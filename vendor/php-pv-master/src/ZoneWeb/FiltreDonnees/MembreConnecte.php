<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class MembreConnecte extends \Pv\ZoneWeb\FiltreDonnees\FiltreDonnees
{
	public $Role = "membre_connecte" ;
	public $TypeLiaisonParametre = "membre_connecte" ;
	public function ObtientValeurParametre()
	{
		// print_r($this->ZoneParent->Membership->MemberLogged->RawData) ;
		if($this->EstNul($this->ZoneParent) || $this->EstNul($this->ZoneParent->Membership) || $this->EstNul($this->ZoneParent->Membership->MemberLogged))
		{
			return $this->ValeurVide ;
		}
		if(isset($this->ZoneParent->Membership->MemberLogged->RawData[$this->NomParametreDonnees]))
		{
			return $this->ZoneParent->Membership->MemberLogged->RawData[$this->NomParametreDonnees] ;
		}
	}
}