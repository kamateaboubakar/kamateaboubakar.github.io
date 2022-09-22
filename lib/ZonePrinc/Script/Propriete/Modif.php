<?php

namespace Rpa2p\ZonePrinc\Script\Propriete ;

use \Rpa2p\ZonePrinc\Script ;

class Modif extends Edit
{
	public $TitreDocument = "Modifier la propriété" ;
	public $Titre = "Modifier la propriété" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
}
