<?php

namespace Pv\ZoneWeb\Commande ;

class AppliqueScriptParent extends Executer
{
	public $NomMethodeScript ;
	public $MsgMethodeNonTrouvee = "La methode %s n'existe pas dans le script parent" ;
	protected function ExecuteInstructions()
	{
		$nomMtd = ($this->NomMethodeScript != '') ? $this->NomMethodeScript : "AppliqueCommande" ;
		if(method_exists($this->ScriptParent, $nomMtd))
		{
			call_user_func_array(array($this->ScriptParent, $nomMtd), array(& $this)) ;
		}
		else
		{
			$this->RenseigneErreur(sprintf($this->MsgMethodeNonTrouvee, $nomMtd)) ;
		}
	}
}
