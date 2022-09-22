<?php

namespace Rpa2p\ZonePrinc\Script\Job\Planif ;

class Liste extends \Rpa2p\ZonePrinc\Script\Job\ModPart
{
	public $TitreDocument = "Planifications du Job" ;
	public $Titre = "Planifications du Job" ;
	public function DetermineEnvironnement()
	{
		$this->ParamId = intval((isset($_GET["id"])) ? $_GET["id"] : 0) ;
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->CtnJsFermModalPrinc = 'ActualiseFormulaire'.$this->TablPrinc->IDInstanceCalc.'()' ;
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->AppliqueScriptParentValsSuppl() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "rpapp_planif_job" ;
		$flt = $this->TablPrinc->InsereFltSelectFixe("id_job", $this->ParamId, "id_job = <self>") ;
		if($this->PossedePrivilege("consult_infos_systeme"))
		{
			$this->TablPrinc->InsereDefCol("id", "ID") ;
		}
		else
		{
			$this->TablPrinc->InsereDefColCachee("id") ;
		}
		$this->TablPrinc->InsereDefColCachee("type_periode") ;
		$this->TablPrinc->InsereDefColCachee("param1_periode") ;
		$this->TablPrinc->InsereDefColCachee("param2_periode") ;
		$this->TablPrinc->InsereDefColCachee("param3_periode") ;
		$this->TablPrinc->InsereDefColCachee("param4_periode") ;
		$this->TablPrinc->InsereDefColCachee("type_notif") ;
		$this->TablPrinc->InsereDefColCachee("param1_notif") ;
		$this->TablPrinc->InsereDefColCachee("param2_notif") ;
		$this->TablPrinc->InsereDefColCachee("param3_notif") ;
		$this->TablPrinc->InsereDefColCachee("param4_notif") ;
		$defCol = $this->TablPrinc->InsereDefColHtml('${titre_periode}', "Periode") ;
		$defCol->Largeur = '30%' ;
		$defCol = $this->TablPrinc->InsereDefColHtml('${titre_notif}', "Notification") ;
		$defCol->Largeur = '30%' ;
		$this->TablPrinc->InsereDefColBool("actif", "Actif") ;
		$acts = $this->TablPrinc->InsereDefColActions("Actions") ;
		$lien1 = $this->TablPrinc->InsereLienAction($acts, '?appelleScript=modifPlanifJob&id=${id}', '<i class="fa fa-edit"></i> Modifier') ;
		$lien1->ClasseCSS = "btn btn-primary" ;
		$this->TablPrinc->ParamsGetSoumetFormulaire[] = "id" ;
	}
	public function ExtraitSrcValsSuppl($lgn, & $composant, & $srcValsSuppl)
	{
		$typePeriode = $this->ApplicationParent->CreeTypePeriodeJob($lgn["type_periode"]) ;
		$typeNotif = $this->ApplicationParent->CreeTypeNotifJob($lgn["type_notif"]) ;
		$lgn["titre_periode"] = $typePeriode->TitreParam($lgn) ;
		$lgn["titre_notif"] = $typeNotif->TitreParam($lgn) ;
		return $lgn ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= parent::RenduSpecifique() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
