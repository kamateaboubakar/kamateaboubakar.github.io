<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class AjoutProfil extends EditProfil
{
	public $Titre = 'Ajouter profil' ;
	public $TitreDocument = 'Ajouter profil' ;
	public $MessageSuccesExecuter = 'Le profil a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s' ;
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
