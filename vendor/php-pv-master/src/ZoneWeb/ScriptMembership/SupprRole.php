<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class SupprRole extends EditRole
{
	public $Titre = 'Desactiver role' ;
	public $TitreDocument = 'Desactiver role' ;
	public $MessageSuccesExecuter = 'Le role a &eacute;t&eacute; desactiv&eacute; avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = true ;
		$form->Editable = false ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AppliqueScriptParent' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
	protected function ChargeFormPrinc()
	{
		parent::ChargeFormPrinc() ;
	}
	public function AppliqueCommande(& $cmd)
	{
		if($cmd->IDInstanceCalc == $this->FormPrinc->CommandeExecuter->IDInstanceCalc)
		{
			$membership = & $this->ZoneParent->Membership ;
			$bd = & $membership->Database ;
			$ok = $bd->RunSql(
				'update '.$bd->EscapeTableName($membership->RoleTable).' set '.$bd->EscapeVariableName($membership->EnableMemberColumn).' = :disabled where id=:id',
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
