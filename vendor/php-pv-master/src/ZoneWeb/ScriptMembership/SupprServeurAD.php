<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class SupprServeurAD extends EditServeurAD
{
	public $Titre = 'Desactiver connexion AD' ;
	public $TitreDocument = 'Desactiver connexion AD' ;
	public $MessageSuccesExecuter = 'La connexion AD a &eacute;t&eacute; desactiv&eacute; avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = true ;
		$form->Editable = false ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AppliqueScriptParent' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
	public function AppliqueCommande(& $cmd)
	{
		if($cmd->IDInstanceCalc == $this->FormPrinc->CommandeExecuter->IDInstanceCalc)
		{
			$membership = & $this->ZoneParent->Membership ;
			$bd = & $membership->Database ;
			$ok = $bd->RunSql(
				'update '.$bd->EscapeTableName($membership->ADServerTable).' set '.$bd->EscapeVariableName($membership->EnableADServerColumn).' = :disabled where id=:id',
				array('disabled' => 0, 'id' => $this->FiltreId->Lie())
			) ;
			if($ok)
			{
				$cmd->ConfirmeSucces() ;
			}
			else
			{
				$cmd->RenseigneErreur('Exception SQL : '.$bd->ConnectionException) ;
			}
		}
		else
		{
			parent::AppliqueCommande($cmd) ;
		}
	}
}
