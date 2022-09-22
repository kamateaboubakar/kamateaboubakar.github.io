<?php

namespace Pv\ZoneWeb\Commande ;

class FormulaireDonnees extends \Pv\ZoneWeb\Commande\Commande
{
	public $NecessiteFormulaireDonnees = 1 ;
	public $InscrireLienAnnuler = 0 ;
	public $InscrireLienReprendre = 0 ;
	public $LibelleLienReprendre = "Reprendre" ;
	public $LibelleLienAnnuler = "Retour" ;
	public $UrlLienAnnuler = "" ;
	public $NomScriptExecutionSucces = "" ;
	public $ParamsScriptExecutionSucces = array() ;
	protected function VerifiePreRequis()
	{
		$this->VerifieFichiersUpload($this->FormulaireDonneesParent->FiltresEdition) ;
	}
	public function Execute()
	{
		parent::Execute() ;
		$this->RedirigeScriptExecutionSucces() ;
	}
	protected function RedirigeScriptExecutionSucces()
	{
		if($this->StatutExecution != 1 || $this->NomScriptExecutionSucces == '')
		{
			return ;
		}
		$script = $this->ZoneParent->Scripts[$this->NomScriptExecutionSucces] ;
		if($this->EstPasNul($script))
		{
			$this->ZoneParent->SauveMessageExecutionSession($this->StatutExecution, $this->MessageExecution, $this->ScriptParent->NomElementZone) ;
			\Pv\Misc::redirect_to($script->ObtientUrlParam($this->ParamsScriptExecutionSucces)) ;
		}
	}
}