<?php

namespace Rpa2p\ZonePrinc\Script\Variable ;

use \Rpa2p\ZonePrinc\Script ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter une variable" ;
	public $Titre = "Ajouter une variable" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
