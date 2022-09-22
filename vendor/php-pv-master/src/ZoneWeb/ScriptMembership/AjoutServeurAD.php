<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class AjoutServeurAD extends EditServeurAD
{
	public $Titre = 'Ajouter connexion AD' ;
	public $TitreDocument = 'Ajouter connexion AD' ;
	public $MessageSuccesExecuter = 'La connexion AD a &eacute;t&eacute; ajout&eacute;e avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$form->InclureElementEnCours = false ;
		$form->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$form->MsgExecSuccesCommandeExecuter = $this->MessageSuccesExecuter ;
	}
}
