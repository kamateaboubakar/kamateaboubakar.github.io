<?php

namespace Pv\ZoneWeb\TableauDonnees ;

class NavTableauDonnees extends \Pv\ZoneWeb\TableauDonnees\NavigateurRangees
{
	public $TotalPremRangees = 3 ;
	public $TotalRangeesAvant = 2 ;
	public $TotalRangeesApres = 2 ;
	public $TotalDernRangees = 3 ;
	public $SepLiens = "&nbsp;&nbsp;" ;
	public $LibelleEtc = "..." ;
	public $CtnAvantListe ;
	public $CtnApresListe ;
	public $NomClasseSelect = "Selectionne" ;
	protected function ExecuteInstructions(& $script, & $comp)
	{
		$ctn = '' ;
		$ctn .= $this->CtnAvantListe ;
		$dernNoRangeeAffich = -1 ;
		for($i=0; $i<$comp->TotalRangees; $i++)
		{
			$dessineRangees = 0 ;
			if($i <= $this->TotalPremRangees || $i >= $comp->TotalRangees - $this->TotalDernRangees || ($i >= $comp->RangeeEnCours - $this->TotalRangeesAvant && $i <= $comp->RangeeEnCours + $this->TotalRangeesAvant))
			{
				$dessineRangees = 1 ;
			}
			if(! $dessineRangees)
			{
				$dernNoRangeeAffich = -1 ;
				continue ;
			}
			if($dernNoRangeeAffich != $i - 1)
			{
				$ctn .= $this->LibelleEtc. PHP_EOL ;
			}
			if($ctn != "")
				$ctn .= $this->SepLiens. PHP_EOL ;
			$ctn .= $this->RenduLienRangee($script, $comp, $i) ;
			$dernNoRangeeAffich = $i ;
		}
		$ctn .= $this->CtnApresListe ;
		$ctn = '<div class="NavigateurRangees">'.PHP_EOL
			.$ctn.'</div>' ;
		return $ctn ;
	}
	protected function RenduLienRangee(& $script, & $comp, $noRangee)
	{
		$ctn = '' ;
		$paramsRendu = $comp->ParametresRendu() ;
		$paramsRendu[$comp->NomParamIndiceDebut()] = $noRangee * $comp->MaxElements ;
		$ctn .= '<a href="javascript:' ;
		$ctn .= $comp->AppelJsEnvoiFiltres($paramsRendu).'"' ;
		if($noRangee == $comp->RangeeEnCours)
		{
			$ctn .= ' class="'.$this->NomClasseSelect.'"' ;
		}
		$ctn .= '>' ;
		$ctn .= ($noRangee + 1) ;
		$ctn .= '</a>'.PHP_EOL ;
		return $ctn ;
	}
}