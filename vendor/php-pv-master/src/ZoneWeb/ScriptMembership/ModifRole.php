<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ModifRole extends EditRole
{
	public $Titre = 'Modifier role' ;
	public $TitreDocument = 'Modifier role' ;
	public $MessageSuccesExecuter = 'Le role a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = true ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
}
