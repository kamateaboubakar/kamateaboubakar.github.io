<?php

namespace Pv\ZoneBootstrap\TableauDonnees ;

class Nav2TableauDonnees extends \Pv\ZoneWeb\TableauDonnees\NavigateurRangees
{
	public function Execute(& $script, & $composant)
	{
		return $this->ExecuteInstructions($script, $composant) ;
	}
	protected function ExecuteInstructions(& $script, & $composant)
	{
		$ctn = '' ;
		$classeCSSBtn = $composant->ClasseCSSBtnNav ;
		$parametresRendu = $composant->ParametresRendu() ;
		$ctn .= '<div class="card bg-light"><div class="card-footer">'.PHP_EOL ;
		$ctn .= '<div class="row">'.PHP_EOL ;
		$ctn .= '<div class="col-12 col-sm-6 LiensRangee">'.PHP_EOL ;
		$paramPremiereRangee = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => 0)) ;
		$ctn .= '<a class="btn '.$classeCSSBtn.'" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramPremiereRangee).'" title="'.$composant->TitrePremiereRangee.'">'.$composant->LibellePremiereRangee.'</a>'.PHP_EOL ;
		$ctn .= $composant->SeparateurLiensRangee ;
		if($composant->RangeeEnCours > 0)
		{
			$paramRangeePrecedente = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => ($composant->RangeeEnCours - 1) * $composant->MaxElements)) ;
			$ctn .= '<a class="btn '.$classeCSSBtn.'" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramRangeePrecedente).'" title="'.$composant->TitreRangeePrecedente.'">'.$composant->LibelleRangeePrecedente.'</a>'.PHP_EOL ;
		}
		else
		{
			$ctn .= '<a class="btn '.$classeCSSBtn.'" title="'.$composant->TitreRangeePrecedente.'">'.$composant->LibelleRangeePrecedente.'</a>'.PHP_EOL ;
		}
		$ctn .= $composant->SeparateurLiensRangee ;
		$ctn .= '<input type="text" size="4" onChange="var nb = 0 ; try { nb = parseInt(this.value) ; } catch(ex) { } if (isNaN(nb) == true) { nb = 0 ; } SoumetEnvoiFiltres'.$composant->IDInstanceCalc.'({'.htmlentities(svc_json_encode($composant->NomParamIndiceDebut())).' : (nb - 1) * '.$composant->MaxElements.'}) ;" value="'.($composant->RangeeEnCours + 1).'" style="text-align:center" />'.PHP_EOL ;
		$ctn .= $composant->SeparateurLiensRangee ;
		//echo $composant->RangeeEnCours." &lt; ".(intval($composant->TotalElements / $composant->MaxElements) - 1) ;
		if($composant->RangeeEnCours < intval($composant->TotalElements / $composant->MaxElements))
		{
			$paramRangeeSuivante = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => ($composant->RangeeEnCours + 1) * $composant->MaxElements)) ;
			$ctn .= '<a class="btn '.$classeCSSBtn.'" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramRangeeSuivante).'" title="'.$composant->TitreRangeeSuivante.'">'.$composant->LibelleRangeeSuivante.'</a>'.PHP_EOL ;
		}
		else
		{
			$ctn .= '<a class="btn '.$classeCSSBtn.'" class="btn" title="'.$composant->TitreRangeeSuivante.'">'.$composant->LibelleRangeeSuivante.'</a>'.PHP_EOL ;
		}
		$paramDerniereRangee = array_merge($parametresRendu, array($composant->NomParamIndiceDebut() => intval($composant->TotalElements / $composant->MaxElements) * $composant->MaxElements)) ;
		$ctn .= $composant->SeparateurLiensRangee ;
		$ctn .= '<a class="btn '.$classeCSSBtn.'" href="javascript:'.$composant->AppelJsEnvoiFiltres($paramDerniereRangee).'" title="'.$composant->TitreDerniereRangee.'">'.$composant->LibelleDerniereRangee.'</a>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '<div align="right" class="InfosRangees col-12 col-sm-6">'.PHP_EOL ;
		$valeursRangee = array(
			'IndiceDebut' => $composant->IndiceDebut,
			'NoDebut' => $composant->IndiceDebut + 1,
			'IndiceFin' => $composant->IndiceFin,
			'NoFin' => $composant->IndiceFin,
			'TotalElements' => $composant->TotalElements,
		) ;
		$ctn .= \Pv\Misc::_parse_pattern($composant->FormatInfosRangee, $valeursRangee) ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}