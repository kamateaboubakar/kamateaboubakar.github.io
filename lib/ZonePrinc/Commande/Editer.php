<?php

namespace Rpa2p\ZonePrinc\Commande ;

class Editer extends \Pv\ZoneWeb\Commande\Commande
{
	protected function ExecuteInstructions()
	{
		parent::ExecuteInstructions() ;
		$this->ScriptParent->AppliqueCmdExecFormPrincAuto($this) ;
		$this->ScriptParent->AppliqueCmdExecFormPrinc($this) ;
		if($this->StatutExecution == 1)
		{
			if($this->ScriptParent->ModeEditionElemCmdExec == 3)
			{
				$this->CacherFormulaireFiltresSiSucces = 1 ;
			}
			elseif($this->ScriptParent->ModeEditionElemCmdExec == 1)
			{
				$this->FormulaireDonneesParent->AnnuleLiaisonParametres() ;
			}
		}
	}
}
