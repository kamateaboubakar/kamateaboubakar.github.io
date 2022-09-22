<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Liste extends \Rpa2p\ZonePrinc\Script\Job\ModPart
{
	public $TitreDocument = "Activit&eacute;s du Job" ;
	public $Titre = "Activit&eacute;s du Job" ;
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
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "(select t11.*, t12.titre titre_env
from rpapp_activite t11
left join rpapp_environnement t12 on t11.id_environnement=t12.id)" ;
		$flt = $this->TablPrinc->InsereFltSelectFixe("id_job", $this->ParamId, "id_job = <self>") ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("titre_rech", "instr(titre, <self>) > 0") ;
		$flt->Libelle = "Titre" ;
		if($this->PossedePrivilege("consult_infos_systeme"))
		{
			$this->TablPrinc->InsereDefCol("id", "ID") ;
		}
		else
		{
			$this->TablPrinc->InsereDefColCachee("id") ;
		}
		$this->TablPrinc->InsereDefCol("titre", "Titre") ;
		$this->TablPrinc->InsereDefCol("titre_env", "Environnement") ;
		$this->TablPrinc->InsereDefColDateTimeFr("date_modif", "Date mise &agrave; jour") ;
		$this->TablPrinc->InsereDefColBool("actif", "Actif") ;
		$acts = $this->TablPrinc->InsereDefColActions("Actions") ;
		$lien1 = $this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=demarreActivite&id=${id}&silencieux=1\')', '<i class="fa fa-play" title="Démarrer"></i>') ;
		$lien1->ClasseCSS = "btn btn-primary" ;
		$lien1 = $this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifActivite&id=${id}\')', '<i class="fa fa-edit"></i>Modifier') ;
		$lien1->ClasseCSS = "btn btn-primary" ;
		$lien2 = $this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=changeParamsActivite&id=${id}\')', '<i class="fas fa-cog"></i>Paramètres') ;
		$lien2->ClasseCSS = "btn btn-info" ;
		$lien3 = $this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=statutActivite&id=${id}\')', '<i class="fas fa-toggle-on"></i> Statut') ;
		$lien3->ClasseCSS = "btn btn-danger" ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= parent::RenduSpecifique() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
