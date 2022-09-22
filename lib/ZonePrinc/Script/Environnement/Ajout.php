<?php

namespace Rpa2p\ZonePrinc\Script\Environnement ;

use \Rpa2p\ZonePrinc\Script ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter un environnement" ;
	public $Titre = "Ajouter un environnement" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
