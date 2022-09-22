<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class PortionDonnees extends \Pv\ZoneWeb\ComposantRendu\ComposantDonneesSimple
{
	public $PrefixeEncUrl = "url_" ;
	public $EncoderUrl = 1 ;
	public $PrefixeEncHtmlEnt = "html_" ;
	public $EncoderHtmlEnt = 1 ;
	public $Encodeurs = array() ;
	public $ElementsBruts = array() ;
	public $Elements = array() ;
	public $ElementsTrouves = 0 ;
	public $ParamsSelection = array() ;
	public $RequeteSelection = "" ;
	public $ContenuModele = "" ;
	protected $ContenuModeleUse = "" ;
	protected $ErreurTrouvee = 0 ;
	protected $ContenuErreurTrouvee = "" ;
	protected $MsgSiErreurTrouvee = "Le composant ne peut s'afficher car une erreur est survenue lors de l'affichage." ;
	protected function ObtientEncodeurs()
	{
		$encodeurs = $this->Encodeurs;
		if($this->EncoderUrl)
		{
			$encodeurs[] = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Url($this->PrefixeEncUrl) ;
		}
		if($this->EncoderHtmlEnt)
		{
			$encodeurs[] = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Html($this->PrefixeEncHtmlEnt) ;
		}
		return $encodeurs ;
	}
	public function & InsereEncodeurDateFr($nomParams=array())
	{
		$encodeur = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\DateFr() ;
		$encodeur->NomParams = $nomParams ;
		$this->InsereEncodeur($encodeur) ;
		return $encodeur ;
	}
	public function & InsereEncodeur($encodeur)
	{
		$this->Encodeurs[] = $encodeur ;
		return $encodeur ;
	}
	public function & InsereEncodeurNonVide($nomParams=array(), $contenu='${luimeme}', $prefixe="non_vide")
	{
		$encodeur = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\NonVide($prefixe) ;
		$encodeur->NomParams = $nomParams ;
		$encodeur->Contenu = $contenu ;
		$this->InsereEncodeur($encodeur) ;
		return $encodeur ;
	}
	protected function VideErreur()
	{
		$this->ErreurTrouvee = 0 ;
		$this->ContenuErreurTrouvee = "" ;
	}
	protected function ConfirmeErreur($msg)
	{
		$this->ErreurTrouvee = 1 ;
		$this->ContenuErreurTrouvee = $msg ;
	}
	protected function PrepareCalcul()
	{
		$this->ElementsTrouves = 0 ;
		$this->VideErreur() ;
		$this->ElementsBruts = array() ;
		$this->Elements = array() ;
	}
	protected function CalculeElements()
	{
		$this->PrepareCalcul() ;
		if($this->ContenuModele == "")
		{
			$this->ConfirmeErreur("Le modele est vide") ;
			return ;
		}
		$this->ElementsBruts = $this->FournisseurDonnees->ExecuteRequete($this->RequeteSelection, $this->ParamsSelection) ;
		// echo $this->FournisseurDonnees->BaseDonnees->ConnectionException ;
		if(! is_array($this->ElementsBruts))
		{
			$this->ConfirmeErreur("La recuperation des elements a echoue") ;
			return ;
		}
		$this->ElementsTrouves = (count($this->ElementsBruts) > 0) ? 1 : 0 ;
		$this->Elements = array() ;
		foreach($this->ElementsBruts as $i => $elem)
		{
			$this->Elements[$i] = $this->ExtraitElementCalc($elem) ;
		}
	}
	protected function ExtraitElementCalc($elem)
	{
		$encodeurs = $this->ObtientEncodeurs() ;
		$result = $elem ;
		foreach($encodeurs as $i => $encodeur)
		{
			$valeurs = ($encodeur->AppliquerTout) ? $elem : array_intersect_key($elem, array_flip($encodeur->NomParams)) ;
			$params = $encodeur->Execute($valeurs, $elem) ;
			if(count($params) == 0)
			{
				continue ;
			}
			$params = \Pv\Misc::array_apply_prefix($params, $encodeur->Prefixe) ;
			$result = array_merge($result, $params) ;
		}
		return $result ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$this->CalculeElements() ;
		if($this->ErreurTrouvee)
		{
			$ctn .= $this->RenduErreurTrouvee() ;
			return $ctn ;
		}
		$ctn .= $this->ContenuAvantRendu ;
		foreach($this->Elements as $i => $elem)
		{
			$ctn .= \Pv\Misc::_parse_pattern($this->ContenuModele, $elem) ;				
		}
		$ctn .= $this->ContenuApresRendu ;
		return $ctn ;
	}
	protected function RenduErreurTrouvee()
	{
		return '<div class="error">'.$this->MsgSiErreurTrouvee.'</div>' ;
	}
}

class PortionDonnees extends \Pv\ZoneWeb\ComposantRendu\PortionDonnees
{
}