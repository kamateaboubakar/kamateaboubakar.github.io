<?php

namespace Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur ;

class Utf8 extends \Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\Correcteur
{
	public function Applique($valeur, & $filtre)
	{
		return utf8_encode($valeur) ;
	}
}