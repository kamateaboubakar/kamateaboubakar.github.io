<?php

namespace Pv\ZoneWeb\FiltreDonnees\FormatEtiquette ;

class Monnaie extends PvFmtEtiquetteFiltre
{
	public $MaxDecimals = 3 ;
	public $MinChiffres = 1 ;
	public function Applique($valeur, & $filtre)
	{
		return \Pv\Misc::format_money($valeur, $this->MaxDecimals, $this->MinChiffres) ;
	}
}