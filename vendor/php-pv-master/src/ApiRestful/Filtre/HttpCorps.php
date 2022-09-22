<?php

namespace Pv\ApiRestful\Filtre ;

class HttpCorps extends Filtre
{
	public $Role = "corps_http" ;
	public $TypeLiaisonParametre = "hidden" ;
	public $Source = null ;
	public function ObtientValeurParametre()
	{
		$valeur = $this->ValeurVide ;
		$nomParam = $this->NomParametreLie ;
		if(is_object($this->ApiParent->Requete->Corps) && isset($this->ApiParent->Requete->Corps->$nomParam))
		{
			$valeur = $this->ApiParent->Requete->Corps->$nomParam ;
		}
		return $valeur ;
	}
}