<?php

namespace Pv\ApiRestful\Filtre ;

class Session extends Filtre
{
	public $Role = "session" ;
	public $TypeLiaisonParametre = "session" ;
	public function ObtientValeurParametre()
	{
		return (isset($_SESSION[$this->NomParametreLie])) ? $_SESSION[$this->NomParametreLie] : $this->ValeurVide ;
	}
}