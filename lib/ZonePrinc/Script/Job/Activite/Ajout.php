<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter une activité" ;
	public $Titre = "Ajouter une activité" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
