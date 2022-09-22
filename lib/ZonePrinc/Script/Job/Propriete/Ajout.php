<?php

namespace Rpa2p\ZonePrinc\Script\Job\Propriete ;

class Ajout extends Edit
{
	public $TitreDocument = "Ajouter une propriété" ;
	public $Titre = "Ajouter une propriété" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AjoutElement' ;
		$this->FormPrinc->InclureElementEnCours = false ;
	}
}
