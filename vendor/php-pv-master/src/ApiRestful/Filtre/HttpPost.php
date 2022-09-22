<?php

namespace Pv\ApiRestful\Filtre ;

class HttpPost extends HttpRequest
{
	public $Role = "post" ;
	public $TypeLiaisonParametre = "post" ;
	protected function CalculeValeurBruteNonCorrigee()
	{
		$this->ValeurBruteNonCorrigee = (array_key_exists($this->NomParametreLie, $_POST)) ? $_POST[$this->NomParametreLie] : $this->ValeurVide ;
	}
}