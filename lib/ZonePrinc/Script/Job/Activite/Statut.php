<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Statut extends Modif
{
	public $TitreDocument = "Statut de l'activité" ;
	public $Titre = "Statut de l'activité" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->InclureElementEnCours = true ;
	}
	protected function DetermineFormPrinc()
	{
		parent::DetermineFormPrinc() ;
		$this->FormPrinc->CommandeAnnuler->ContenuJsSurClick = "window.top.fermeModal()" ;
		$this->FltTitre->EstEtiquette = true ;
		$this->FltJob->EstEtiquette = true ;
		$this->FltEnv->EstEtiquette = true ;
		$this->FltTitre->NePasLierColonne = true ;
		$this->FltJob->NePasLierColonne = true ;
		$this->FltEnv->NePasLierColonne = true ;
		$this->FltStatut->Invisible = false ;
	}
}
