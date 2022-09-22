<?php

namespace Pv\ApiRestful\Filtre ;

class ArgRoute extends Filtre
{
	public $Role = "arg_route" ;
	public $TypeLiaisonParametre = "arg_route" ;
	public function ObtientValeurParametre()
	{
		if($this->EstNul($this->ApiParent))
		{
			return $this->ValeurVide ;
		}
		return $this->ApiParent->ArgRoute($this->NomParametreLie, $this->ValeurParDefaut) ;
	}
}