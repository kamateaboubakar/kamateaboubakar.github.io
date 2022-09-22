<?php

namespace Pv\ApiRestful\Filtre ;

class HttpGet extends HttpRequest
{
	public $Role = "get" ;
	public $TypeLiaisonParametre = "get" ;
	protected function CalculeValeurBruteNonCorrigee()
	{
		$this->ValeurBruteNonCorrigee = (array_key_exists($this->NomParametreLie, $_GET)) ? $_GET[$this->NomParametreLie] : $this->ValeurVide ;
	}
}