<?php

namespace Pv\ZoneWeb\Tache ;

class GestTaches extends \Pv\Objet\Objet
{
	public $NomDossierTaches = "taches" ;
	protected $Taches = array() ;
	public $ZoneParent ;
	public $ApplicationParent ;
	public $NomElementZone = "" ;
	public $Activer = 1 ;
	public function EstPret()
	{
		return ($this->Activer == 1 && is_dir($this->ObtientCheminDossierTaches())) ;
	}
	public function AdopteZone($nom, & $zone)
	{
		$this->ZoneParent = & $zone ;
		$this->NomElementZone = $nom ;
		$this->ApplicationParent = & $zone->ApplicationParent ;
	}
	public function ObtientCheminDossierTaches()
	{
		return dirname($this->ZoneParent->ObtientCheminFichierRelatif()).DIRECTORY_SEPARATOR.$this->NomDossierTaches ;
	}
	public function & ObtientTaches()
	{
		return $this->Taches ;
	}
	public function InsereTache($nom, $tache)
	{
		$this->InscritTache($nom, $tache) ;
		return $tache ;
	}
	public function InscritTache($nom, & $tache)
	{
		$this->Taches[$nom] = & $tache ;
		$tache->AdopteGest($nom, $this) ;
	}
	public function Execute()
	{
		if(! $this->EstPret())
		{
			return ;
		}
		$taches = $this->ObtientTaches() ;
		foreach($taches as $i => & $tache)
		{
			if($tache->EstPret())
			{
				$this->LanceTache($tache->NomElementGest) ;
			}
		}
	}
	public function LanceTache(& $nomTache, $params=array())
	{
		if(! isset($this->Taches[$nomTache]))
		{
			return false ;
		}
		$tache = & $this->Taches[$nomTache] ;
		$urlZone = $this->ZoneParent->ObtientUrl() ;
		$parts = parse_url($urlZone) ;
		$port = (isset($parts["port"]) && $parts["port"] != '') ? $parts["port"] : 80 ;
		$chaineParams = http_build_query($params) ;
		if($chaineParams != "")
		{
			$chaineParams = "&".$chaineParams ;
		}
		$fh = fsockopen($parts["host"], $port, $errno, $errstr, 30);
		if ($fh)
		{
			$ctn = "GET ".$parts["path"]."?".urlencode($this->ZoneParent->NomParamTacheAppelee)."=".urlencode($tache->NomElementGest).$chaineParams." HTTP/1.0\r\n";
			$ctn .= "Host: ".$parts["host"].":".$port."\r\n" ;
			$ctn .= "Content-Type: text/html\r\n" ;
			$ctn .= "Connection: Close\r\n\r\n" ;
			fputs($fh, $ctn) ;
			fclose($fh) ;
		}
	}
}