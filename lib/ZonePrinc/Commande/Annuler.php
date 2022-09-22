<?php

namespace Rpa2p\ZonePrinc\Commande ;

class Annuler extends \Pv\ZoneWeb\Commande\Executer
{
	protected function ExecuteInstructions()
	{
		$this->ScriptParent->AppliqueCmdAnnulFormPrincAuto($this) ;
		$this->ScriptParent->AppliqueCmdAnnulFormPrinc($this) ;
	}
}
