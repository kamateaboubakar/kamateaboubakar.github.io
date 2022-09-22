<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ModifMembre extends EditMembre
{
	public $Titre = 'Modifier membre' ;
	public $TitreDocument = 'Modifier membre' ;
	public $MessageSuccesExecuter = 'Le membre a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s' ;
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
