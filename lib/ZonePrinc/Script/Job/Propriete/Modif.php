<?php

namespace Rpa2p\ZonePrinc\Script\Job\Propriete ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier la propriété" ;
	public $Titre = "Modifier la propriété" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
	protected function DetermineFormPrinc()
	{
		parent::DetermineFormPrinc() ;
	}
}
