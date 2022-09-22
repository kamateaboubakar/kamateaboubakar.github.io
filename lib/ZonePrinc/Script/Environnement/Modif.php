<?php

namespace Rpa2p\ZonePrinc\Script\Environnement ;

use \Rpa2p\ZonePrinc\Script ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier l'environnement" ;
	public $Titre = "Modifier l'environnement" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
}
