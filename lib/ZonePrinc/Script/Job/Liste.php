<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $TitreDocument = "Liste des jobs" ;
	public $Titre = "Liste des jobs" ;
	public $EstScriptSession = true ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
		$this->CtnJsFermModalPrinc = 'ActualiseFormulaire'.$this->TablPrinc->IDInstanceCalc.'()' ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "(select t1.*, t2.titre titre_application
from rpapp_job t1
left join rpapp_application t2 on t1.id_application=t2.id)" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("application", "id_application = <self>") ;
		$flt->Libelle = "Application" ;
		$this->TablPrinc->AccepterTriColonneInvisible = true ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->InclureElementHorsLigne = true ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("nom_rech", "instr(nom, <self>) > 0") ;
		$flt->Libelle = "Titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("actif", "actif=<self>") ;
		$flt->Libelle = "Actif" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool) ;
		$comp->InclureElementHorsLigne = true ;
		$this->TablPrinc->SensColonneTri = "desc" ;
		if($this->PossedePrivilege("consult_infos_systeme"))
		{
			$this->TablPrinc->InsereDefCol("id", "ID") ;
		}
		else
		{
			$this->TablPrinc->InsereDefColCachee("id") ;
		}
		$this->TablPrinc->InsereDefCol("reference_job", "Reference") ;
		$this->TablPrinc->InsereDefCol("titre_application", "Application") ;
		$this->TablPrinc->InsereDefCol("nom", "Nom") ;
		$this->TablPrinc->InsereDefColBool("actif", "Actif") ;
		$liensAct = '' ;
		$liensAct .= '<div class="d-flex flex-row">' ;
		if($this->PossedePrivilege("gestion_jobs"))
		{
			$liensAct .= '<div class="dropdown mx-1">
  <button class="btn btn-success dropdown-toggle" type="button" id="ddCree${id}" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-plus"></i> Cr&eacute;er
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddCree${id}">
    <li><a class="dropdown-item" href="?appelleScript=choixTypeActivite&id=${id}">Activit&eacute;</a></li>
    <li><a class="dropdown-item" href="?appelleScript=ajoutPlanifJob&id_job=${id}">Planification</a></li>
    <li><a class="dropdown-item" href="?appelleScript=ajoutProprieteJob&id_job=${id}">Propriété</a></li>
  </ul>
</div>' ;
		}
		if($this->PossedePrivilege("exec_jobs"))
		{
			$liensAct .= '<div class="dropdown mx-1">
  <button class="btn btn-primary dropdown-toggle" type="button" id="ddCree${id}" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-play"></i> Demarrer
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddDemarre${id}">
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=demarreJob&id=${id}&silencieux=1\')">Tester</a></li>
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=demarreJob&id=${id}\')">Demarrer</a></li>
  </ul>
</div>' ;
		}
		if($this->PossedePrivilege("gestion_jobs"))
		{
			$liensAct .= ' <a class="btn btn-info mx-1" href="?appelleScript=listeActivitesJob&id=${id}"><i class="fas fa-list"></i> Activit&eacute;s</a>' ;
			$liensAct .= ' <a class="btn btn-info mx-1" href="?appelleScript=listePlanifsJob&id=${id}"><i class="fa fa-clock"></i> Planifications</a>' ;
			$liensAct .= ' <a class="btn btn-info mx-1" href="?appelleScript=listeProprietesJob&id=${id}"><i class="fa fa-table" title="Propriétés"></i></a>' ;
			$liensAct .= '<div class="dropdown mx-1">
  <button class="btn btn-warning dropdown-toggle" type="button" id="ddModif${id}" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-edit"></i> Modifier
  </button>
  <ul class="dropdown-menu" aria-labelledby="ddModif${id}">
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=modifJob&id=${id}\')">Modifier</a></li>
    <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=statutJob&id=${id}\')">Statut</a></li>
  </ul>
</div>' ;
		}
		$liensAct .= '</div>' ;
		$this->TablPrinc->InsereDefColHtml($liensAct, 'Actions') ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		if($this->TablPrinc->IDInstanceCalc == $composant->IDInstanceCalc)
		{
			return '' ;
		}
		return '' ;
	}
	public function RenduLiensPrinc()
	{
		$ctn = '' ;
		if($this->PossedePrivilege("gestion_jobs"))
		{
			$ctn .= '<div class="card">
<div class="card-header">
<a class="btn btn-primary" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutJob\') ;">Ajouter</a>
</div>
</div>
<br>' ;
		}
		return $ctn ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->RenduLiensPrinc() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
