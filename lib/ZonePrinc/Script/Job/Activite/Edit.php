<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Edit extends \Rpa2p\ZonePrinc\Script\Script
{
	public $NomDocumentWeb = "modal" ;
	public $TypeActivite ;
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
		$nomsTypesActivit = array_keys($this->ApplicationParent->TypesActiviteJob) ;
		$this->TypeActivite = $this->ApplicationParent->TypesActiviteJob[$nomsTypesActivit[0]] ;
		if($this->FormPrinc->InclureElementEnCours == true)
		{
			$bd = $this->CreeBdPrinc() ;
			$this->LgnPrinc = $bd->FetchSqlRow("select * from rpapp_activite where id=:0", array(intval(\Pv\Misc::_GET_def("id")))) ;
			if(is_array($this->LgnPrinc) && isset($this->LgnPrinc["type_activite"]) && isset($this->ApplicationParent->TypesActiviteJob[$this->LgnPrinc["type_activite"]]))
			{
				$this->TypeActivite = $this->ApplicationParent->TypesActiviteJob[$this->LgnPrinc["type_activite"]] ;
			}
		}
		else
		{
			$typeActiviteSelect = (isset($_GET["type_activite"])) ? $_GET["type_activite"] : "" ;
			if($typeActiviteSelect != "" && isset($this->ApplicationParent->TypesActiviteJob[$typeActiviteSelect]))
			{
				$this->TypeActivite = $this->ApplicationParent->TypesActiviteJob[$typeActiviteSelect] ;
			}
		}
		if($this->FormPrinc->Editable && isset($_POST["type_activite"]) && $_POST["type_activite"] != '') {
			if(isset($this->ApplicationParent->TypesActiviteJob[$_POST["type_activite"]])) {
				$this->TypeActivite = $this->ApplicationParent->TypesActiviteJob[$_POST["type_activite"]] ;
			}
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
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_activite" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_activite" ;
		$this->FltId = $this->FormPrinc->InsereFltSelectHttpGet("id", "id = <self>") ;
		$this->FltJob = $this->FormPrinc->InsereFltEditHttpPost("id_job", "id_job") ;
		$this->FltJob->Libelle = "Job" ;
		$comp = $this->FltJob->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCorresp) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "(select t1.id, concat(t2.titre, ' / ', t1.nom) nom_job from rpapp_job t1
inner join rpapp_application t2 on t1.id_application = t2.id)" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom_job" ;
		if($this->FormPrinc->InclureElementEnCours == false)
		{
			$this->ParamIdJob = intval((isset($_GET["id_job"])) ? $_GET["id_job"] : 0) ;
			$this->FltJob->ValeurParDefaut = $this->ParamIdJob ;
		}
		$this->FltTitre = $this->FormPrinc->InsereFltEditHttpPost("titre", "titre") ;
		$this->FltTitre->Libelle = "Titre" ;
		$this->FltTypeActivit = $this->FormPrinc->InsereFltEditHttpPost("type_activite", "type_activite") ;
		$this->FltTypeActivit->Libelle = "Type" ;
		$this->FltTypeActivit->ValeurParDefaut = $this->TypeActivite->Id() ;
		$this->FltTypeActivit->EstEtiquette = true ;
		$comp = $this->FltTypeActivit->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->FournisseurDonnees = $this->ApplicationParent->CreeFournTypesActiviteJob() ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$this->TypeActivite->RemplitFormEdit($this->FormPrinc) ;
		$this->FltEnv = $this->FormPrinc->InsereFltEditHttpPost("id_environnement", "id_environnement") ;
		$this->FltEnv->Libelle = "Environnement" ;
		$comp = $this->FltEnv->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_environnement" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		if($this->FormPrinc->InclureElementEnCours == false)
		{
			$this->FormPrinc->InsereFltEditFixe("id_membre_creation", $this->IdMembreConnecte(), "id_membre_creation") ;
			$this->FormPrinc->InsereFltEditFixe("date_creation", date("Y-m-d H:i:s"), "date_creation") ;
		}
		$this->FltStatut = $this->FormPrinc->InsereFltEditHttpPost("actif", "actif") ;
		$this->FltStatut->Libelle = "Actif" ;
		$comp = $this->FltStatut->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool) ;
		$this->FltStatut->Invisible = true ;
		$this->FltStatut->ValeurParDefaut = 1 ;
		$this->FormPrinc->InsereFltEditFixe("id_membre_modif", $this->IdMembreConnecte(), "id_membre_modif") ;
		$this->FormPrinc->InsereFltEditFixe("date_modif", date("Y-m-d H:i:s"), "date_modif") ;
		$this->CritrPrinc = $this->FormPrinc->CommandeExecuter->InsereCritereScriptParent() ;
		$this->ActCmdPrinc = $this->FormPrinc->CommandeExecuter->InsereActCmdScriptParent() ;
	}
	protected function CacheFltsParamsType($oui=true)
	{
		if(count($this->TypeActivite->NomsFiltreEdit) > 0)
		{
			foreach($this->FormPrinc->FiltresEdition as $i => $flt)
			{
				if(! in_array($flt->NomParametreDonnees, array("id_job", "id_environnement", "type_activite", "id_application", "statut", "titre")))
				{
					$this->FormPrinc->FiltresEdition[$i]->Invisible = $oui ;
				}
			}
		}
	}
	public function AppliqueActCmd(& $actCmd)
	{
		$cmd = & $actCmd->CommandeParent ;
		if($this->ActCmdPrinc->IDInstanceCalc == $actCmd->IDInstanceCalc)
		{
			if($actCmd->CommandeParent->EstSucces())
			{
				$this->TypeActivite->AppliqueActCmdEdit($actCmd) ;
			}
		}
	}
	public function ValideCritere(& $critere)
	{
		$cmd = & $critere->CommandeParent ;
		if($this->CritrPrinc->IDInstanceCalc == $critere->IDInstanceCalc)
		{
			$this->TypeActivite->ValideCritrEdit($critere) ;
			if($critere->MessageErreur != "")
			{
				return false ;
			}
			$bd = $this->CreeBdPrinc() ;
			$lgnSimil = $bd->FetchSqlRow(
				'select id from rpapp_activite where id<>:0 and (id_job=:1 and id_environnement=:3 and upper(titre)=upper(:2))',
				array(
					(($this->FormPrinc->InclureElementEnCours) ? $this->FltId->Lie() : 0),
					$this->FltJob->Lie(),
					$this->FltTitre->Lie(),
					$this->FltEnv->Lie()
				)
			) ;
			if(! is_array($lgnSimil))
			{
				$critere->MessageErreur = "Exception SQL Activité Similaire : ".$bd->ConnectionException ;
			}
			elseif(count($lgnSimil) > 0)
			{
				$critere->MessageErreur = "Une activité similaire existe deja. Veuillez changer le titre ou l'environnement" ;
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
