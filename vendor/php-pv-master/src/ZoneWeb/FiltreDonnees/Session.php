<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class Session extends \Pv\ZoneWeb\FiltreDonnees\FiltreDonnees
{
	public $Role = "session" ;
	public $TypeLiaisonParametre = "session" ;
	public function ObtientValeurParametre()
	{
		return (isset($_SESSION[$this->NomParametreLie])) ? $_SESSION[$this->NomParametreLie] : $this->ValeurVide ;
	}
}