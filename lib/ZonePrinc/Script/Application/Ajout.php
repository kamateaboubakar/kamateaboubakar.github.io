<?php

namespace Rpa2p\ZonePrinc\Script\Application ;

use \Rpa2p\ZonePrinc\Script ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter une application" ;
	public $Titre = "Ajouter une application" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
