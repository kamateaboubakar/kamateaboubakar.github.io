<?php

namespace Pv\ZoneWeb\DessinFiltres ;

class Ligne extends \Pv\ZoneWeb\DessinFiltres\Html
{
	public $Largeur = "" ;
	public $InclureRenduLibelle = 1 ;
	public function Execute(& $script, & $composant, $parametres)
	{
		$filtres = $composant->ExtraitFiltresDeRendu($parametres, $this->FiltresCaches) ;
		$ctn = '' ;
		$ctn .= '<table' ;
		if($this->Largeur != '')
		{
			$ctn .= ' width="'.$this->Largeur.'"' ;
		}
		$ctn .= '>'.PHP_EOL ;
		$nomFiltres = array_keys($filtres) ;
		$filtreRendus = 0 ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = $parametres[$i] ;
			if(! $filtre->RenduPossible())
			{
				continue ;
			}
			if($this->InclureRenduLibelle)
			{
				$ctn .= '<tr>'.PHP_EOL ;
				$ctn .= '<td' ;
				$ctn .= ' valign="top"' ;
				$ctn .= '>'.PHP_EOL ;
				$ctn .= $this->RenduLibelleFiltre($filtre).PHP_EOL ;
				$ctn .= '</td>'.PHP_EOL ;
				$ctn .= '</tr>'.PHP_EOL ;
			}
			$ctn .= '<tr>'.PHP_EOL ;
			$ctn .= '<td' ;
			$ctn .= ' valign="top"' ;
			$ctn .= '>'.PHP_EOL ;
			$ctn .= $this->RenduFiltre($filtre, $composant).PHP_EOL ;
			$ctn .= '</td>'.PHP_EOL ;
			$ctn .= '</tr>'.PHP_EOL ;
		}
		$ctn .= '</table>' ;
		return $ctn ;
	}
}