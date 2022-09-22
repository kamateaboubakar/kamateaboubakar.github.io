<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class HttpGet extends \Pv\ZoneWeb\FiltreDonnees\HttpRequest
{
	public $Role = "get" ;
	public $TypeLiaisonParametre = "get" ;
	protected function CalculeValeurBruteNonCorrigee()
	{
		$this->ValeurBruteNonCorrigee = (array_key_exists($this->NomParametreLie, $_GET)) ? $_GET[$this->NomParametreLie] : $this->ValeurVide ;
	}
}