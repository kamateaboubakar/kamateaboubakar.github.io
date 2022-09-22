<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class Fixe extends \Pv\ZoneWeb\FiltreDonnees\FiltreDonnees
{
	public $TypeLiaisonParametre = "hidden" ;
	public $Role = "fixe" ;
	public function NePasInclure()
	{
		return 0 ;
	}
	public function ObtientValeurParametre()
	{
		return $this->ValeurParDefaut ;
	}
}