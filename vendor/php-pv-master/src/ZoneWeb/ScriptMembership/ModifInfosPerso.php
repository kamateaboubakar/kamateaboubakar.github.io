<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ModifInfosPerso extends EditMembre
{
	public $CibleModification = 2 ;
	public $Titre = 'Informations personnelles' ;
	public $TitreDocument = 'Informations personnelles' ;
	public $MessageSuccesExecuter = 'Vos informations personnelles ont &eacute;t&eacute; modifi&eacute;es avec succ&egrave;s' ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
	}
}
