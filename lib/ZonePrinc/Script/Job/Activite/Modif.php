<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier l'activité" ;
	public $Titre = "Modifier l'activité" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
	protected function DetermineFormPrinc()
	{
		parent::DetermineFormPrinc() ;
		$this->CacheFltsParamsType(true) ;
	}
}
