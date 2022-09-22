<?php

namespace Rpa2p\ZonePrinc\Script\Job\Planif ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter une planification" ;
	public $Titre = "Ajouter une planification" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
