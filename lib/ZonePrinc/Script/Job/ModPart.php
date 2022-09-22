<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class ModPart extends Script\Script
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
		// $this->FormInfo->NomClasseCommandeAnnuler = '\Pv\ZoneWeb\Commande\RedirectScriptSession' ;
		$this->FormInfo->ChargeConfig() ;
		$this->FormInfo->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormInfo->FournisseurDonnees->RequeteSelection = "rpapp_job" ;
		$this->FormInfo->FournisseurDonnees->TableEdition = "rpapp_job" ;
		$this->FormInfo->InsereFltSelectHttpGet("id", "id=<self>") ;
		$flt = $this->FormInfo->InsereFltEditHttpPost("reference_job", "reference_job") ;
		$flt->Libelle = "Reference" ;
		$flt = $this->FormInfo->InsereFltEditHttpPost("nom", "nom") ;
		$flt->Libelle = "Nom" ;
		$flt = $this->FormInfo->InsereFltEditHttpPost("id_application", "id_application") ;
		$flt->Libelle = "Application" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
	}
	public function RenduLiensPrinc()
	{
		$ctn = '' ;
		$this->ParamId = intval((isset($_GET["id"])) ? $_GET["id"] : "") ;
		$ctn .= '<div class="card">
<div class="card-header text-center">' ;
		$ctn .= '<div class="d-flex flex-row">
<a href="'.$this->ZoneParent->UrlRedirScriptSession().'" class="btn btn-danger mx-1"><i class="fas fa-undo"></i> Retour</a>' ;
		if($this->PossedePrivilege("gestion_jobs"))
		{
			$ctn .= '<div class="dropdown mx-1">
  <button class="btn btn-success dropdown-toggle" type="button" id="ddCree'.$this->ParamId.'" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-plus"></i> Cr&eacute;er
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddCree'.$this->ParamId.'">
    <li><a class="dropdown-item" href="?appelleScript=choixTypeActivite&id='.$this->ParamId.'">Activit&eacute;</a></li>
    <li><a class="dropdown-item" href="?appelleScript=ajoutPlanifJob&id_job='.$this->ParamId.'">Planification</a></li>
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutProprieteJob&id_job='.$this->ParamId.'\')">Propriété</a></li>
  </ul>
</div>' ;
		}
		if($this->PossedePrivilege("exec_jobs"))
		{
			$ctn .= '<div class="dropdown mx-1">
  <button class="btn btn-primary dropdown-toggle" type="button" id="ddCree'.$this->ParamId.'" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-play"></i> Demarrer
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddDemarre'.$this->ParamId.'">
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=demarreJob&id='.$this->ParamId.'&silencieux=1\')">Tester</a></li>
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=demarreJob&id='.$this->ParamId.'\')">Demarrer</a></li>
  </ul>
</div>' ;
		}
		if($this->PossedePrivilege("gestion_jobs"))
		{
			$ctn .= ' <a class="btn btn-info mx-1" href="?appelleScript=listeActivitesJob&id='.$this->ParamId.'"><i class="fas fa-list"></i> Activit&eacute;s</a>' ;
			$ctn .= ' <a class="btn btn-info mx-1" href="?appelleScript=listePlanifsJob&id='.$this->ParamId.'"><i class="fa fa-clock"></i> Planifications</a>' ;
			$ctn .= ' <a class="btn btn-info mx-1" href="?appelleScript=listeProprietesJob&id='.$this->ParamId.'"><i class="fa fa-table"></i> Propriétés</a>' ;
			$ctn .= '<div class="dropdown mx-1">
  <button class="btn btn-warning dropdown-toggle" type="button" id="ddModif'.$this->ParamId.'" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-edit"></i> Modifier
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddModif'.$this->ParamId.'">
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=modifJob&id='.$this->ParamId.'\')">Modifier</a></li>
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=statutJob&id='.$this->ParamId.'\')">Statut</a></li>
  </ul>
</div>' ;
		}
		$ctn .= '</div>' ;
		$ctn .= '</div>
</div>' ;
		return $ctn ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->FormInfo->RenduDispositif() ;
		$ctn .= $this->RenduLiensPrinc() ;
		return $ctn ;
	}
}
