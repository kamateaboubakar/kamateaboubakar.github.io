<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class ChangeParams extends Modif
{
	public $TitreDocument = "Modifier les paramètres" ;
	public $Titre = "Modifier les paramètres" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
	protected function DetermineFormPrinc()
	{
		parent::DetermineFormPrinc() ;
		$this->FltTitre->EstEtiquette = true ;
		$this->FltJob->EstEtiquette = true ;
		$this->FltEnv->EstEtiquette = true ;
		$this->FltTitre->NePasLierColonne = true ;
		$this->FltJob->NePasLierColonne = true ;
		$this->FltEnv->NePasLierColonne = true ;
		$this->CacheFltsParamsType(false) ;
		$this->FltStatut->Invisible = true ;
	}
}
