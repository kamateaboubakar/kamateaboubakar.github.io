<?php

namespace Pv\ZoneWeb\ActionCommande ;

class AppliqueZoneParent extends ActionCommande
{
	public $NomMethodeScript ;
	public $MsgMethodeNonTrouvee = "La methode %s n'existe pas dans le script parent" ;
	public function Execute()
	{
		$nomMtd = ($this->NomMethodeScript != '') ? $this->NomMethodeScript : "AppliqueActionCommande" ;
		$zone = & $this->ZoneParent ;
		if(method_exists($zone, $nomMtd))
		{
			$ctn = call_user_func_array(array($zone, $nomMtd), array(& $this)) ;
		}
		else
		{
			$ctn = sprintf($this->MsgMethodeNonTrouvee, $nomMtd) ;
		}
		if($ctn == '')
		{
			$ctn = parent::Execute() ;
		}
		return $ctn ;
	}
}