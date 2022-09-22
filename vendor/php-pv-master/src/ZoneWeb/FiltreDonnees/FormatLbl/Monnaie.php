<?php

namespace Pv\ZoneWeb\FiltreDonnees\FormatLbl ;

class Monnaie extends \Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl
{
	public $MaxDecimals = 3 ;
	public $MinChiffres = 1 ;
	public function Rendu($valeur, & $composant)
	{
		return \Pv\Misc::format_money($valeur, $this->MaxDecimals, $this->MinChiffres) ;
	}
}