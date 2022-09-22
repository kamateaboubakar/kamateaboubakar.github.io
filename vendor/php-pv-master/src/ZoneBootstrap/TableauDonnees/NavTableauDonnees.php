<?php

namespace Pv\ZoneBootstrap\TableauDonnees ;

class NavTableauDonnees extends \Pv\ZoneWeb\TableauDonnees\NavigateurRangees
{
	public $MaxRangeesPrec = 3 ;
	public $MaxRangeesSuiv = 3 ;
	public $InclureInfosRangees = false ;
	public $ColMdInfosRangees = 8 ;
	public function Execute(& $script, & $composant)
	{
		return $this->ExecuteInstructions($script, $composant) ;
	}
	protected function ExecuteInstructions(& $script, & $composant)
	{
		$ctn = '' ;
		$parametresRendu = $composant->ParametresRendu() ;
		if($this->InclureInfosRangees == true)
		{
			$ctn .= '<div class="row">
<div class="col-sm-12 col-md-'.(12 - $this->ColMdInfosRangees).' d-flex justify-content-sm-center justify-content-md-start">' ;
		}
		$ctn .= '<nav aria-label="" class="NavigateurRangees">'.PHP_EOL ;
		$ctn .= '<ul class="pagination'.(($this->InclureInfosRangees == false) ? ' justify-content-center' : '').'">'.PHP_EOL ;
		$paramPremiereRangee = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => 0)) ;
		$ctn .= '<li class="page-item"><a class="page-link" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramPremiereRangee).'" title="'.$composant->TitrePremiereRangee.'">'.$composant->LibellePremiereRangee.'</a></li>'.PHP_EOL ;
		if($composant->RangeeEnCours > 0)
		{
			if($composant->RangeeEnCours - $this->MaxRangeesPrec > 0)
			{
				$ctn .= '<li class="page-item"><a class="page-link" href="javascript:;" title="'.$composant->TitrePremiereRangee.'">...</a></li>' ;
			}
			for($i=$composant->RangeeEnCours - $this->MaxRangeesPrec; $i<$composant->RangeeEnCours; $i++)
			{
				$rangeeEnCours = $i ;
				if($rangeeEnCours < 0)
				{
					continue ;
				}
				$paramRangeePrecedente = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => ($rangeeEnCours) * $composant->MaxElements)) ;
				$ctn .= '<li class="page-item"><a class="page-link" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramRangeePrecedente).'" title="'.($rangeeEnCours + 1).'">'.($rangeeEnCours + 1).'</a></li>'.PHP_EOL ;
			}
		}
		$paramRangee = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => ($composant->RangeeEnCours) * $composant->MaxElements)) ;
		$ctn .= '<li class="page-item active"><a class="page-link" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramRangee).'" title="'.($composant->RangeeEnCours + 1).'">'.($composant->RangeeEnCours + 1).'</a></li>'.PHP_EOL ;
		if($composant->RangeeEnCours < $composant->TotalRangees - 1)
		{
			for($i=$composant->RangeeEnCours + 1; $i<$composant->RangeeEnCours + $this->MaxRangeesSuiv + 1 && $i < $composant->TotalRangees; $i++)
			{
				$rangeeEnCours = $i ;
				if($rangeeEnCours >= $composant->TotalRangees)
				{
					break ;
				}
				$paramRangeeSuivante = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => ($rangeeEnCours) * $composant->MaxElements)) ;
				$ctn .= '<li class="page-item"><a class="page-link" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramRangeeSuivante).'" title="'.($rangeeEnCours + 1).'">'.($rangeeEnCours + 1).'</a></li>'.PHP_EOL ;
			}
			if($composant->RangeeEnCours + $this->MaxRangeesSuiv < $composant->TotalRangees - 1)
			{
				$ctn .= '<li class="page-item"><a class="page-link" href="javascript:;" title="">...</a></li>' ;
			}
		}
		$paramDerniereRangee = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => intval($composant->TotalElements / $composant->MaxElements) * $composant->MaxElements)) ;
		$ctn .= '<li class="page-item"><a class="page-link" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramDerniereRangee).'" title="'.$composant->TitreDerniereRangee.'">'.$composant->LibelleDerniereRangee.'</a></li>'.PHP_EOL ;
		$ctn .= '</ul>'.PHP_EOL ;
		$ctn .= '</nav>' ;
		if($this->InclureInfosRangees == true)
		{
			$ctn .= '</div>
<div class="col-sm-12 col-md-'.($this->ColMdInfosRangees).'  d-flex justify-content-sm-center justify-content-md-end">' ;
			$valeursRangee = array(
				'IndiceDebut' => $composant->IndiceDebut,
				'NoDebut' => $composant->IndiceDebut + 1,
				'IndiceFin' => $composant->IndiceFin,
				'NoFin' => $composant->IndiceFin,
				'TotalElements' => $composant->TotalElements,
			) ;
			$ctn .= \Pv\Misc::_parse_pattern($composant->FormatInfosRangee, $valeursRangee) ;
			$ctn .= '</div>
</div>' ;
		}
		return $ctn ;
	}
}