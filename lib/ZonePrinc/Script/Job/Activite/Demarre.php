<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class Demarre extends \Rpa2p\ZonePrinc\Script\Script
{
	public $TitreDocument = "Demarrer l'activité" ;
	public $Titre = "Demarrer l'activité" ;
	protected $StatutAction = "" ;
	public $NomDocumentWeb = "modal" ;
	protected $MessageAction = "" ;
	public function ConfirmeSucces($msg='')
	{
		$this->StatutAction = 0 ;
		$this->MessageAction = ($msg) ? $msg : "Action deroul&eacute;e avec succ&egrave;s" ;
	}
	public function RenseigneErreur($msg)
	{
		$this->StatutAction = 1 ;
		$this->MessageAction = $msg ;
	}
	public function DetermineEnvironnement()
	{
		$this->ParamSilencieux = intval(\Pv\Misc::_GET_def("silencieux")) ;
		$bd = $this->CreeBdPrinc() ;
		$this->ParamIdActivit = intval(\Pv\Misc::_GET_def("id")) ;
		$this->LgnPrinc = $bd->FetchSqlRow('select t0.titre titre_activite, t1.*, t2.id_job, t3.titre titre_application
from rpapp_activite t0
inner join rpapp_job t1 on t0.id_job=t1.id
left join rpapp_exec_queue_0 t2 on t1.id=t2.id_job
left join rpapp_application t3 on t1.id_application=t3.id
where t0.id=:0 and t2.id_planif is null', array($this->ParamIdActivit)) ;
		if(! is_array($this->LgnPrinc))
		{
			$this->RenseigneErreur("Exception SQL Job : ".$bd->ConnectionException) ;
			return ;
		}
		if(count($this->LgnPrinc) == 0)
		{
			$this->RenseigneErreur("Job inexistant ou déjà planifié, veuillez vérifier") ;
			return ;
		}
		else
		{
			$this->TitreDocument = "Demarrage ".$this->LgnPrinc["titre_application"]." / ".$this->LgnPrinc["titre_activite"] ;
			$this->Titre = "Demarrage ".$this->LgnPrinc["titre_application"]." / ".$this->LgnPrinc["titre_activite"] ;
		}
		if($this->LgnPrinc["id_job"] > 0)
		{
			$this->RenseigneErreur("Job déjà planifié, veuillez attendre la fin de son exécution.") ;
			return ;
		}
		if($this->ParamSilencieux == 0)
		{
			$ok = $bd->RunSql('insert into rpapp_exec_queue_0
select t1.*, 0 id_planif, 0 id_planif_job, t2.nom titre_job, t3.type_periode, t3.param1_periode, t3.param2_periode, t3.param3_periode, t3.param4_periode, t3.type_notif, t3.param1_notif, t3.param2_notif, t3.param3_notif, t3.param4_notif, t4.titre titre_application
from rpapp_activite t1
inner join rpapp_job t2 on t1.id_job=t2.id
inner join rpapp_planif_job t3 on t1.id_job=t3.id_job
left join rpapp_application t4 on t2.id_application=t4.id
where t1.id='.$this->ParamIdActivit.' and t1.actif=1 and t2.actif=1 and t3.actif=1') ;
		}
		else
		{
			$ok = $bd->RunSql("insert into rpapp_exec_queue_0
select t1.*, 0 id_planif, 0 id_planif_job, t2.nom titre_job, 'toujours', '', '', '', '', 'silencieux', '', '', '', '', t4.titre titre_application
from rpapp_activite t1
inner join rpapp_job t2 on t1.id_job=t2.id
left join rpapp_application t4 on t2.id_application=t4.id
where t1.id=".$this->ParamIdActivit." and t1.actif=1 and t2.actif=1") ;
		}
		if(! $ok)
		{
			$this->RenseigneErreur("Exception SQL Maj Planif : ".$bd->ConnectionException) ;
			return ;
		}
		$this->ConfirmeSucces("Démarrage initié, veuillez patienter quelques minutes.") ;
	}
	protected function RenduMsgAction()
	{
		return '<div class="alert '.(($this->StatutAction == 0) ? "alert-success" : "alert-danger").'">'.$this->MessageAction.'</div>' ;
	}
	public function RenduSpecifique()
	{
		return $this->RenduMsgAction() ;
	}
}
