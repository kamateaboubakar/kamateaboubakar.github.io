<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class ModCadre extends Script\Script
{
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormInfo() ;
	}
	protected function DetermineFormInfo()
	{
		$this->FormInfo = $this->InsereFormPrinc() ;
		$this->FormInfo->Editable = false ;
		$this->FormInfo->InclureElementEnCours = true ;
		$this->FormInfo->InscrireCommandeExecuter = false ;
		$this->FormInfo->InscrireCommandeAnnuler = false ;
		$this->FormInfo->ChargeConfig() ;
		$this->FormInfo->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormInfo->FournisseurDonnees->RequeteSelection = "rpapp_job" ;
		$this->FormInfo->FournisseurDonnees->TableEdition = "rpapp_job" ;
		$this->FormInfo->InsereFltSelectHttpGet("id", "id=<self>") ;
		$this->FltJob = $this->FormInfo->InsereFltEditHttpPost("id_job", "id") ;
		$this->FltJob->Libelle = "Job" ;
		$comp = $this->FltJob->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCorresp) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "(select t1.id, concat(t2.titre, ' / ', t1.nom) nom_job from rpapp_job t1
inner join rpapp_application t2 on t1.id_application = t2.id)" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom_job" ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->FormInfo->RenduDispositif() ;
		$ctn .= $this->RenduLiensPrinc() ;
		return $ctn ;
	}
}
