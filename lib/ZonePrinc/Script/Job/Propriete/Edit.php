<?php

namespace Rpa2p\ZonePrinc\Script\Job\Propriete ;

class Edit extends \Rpa2p\ZonePrinc\Script\Script
{
	public $NomDocumentWeb = "modal" ;
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
		if($this->FormPrinc->InclureElementEnCours == true)
		{
			$bd = $this->CreeBdPrinc() ;
			$this->LgnPrinc = $bd->FetchSqlRow("select * from rpapp_propriete_job where id=:0", array(intval(\Pv\Misc::_GET_def("id")))) ;
			if(is_array($this->LgnPrinc) && count($this->LgnPrinc) > 0)
			{
				$this->ParamIdJob = $this->LgnPrinc["id_job"] ;
			}
		}
		else
		{
			$this->ParamIdJob = intval((isset($_GET["id_job"])) ? $_GET["id_job"] : 0) ;
		}
	}
	protected function DetermineFormPrinc()
	{
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->InitFormPrinc() ;
		$this->FormPrinc->ChargeConfig() ;
		$this->DetermineParamsFormPrinc() ;
		$this->FormPrinc->CommandeAnnuler->ContenuJsSurClick = "window.top.fermeModal()" ;
		$this->FormPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->FormPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_propriete_job" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_propriete_job" ;
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
		$this->FltPropriete = $this->FormPrinc->InsereFltEditHttpPost("id_propriete", "id_propriete") ;
		$this->FltPropriete->Libelle = "Propriété" ;
		$comp = $this->FltPropriete->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_propriete" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom" ;
		$this->FltValeur = $this->FormPrinc->InsereFltEditHttpPost("valeur", "valeur") ;
		$this->FltValeur->Libelle = "Valeur" ;
		$comp = $this->FltValeur->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne()) ;
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
			}
		}
	}
	public function ValideCritere(& $critere)
	{
		$cmd = & $critere->CommandeParent ;
		if($this->CritrPrinc->IDInstanceCalc == $critere->IDInstanceCalc)
		{
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
