<?php

namespace Pv\ServeurSocket ;

class ServeurSocket extends \Pv\ServicePersist\ServicePersist
{
	protected $Flux = false ;
	protected $FluxClient = false ;
	protected $FluxEnvoi = false ;
	public $Scheme = "tcp" ;
	public $Hote = "127.0.0.1" ;
	protected $Adresse = "" ;
	public $Port = 4401 ;
	public $DelaiOuvrFlux = 30 ;
	public $SauveEtatChaqueDemande = 1 ;
	public $DelaiLectFlux = 0 ;
	public $DelaiOuvrEnvoi = 30 ;
	public $DelaiLectEnvoi = 0 ;
	public $LimiterDelaiBoucle = 0 ;
	public $DelaiInactivite = 30 ;
	public $EcartInactiviteBoucle = 5 ;
	public $TaillePaquetFlux = 1024 ;
	public $FormatPaquet ;
	public $DelaiAttente = 0 ;
	public $MaxSessions = 0 ;
	protected $DernErrEnvoiDemande ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->FormatPaquet = $this->CreeFormatPaquet() ;
		register_shutdown_function(array(& $this, 'AnnuleFlux')) ;
	}
	protected function CreeFormatPaquet()
	{
		return new \Pv\ServeurSocket\Format\Natif() ;
	}
	protected function ExecuteSession()
	{
		$this->FormatPaquet = $this->CreeFormatPaquet() ;
		$this->OuvreFlux() ;
		if($this->ErreurOuvr->Trouve())
		{
			echo $this->ErreurOuvr->No."# ".$this->ErreurOuvr->Contenu."\n" ;
			exit ;
		}
		$this->PrepareReception() ;
		$this->RecoitDemandes() ;
		$this->TermineReception() ;
		$this->FermeFlux() ;
	}
	protected function PrepareReception()
	{
	}
	protected function TermineReception()
	{
	}
	public function ExtraitAdresse()
	{
		return $this->Scheme.'://'.$this->Hote.':'.$this->Port ;
	}
	protected function OuvreFlux()
	{
		$this->ErreurOuvr = new \Pv\ServeurSocket\ErreurOuvr() ;
		$this->Adresse = $this->ExtraitAdresse() ;
		$this->Flux = stream_socket_server($this->Adresse, $this->ErreurOuvr->No, $this->ErreurOuvr->Contenu) ;
		if($this->Flux === false && $this->ErreurOuvr->No == 0)
		{
			$this->ErreurOuvr->No = -1 ;
			$this->ErreurOuvr->Contenu = 'Impossible d\'ouvrir une connexion socket' ;
		}
	}
	public function EnvoieDemande($contenu)
	{
		$msgErreur = "" ;
		// echo $this->ExtraitAdresse() ;
		$this->FluxEnvoi = stream_socket_client($this->ExtraitAdresse(), $codeErreur, $msgErreur, $this->DelaiOuvrFlux, STREAM_CLIENT_CONNECT | STREAM_CLIENT_ASYNC_CONNECT) ;
		$partieResult = '' ;
		$resultat = '' ;
		$this->DernErrEnvoiDemande = '' ;
		$longueurMax = 1024 ;
		if($this->FluxEnvoi !== false)
		{
			$ctnEncode = $this->FormatPaquet->Encode($contenu) ;
			$ok = true ;
			$msgErreur = null ;
			if($ctnEncode != '')
			{
				try
				{
					$ok = fputs($this->FluxEnvoi, $ctnEncode) ;
				}
				catch(Exception $ex)
				{
					$msgErreur = $ex->getMessage() ;
				}
			}
			if($ok)
			{
				if($this->DelaiLectEnvoi > 0)
				{
					stream_set_timeout($this->FluxEnvoi, $this->DelaiLectEnvoi) ;
				}
				do
				{
					$partieResult = fread($this->FluxEnvoi, $longueurMax) ;
					if($partieResult !== false)
					{
						$resultat .= $partieResult ;
					}
					else
					{
						$this->DernErrEnvoiDemande = "lecture_flux_socket_echoue" ;
						break ;
					}
				}
				while(strlen($partieResult) == $longueurMax) ;
			}
			else
			{
				if($msgErreur != null)
				{
					$this->DernErrEnvoiDemande = $msgErreur ;
				}
				else
				{
					$this->DernErrEnvoiDemande = "ecriture_flux_socket_echoue" ;
				}
			}
			$this->FermeFluxEnvoi() ;
		}
		else
		{
			$this->DernErrEnvoiDemande = $codeErreur.'#'.$msgErreur ;
		}
		return $this->FormatPaquet->Decode($resultat) ;
	}
	public function ObtientErrEnvoiDemande()
	{
		return $this->DernErrEnvoiDemande ;
	}
	protected function RecoitDemandes()
	{
		$delaiInactivite = ($this->LimiterDelaiBoucle) ? $this->DelaiBoucle - $this->EcartInactiviteBoucle : $this->DelaiInactivite ;
		// print_r(get_resource_type($this->Flux)) ;
		while($this->FluxClient = @stream_socket_accept($this->Flux, $delaiInactivite))
		{
			$paquet = new \Pv\ServeurSocket\Packet() ;
			if($this->DelaiLectFlux > 0)
			{
				stream_set_timeout($this->FluxClient, $this->DelaiLectFlux) ;
			}
			do
			{
				$partiePaquet = fread($this->FluxClient, $this->TaillePaquetFlux) ;
				$paquet->Contenu .= $partiePaquet ;
			}
			while(strlen($partiePaquet) == $this->TaillePaquetFlux && ! feof($this->FluxClient)) ;
			$resultat = $this->TraitePaquet($paquet) ;
			fputs($this->FluxClient, $resultat) ;
			$this->FermeFluxClient() ;
			if($this->SauveEtatChaqueDemande == 1)
			{
				$this->SauveEtat() ;
			}
		}
	}
	protected function FermeFluxEnvoi()
	{
		if(is_resource($this->FluxEnvoi))
		{
			fclose($this->FluxEnvoi) ;
			$this->FluxEnvoi = false ;
		}
	}
	protected function FermeFluxClient()
	{
		if(is_resource($this->FluxClient))
		{
			fclose($this->FluxClient) ;
			$this->FluxClient = false ;
		}
	}
	protected function TraitePaquet($paquet)
	{
		$contenuDecode = $this->FormatPaquet->Decode($paquet->Contenu) ;
		// print "Decode : ".$paquet->Contenu."\n\t".$contenuDecode."\n" ;
		$resultat = $this->RepondDemande($contenuDecode) ;
		return $this->FormatPaquet->Encode($resultat) ;
	}
	protected function RepondDemande($contenu)
	{
		return null ;
	}
	protected function FermeFlux()
	{
		if(is_resource($this->Flux))
		{
			fclose($this->Flux) ;
			$this->Flux = false ;
		}
	}
	public function AnnuleFlux()
	{
		$this->FermeFluxEnvoi() ;
		$this->FermeFluxClient() ;
		$this->FermeFlux() ;
	}
}