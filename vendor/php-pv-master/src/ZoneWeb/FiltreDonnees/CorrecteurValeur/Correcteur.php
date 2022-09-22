<?php

namespace Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur ;

class Correcteur
{
	public function Applique($valeur, & $filtre)
	{
		return $valeur ;
	}
	public function AppliquePourRendu($valeur, & $filtre)
	{
		return $valeur ;
	}
	public function AppliquePourTraitement($valeur, & $filtre)
	{
		return $valeur ;
	}
	public function AppliquePourColonne($valeur, & $defCol)
	{
		return $valeur ;
	}
}