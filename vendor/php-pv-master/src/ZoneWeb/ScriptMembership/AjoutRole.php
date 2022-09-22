<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class AjoutRole extends EditRole
{
	public $Titre = 'Ajouter role' ;
	public $TitreDocument = 'Ajouter role' ;
	public $MessageSuccesExecuter = 'Le role a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = 0 ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
	protected function ChargeFormPrinc()
	{
		parent::ChargeFormPrinc() ;
	}
}
