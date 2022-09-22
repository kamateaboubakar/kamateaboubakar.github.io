<?php

namespace Rpa2p\ZonePrinc\Script\Job\Planif ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier la planification" ;
	public $Titre = "Modifier la planification" ;
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
