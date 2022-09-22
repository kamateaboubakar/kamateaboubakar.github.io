<?php

namespace Rpa2p\ZonePrinc\Script\Job\Planif ;

class Edit extends \Rpa2p\ZonePrinc\Script\Script
{
	public $TypePeriode ;
	public $TypeNotif ;
	public $ParamIdJob ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function InitFormPrinc()
	{
	}
	protected function DetermineParamsFormPrinc()
	{
		$nomsTypesPeriode = array_keys($this->ApplicationParent->TypesPeriodeJob) ;
		$this->TypePeriode = $this->ApplicationParent->TypesPeriodeJob[$nomsTypesPeriode[0]] ;
		$nomsTypesNotif = array_keys($this->ApplicationParent->TypesNotifJob) ;
		$this->TypeNotif = $this->ApplicationParent->TypesNotifJob[$nomsTypesNotif[0]] ;
		$nomTypePeriode = '' ;
		$nomTypeNotif = '' ;
		if($this->FormPrinc->InclureElementEnCours == true)
		{
			$bd = $this->CreeBdPrinc() ;
			$this->LgnPrinc = $bd->FetchSqlRow("select * from rpapp_planif_job where id=:0", array(intval(\Pv\Misc::_GET_def("id")))) ;
			if(is_array($this->LgnPrinc) && count($this->LgnPrinc) > 0)
			{
				$this->ParamIdJob = $this->LgnPrinc["id_job"] ;
				$nomTypePeriode = $this->LgnPrinc["type_periode"] ;
				$nomTypeNotif = $this->LgnPrinc["type_notif"] ;
			}
		}
		else
		{
			$this->ParamIdJob = intval((isset($_GET["id_job"])) ? $_GET["id_job"] : 0) ;
		}
		if(isset($_POST["type_periode"]))
		{
			$nomTypePeriode = $_POST["type_periode"] ;
		}
		if(isset($_POST["type_notif"]))
		{
			$nomTypeNotif = $_POST["type_notif"] ;
		}
		if($nomTypePeriode != '' && isset($this->ApplicationParent->TypesPeriodeJob[$nomTypePeriode]))
		{
			$this->TypePeriode = $this->ApplicationParent->TypesPeriodeJob[$nomTypePeriode] ;
		}
		if($nomTypeNotif != '' && isset($this->ApplicationParent->TypesNotifJob[$nomTypeNotif]))
		{
			$this->TypeNotif = $this->ApplicationParent->TypesNotifJob[$nomTypeNotif] ;
		}
	}
	protected function DetermineFormPrinc()
	{
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->InitFormPrinc() ;
		$this->FormPrinc->ChargeConfig() ;
		$this->DetermineParamsFormPrinc() ;
		$this->FormPrinc->RedirigeAnnulerVersUrl("?appelleScript=listePlanifsJob&id=".$this->ParamIdJob) ;
		$this->FormPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->FormPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_planif_job" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_planif_job" ;
		$this->FltId = $this->FormPrinc->InsereFltSelectHttpGet("id", "id = <self>") ;
		$this->FltJob = $this->FormPrinc->InsereFltEditHttpPost("id_job", "id_job") ;
		$this->FltJob->Libelle = "Job" ;
		$this->FltJob->ValeurParDefaut = $this->ParamIdJob ;
		$comp = $this->FltJob->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCorresp) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "(select t1.id, concat(t2.titre, ' / ', t1.nom) nom_job from rpapp_job t1
inner join rpapp_application t2 on t1.id_application = t2.id)" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom_job" ;
		$this->FltTypePeriode = $this->FormPrinc->InsereFltEditHttpPost("type_periode", "type_periode") ;
		$this->FltTypePeriode->Libelle = "Periode" ;
		$this->FltTypePeriode->ValeurParDefaut = $this->TypePeriode->Id() ;
		$comp = $this->FltTypePeriode->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->AttrsSupplHtml["onchange"] = "ActualiseFormulaire".$this->FormPrinc->IDInstanceCalc."()" ;
		$comp->FournisseurDonnees = $this->ApplicationParent->CreeFournTypesPeriodeJob() ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$this->TypePeriode->RemplitFormEdit($this->FormPrinc) ;
		$this->FltTypeNotif = $this->FormPrinc->InsereFltEditHttpPost("type_notif", "type_notif") ;
		$this->FltTypeNotif->Libelle = "Notification" ;
		$this->FltTypeNotif->ValeurParDefaut = $this->TypeNotif->Id() ;
		$comp = $this->FltTypeNotif->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->AttrsSupplHtml["onchange"] = "ActualiseFormulaire".$this->FormPrinc->IDInstanceCalc."()" ;
		$comp->FournisseurDonnees = $this->ApplicationParent->CreeFournTypesNotifJob() ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$this->TypeNotif->RemplitFormEdit($this->FormPrinc) ;
		$this->FltStatut = $this->FormPrinc->InsereFltEditHttpPost("actif", "actif") ;
		$this->FltStatut->Libelle = "Actif" ;
		$comp = $this->FltStatut->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool) ;
		$this->FltStatut->Invisible = (! $this->FormPrinc->InclureElementEnCours) ;
		$this->FltStatut->ValeurParDefaut = 1 ;
		$this->CritrPrinc = $this->FormPrinc->CommandeExecuter->InsereCritereScriptParent() ;
		$this->ActCmdPrinc = $this->FormPrinc->CommandeExecuter->InsereActCmdScriptParent() ;
	}
	public function AppliqueActCmd(& $actCmd)
	{
		$cmd = & $actCmd->CommandeParent ;
		if($this->ActCmdPrinc->IDInstanceCalc == $actCmd->IDInstanceCalc)
		{
			if($actCmd->CommandeParent->EstSucces())
			{
				$this->TypePeriode->AppliqueActCmdEdit($actCmd) ;
				$this->TypeNotif->AppliqueActCmdEdit($actCmd) ;
			}
		}
	}
	public function ValideCritere(& $critere)
	{
		$cmd = & $critere->CommandeParent ;
		if($this->CritrPrinc->IDInstanceCalc == $critere->IDInstanceCalc)
		{
			$this->TypePeriode->ValideCritrEdit($critere) ;
			if($critere->MessageErreur != "")
			{
				return false ;
			}
			$this->TypeNotif->ValideCritrEdit($critere) ;
			if($critere->MessageErreur != "")
			{
				return false ;
			}
			return ($critere->MessageErreur == "") ;
		}
		return true ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		if($this->FormPrinc->IDInstanceCalc == $composant->IDInstanceCalc)
		{
			return '' ;
		}
		return '' ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
