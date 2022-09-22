<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class Statut extends ModCadre
{
	public $NomDocumentWeb = "modal" ;
	public $TitreDocument = "Statut du job" ;
	public $Titre = "Statut du job" ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
		$this->FormInfo->CacherBlocCommandes = true ;
	}
	protected function DetermineFormPrinc()
	{
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->FormPrinc->Editable = true ;
		$this->FormPrinc->InclureElementEnCours = true ;
		$this->FormPrinc->LibelleCommandeExecuter = "Appliquer" ;
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\ModifElement' ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FormPrinc->CommandeAnnuler->ContenuJsSurClick = "window.top.fermeModal()" ;
		$this->FormPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_job" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_job" ;
		$this->FormPrinc->InsereFltSelectHttpGet("id", "id=<self>") ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("actif", "actif") ;
		$flt->Libelle = "Actif" ;
		$flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool()) ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= parent::RenduSpecifique() ;
		$ctn .= '<hr>' ;
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
