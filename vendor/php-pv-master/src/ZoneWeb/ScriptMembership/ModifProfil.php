<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ModifProfil extends EditProfil
{
	public $Titre = 'Modifier profil' ;
	public $TitreDocument = 'Modifier profil' ;
	public $MessageSuccesExecuter = 'Le profil a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = true ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
	protected function ChargeFormPrinc()
	{
		parent::ChargeFormPrinc() ;
	}
}
