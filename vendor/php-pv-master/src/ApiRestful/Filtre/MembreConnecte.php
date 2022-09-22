<?php

namespace Pv\ApiRestful\Filtre ;

class MembreConnecte extends Filtre
{
	public $Role = "membre_connecte" ;
	public $TypeLiaisonParametre = "membre_connecte" ;
	public function ObtientValeurParametre()
	{
		// print_r($this->ApiParent->Membership->MemberLogged->RawData) ;
		if($this->EstNul($this->ApiParent) || $this->EstNul($this->ApiParent->Membership) || $this->EstNul($this->ApiParent->Membership->MemberLogged))
		{
			return $this->ValeurVide ;
		}
		if(isset($this->ApiParent->Membership->MemberLogged->RawData[$this->NomParametreLie]))
		{
			// print "ssds ".$this->ApiParent->Membership->MemberLogged->RawData[$this->NomParametreLie] ;
			return $this->ApiParent->Membership->MemberLogged->RawData[$this->NomParametreLie] ;
		}
	}
}