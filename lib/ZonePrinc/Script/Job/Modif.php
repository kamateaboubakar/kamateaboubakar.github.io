<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier le job" ;
	public $Titre = "Modifier le job" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
}
