<?php

namespace Rpa2p\ZonePrinc\Script\Variable ;

use \Rpa2p\ZonePrinc\Script ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier la variable" ;
	public $Titre = "Modifier la variable" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
}
