<?php

namespace Pv\ZoneWeb\Commande ;

class AppliqueZoneParent extends Executer
{
	public $NomMethodeZone ;
	public $MsgMethodeNonTrouvee = "La methode %s n'existe pas dans le zone parent" ;
	protected function ExecuteInstructions()
	{
		$nomMtd = ($this->NomMethodeZone != '') ? $this->NomMethodeZone : "AppliqueCommande" ;
		if(method_exists($this->ZoneParent, $nomMtd))
		{
			call_user_func_array(array($this->ZoneParent, $nomMtd), array(& $this, & $this->ScriptParent)) ;
		}
		else
		{
			$this->RenseigneErreur(sprintf($this->MsgMethodeNonTrouvee, $nomMtd)) ;
		}
		$this->ScriptParent->AppliqueCommande($this) ;
	}
}
