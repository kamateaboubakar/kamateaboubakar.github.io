<?php

namespace Rpa2p\ZonePrinc ;

class ZonePrinc extends \Pv\ZoneBootstrap\ZoneBootstrap
{
	public $PrivilegesPassePartout = array('super_admin') ;
	public $PrivilegesEditMembres = array('gestion_membres') ;
	public $PrivilegesEditMembership = array('gestion_profils') ;
	public $UtiliserDocumentWeb = true ;
	public $NomClasseMembership = '\Rpa2p\Membership' ;
	public $InclureScriptsMembership = true ;
	public $InclureFontAwesome = true ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::ZONE_PRINC ;
	}
	protected function ChargeDocumentsWeb()
	{
		$this->DocumentsWeb["defaut"] = new Document\Defaut() ;
		$this->DocumentsWeb["onglet"] = new Document\Onglet() ;
		$this->DocumentsWeb["modal"] = new Document\Modal() ;
		$this->DocumentsWeb["ecran_supervision"] = new Document\EcranSupervision() ;
	}
	protected function ChargeScriptsMembership()
	{
		parent::ChargeScriptsMembership() ;
		if(! $this->PossedeMembreConnecte())
		{
			$this->ScriptConnexion->ClasseCSSConteneur = 'justify-content-center' ;
		}
		else
		{
			if($this->PossedePrivilege("consulte_clients"))
			{
				$this->InsereScript("nos_clients", new \Rpa2p\ZonePrinc\Script\Clients()) ;
			}
			if($this->PossedePrivilege("gestion_references"))
			{
				$this->InsereScript("listeEnvs", new Script\Environnement\Liste()) ;
				$this->InsereScript("ajoutEnv", new Script\Environnement\Ajout()) ;
				$this->InsereScript("modifEnv", new Script\Environnement\Modif()) ;
				$this->InsereScript("listeApps", new Script\Application\Liste()) ;
				$this->InsereScript("ajoutApp", new Script\Application\Ajout()) ;
				$this->InsereScript("modifApp", new Script\Application\Modif()) ;
				$this->InsereScript("listeProprietes", new Script\Propriete\Liste()) ;
				$this->InsereScript("ajoutPropriete", new Script\Propriete\Ajout()) ;
				$this->InsereScript("modifPropriete", new Script\Propriete\Modif()) ;
				$this->InsereScript("listeVars", new Script\Variable\Liste()) ;
				$this->InsereScript("ajoutVar", new Script\Variable\Ajout()) ;
				$this->InsereScript("modifVar", new Script\Variable\Modif()) ;
			}
			if($this->PossedePrivilege("consult_execs"))
			{
				$this->InsereScript("listeExecsJobs", new Script\ExecJob\Liste()) ;
				$this->InsereScript("rapportExecJob", new Script\ExecJob\Rapport()) ;
				$this->InsereScript("listeExecsActivit", new Script\ExecActivite\Liste()) ;
				$this->InsereScript("listeExecsErrActivit", new Script\ExecActivite\ListeErr()) ;
				$this->InsereScript("listeInfosPlanif", new Script\InfoPlanif\Liste()) ;
				$this->InsereScript("listeJobsNonDemar", new Script\InfoPlanif\NonDemarres()) ;
				$this->InsereScript("listeSupervisJob", new Script\Job\Supervision\Liste()) ;
			}
			if($this->PossedePrivilege("exec_jobs"))
			{
				$this->InsereScript("demarreJob", new Script\Job\Demarre()) ;
			}
			if($this->PossedePrivileges(array("gestion_jobs", "exec_jobs")))
			{
				$this->InsereScript("listeJobs", new Script\Job\Liste()) ;
			}
			if($this->PossedePrivilege("gestion_jobs"))
			{
				$this->InsereScript("listeJobs", new Script\Job\Liste()) ;
				$this->InsereScript("ajoutJob", new Script\Job\Ajout()) ;
				$this->InsereScript("modifJob", new Script\Job\Modif()) ;
				$this->InsereScript("listePlanifsJob", new Script\Job\Planif\Liste()) ;
				$this->InsereScript("ajoutPlanifJob", new Script\Job\Planif\Ajout()) ;
				$this->InsereScript("modifPlanifJob", new Script\Job\Planif\Modif()) ;
				$this->InsereScript("statutJob", new Script\Job\Statut()) ;
				$this->InsereScript("listeActivitesJob", new Script\Job\Activite\Liste()) ;
				$this->InsereScript("ajoutActivite", new Script\Job\Activite\Ajout()) ;
				$this->InsereScript("modifActivite", new Script\Job\Activite\Modif()) ;
				$this->InsereScript("demarreActivite", new Script\Job\Activite\Demarre()) ;
				$this->InsereScript("choixTypeActivite", new Script\Job\Activite\ChoixType()) ;
				$this->InsereScript("changeParamsActivite", new Script\Job\Activite\ChangeParams()) ;
				$this->InsereScript("statutActivite", new Script\Job\Activite\Statut()) ;
				$this->InsereScript("listeProprietesJob", new Script\Job\Propriete\Liste()) ;
				$this->InsereScript("ajoutProprieteJob", new Script\Job\Propriete\Ajout()) ;
				$this->InsereScript("modifProprieteJob", new Script\Job\Propriete\Modif()) ;
			}
		}
		$this->InsereScriptParDefaut(new Script\Accueil()) ;
		$this->InsereScript('aPropos', new Script\APropos()) ;
	}
}
