<?php

namespace Pv\ZoneWeb\DessinFiltres ;

class DessinFiltres
{
	public $FiltresCaches = array() ;
	public function Execute(& $script, & $composant, $parametres)
	{
		return "" ;
	}
	public function RenduFiltre(& $filtre, & $composant)
	{
		$ctn = '' ;
		// print $filtre->NomParametreLie.' : '.$filtre->EstEtiquette.'<br>' ;
		if($composant->Editable && $filtre->EstEtiquette == 0)
		{
			// $ctn .= $filtre->Lie() ;
			$ctn .= $filtre->Rendu() ;
		}
		else
		{
			$ctn .= $filtre->Etiquette() ;
		}
		return $ctn ;
	}
}