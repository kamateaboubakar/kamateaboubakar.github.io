<?php

namespace Pv\ZoneWeb\Action ;

class EnvoiFichier extends \Pv\ZoneWeb\Action\Action
{
	public $UtiliserTypeMime = 0 ;
	public $UtiliserFichierSource = 1 ;
	public $UtiliserFichierAttache = 1 ;
	public $TypeMime = "" ;
	public $DispositionFichierAttache = "inline" ;
	public $NomFichierAttache = "" ;
	public $ExtensionFichierAttache = "" ;
	public $CheminFichierSource = "" ;
	public $TailleContenu = 0 ;
	public $SupprimerCaractsSpec = 1 ;
	public $AutresEntetes = array() ;
	protected function CalculeTailleContenu()
	{
	}
	public function Execute()
	{
		$this->DetermineFichierAttache() ;
		$this->CalculeTailleContenu() ;
		$this->AfficheEntetes() ;
		$this->AfficheContenu() ;
		exit ;
	}
	protected function DetermineFichierAttache()
	{
		/*
		if($this->ExtensionFichierAttache == "")
		{
			$this->NomFichierAttache = $this->NomElementZone.".".$this->ExtensionFichierAttache ;
		}
		*/
		if($this->UtiliserFichierSource == 1)
		{
			$infosFich = @pathinfo($this->CheminFichierSource) ;
			if($this->ExtensionFichierAttache == "" && $this->CheminFichierSource != "")
			{
				$this->ExtensionFichierAttache = $infosFich["extension"] ;
			}
			if($this->NomFichierAttache == "" && $this->CheminFichierSource != "")
			{
				$this->NomFichierAttache = (isset($infosFich["filename"])) ? $infosFich["filename"] : substr($infosFich["basename"], 0, count($infosFich["basename"]) - (($infosFich["extension"] != '') ? strlen($infosFich["extension"]) + 1 : 0)) ;
			}
		}
	}
	public function SupprimeCaractsSpec($valeur)
	{
		return preg_replace('/[^a-z0-9_\.]/i', '_', $valeur) ;
	}
	protected function AfficheEntetes()
	{
		// echo $this->SupprimeCaractsSpec($this->NomFichierAttache) ;
		// exit ;
		if($this->TypeMime != "")
		{
			Header("Content-type:".$this->TypeMime."\r\n") ;
		}
		if($this->UtiliserFichierAttache == 1 && $this->NomFichierAttache != "")
		{
			Header("Content-disposition:".$this->DispositionFichierAttache."; filename=".$this->SupprimeCaractsSpec($this->NomFichierAttache).(($this->ExtensionFichierAttache != '') ? '.'.$this->ExtensionFichierAttache : '')."\r\n") ;
		}
		if($this->TailleContenu > 0)
		{
			Header("Content-Length:".$this->TailleContenu."\r\n") ;
		}
		foreach($this->AutresEntetes as $i => $entete)
		{
			Header($entete."\r\n") ;
		}
	}
	protected function AfficheContenu()
	{
		if($this->UtiliserFichierSource && $this->CheminFichierSource != "")
		{
			if(file_exists($this->CheminFichierSource))
			{
				$fr = @fopen($this->CheminFichierSource, "rb") ;
				if($fr !== false)
				{
					while(! feof($fr))
					{
						echo fgets($fr) ;
					}
					fclose($fr) ;
				}
				else
				{
					die("Impossible d'acceder au fichier ".$this->CheminFichierSource.". Verifier que les droits en acces et lecture sont bien octroyes") ;
				}
			}
			else
			{
				die("Le fichier ".$this->CheminFichierSource." n'existe pas.") ;
			}
		}
	}
}