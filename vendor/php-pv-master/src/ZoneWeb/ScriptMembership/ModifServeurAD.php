<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ModifServeurAD extends EditServeurAD
{
	public $Titre = 'Modifier connexion AD' ;
	public $TitreDocument = 'Modifier connexion AD' ;
	public $MessageSuccesExecuter = 'La connexion AD a &eacute;t&eacute; modifi&eacute;e avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = true ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
}
