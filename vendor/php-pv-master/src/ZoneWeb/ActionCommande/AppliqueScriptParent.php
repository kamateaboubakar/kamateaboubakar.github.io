<?php

namespace Pv\ZoneWeb\ActionCommande ;

class AppliqueScriptParent extends ActionCommande
{
	public $NomMethodeScript ;
	public $MsgMethodeNonTrouvee = "La methode %s n'existe pas dans le script parent" ;
	public function Execute()
	{
		$nomMtd = ($this->NomMethodeScript != '') ? $this->NomMethodeScript : "AppliqueActionCommande" ;
		$script = & $this->ScriptParent ;
		if(method_exists($script, $nomMtd))
		{
			$ctn = call_user_func_array(array($script, $nomMtd), array(& $this)) ;
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