<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class AjoutMembre extends EditMembre
{
	public $Titre = 'Ajouter membre' ;
	public $TitreDocument = 'Ajouter membre' ;
	public $MessageSuccesExecuter = 'Le membre a &eacute;t&eacute; ajout&eacute; avec succ&egrave;s' ;
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
