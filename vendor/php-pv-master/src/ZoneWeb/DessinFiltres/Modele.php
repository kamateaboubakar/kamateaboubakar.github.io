<?php

namespace Pv\ZoneWeb\DessinFiltres ;

class Modele extends \Pv\ZoneWeb\DessinFiltres\Html
{
	public $ContenuModele ;
	public $ContenuAvantModeleFiltre ;
	public $ContenuModeleFiltre ;
	public $ContenuApresModeleFiltre ;
	public $ContenuModeleUse ;
	protected function DetecteContenuModeleUse(& $filtres)
	{
		$this->ContenuModeleUse = $this->ContenuModele ;
		if($this->ContenuModeleUse == '')
		{
			$this->ContenuModeleUse = $this->ContenuAvantModeleFiltre ;
			$nomFiltres = array_keys($filtres) ;
			foreach($nomFiltres as $i => $nomFiltre)
			{
				$filtre = & $filtres[$nomFiltre] ;
				$this->ContenuModeleUse .= \Pv\Misc::_parse_pattern($this->ContenuModeleFiltre, array("Libelle" => $filtre->NomParametreLie.".Libelle", "Valeur" => $filtre->NomParametreLie.".Valeur")) ;
			}
			$this->ContenuModeleUse .= $this->ContenuApresModeleFiltre ;
		}
	}
	public function Execute(& $script, & $composant, $parametres)
	{
		$ctn = '' ;
		$filtres = $composant->ExtraitFiltresDeRendu($parametres, $this->FiltresCaches) ;
		$this->DetecteContenuModeleUse($filtres) ;
		$nomFiltres = array_keys($filtres) ;
		$filtreRendus = 0 ;
		$params = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = $parametres[$i] ;
			$params = array_merge($params, $this->ExtraitParamsModeleFiltre($filtre)) ;
		}
		$ctn = \Pv\Misc::_parse_pattern($this->ContenuModeleUse, $params) ;
		return $ctn ;
	}
	protected function ExtraitParamsModeleFiltre(& $filtre)
	{
		$params = array() ;
		$params[$filtre->NomParametreLie.".Libelle"] = $this->RenduLibelleFiltre($filtre) ;
		$params[$filtre->NomParametreLie.".Etiquette"] = $this->RenduLibelleFiltre($filtre) ;
		$params[$filtre->NomParametreLie.".Valeur"] = $filtre->Lie() ;
		$params[$filtre->NomParametreLie.".ValUrl"] = urlencode($params[$filtre->NomParametreLie.".Valeur"]) ;
		$params[$filtre->NomParametreLie.".ValEntiteHtml"] = htmlentities($params[$filtre->NomParametreLie.".Valeur"]) ;
		$params[$filtre->NomParametreLie.".ValAttrHtml"] = htmlspecialchars($params[$filtre->NomParametreLie.".Valeur"]) ;
		return $params ;
	}
}

class Modele extends \Pv\ZoneWeb\DessinFiltres\Modele
{
}