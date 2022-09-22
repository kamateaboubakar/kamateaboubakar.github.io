<?php

namespace Rpa2p\ZonePrinc\Script\Application ;

use \Rpa2p\ZonePrinc\Script ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier l'application" ;
	public $Titre = "Modifier l'application" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
}
