<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Booleen extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $ValeursPositivesAcceptees = array("1", "true", "vrai") ;
	public $CasseInsensitive = 1 ;
	public $ValeurPositive = "Oui" ;
	public $ValeurNegative = "Non" ;
	public $StyleValPositive = "color:green" ;
	public $StyleValNegative = "color:red" ;
	public $NomClasseCSSValPositive = "" ;
	public $NomClasseCSSValNegative = "" ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$valeurEntree = $ligne[$colonne->NomDonnees] ;
		if($this->CasseInsensitive)
		{
			$valeurEntree = strtolower($ligne[$colonne->NomDonnees]) ;
		}
		return (in_array($valeurEntree, $this->ValeursPositivesAcceptees)) ? $this->RenduValPositive() : $this->RenduValNegative() ;
	}
	public function InstrsJsPrepareRendu()
	{
		$ctn = '' ;
		$ctn .= 'var valeursVrai'.$this->IDInstanceCalc.' = '.svc_json_encode($this->ValeursPositivesAcceptees).' ;' ;
		return $ctn ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = '' ;
		if($colonne->NomDonnees == '')
		{
			return '' ;
		}
		$nomDonnees = svc_json_encode($colonne->NomDonnees) ;
		$ctn .= 'var valChoix, val = "" ;
if(donnees['.$nomDonnees.'] !== undefined) {
valChoix = donnees['.$nomDonnees.'] ;
}
if(valeursVrai'.$this->IDInstanceCalc.'.indexOf(valChoix) > -1) {
val = '.svc_json_encode('<span style="'.$this->StyleValPositive.'">'.$this->ValeurPositive.'</span>').' ;
} else {
val = '.svc_json_encode('<span style="'.$this->StyleValNegative.'">'.$this->ValeurNegative.'</span>').' ;
}
noeudCellule.innerHTML = val ;' ;
		return $ctn ;
	}
	protected function RenduValPositive()
	{
		$ctn = '' ;
		$ctn .= '<span' ;
		if($this->StyleValPositive != '')
			$ctn .= ' style="'.$this->StyleValPositive.'"' ;
		if($this->NomClasseCSSValPositive != '')
			$ctn .= ' class="'.$this->NomClasseCSSValPositive.'"' ;
		$ctn .= '>' ;
		$ctn .= $this->ValeurPositive ;
		$ctn .= '</span>' ;
		return $ctn ;
	}
	protected function RenduValNegative()
	{
		$ctn = '' ;
		$ctn .= '<span' ;
		if($this->StyleValNegative != '')
			$ctn .= ' style="'.$this->StyleValNegative.'"' ;
		if($this->NomClasseCSSValNegative != '')
			$ctn .= ' class="'.$this->NomClasseCSSValNegative.'"' ;
		$ctn .= '>' ;
		$ctn .= $this->ValeurNegative ;
		$ctn .= '</span>' ;
		return $ctn ;
	}
}