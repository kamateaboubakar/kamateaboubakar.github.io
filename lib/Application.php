<?php

namespace Rpa2p ;

class Application extends \Pv\Application\Application
{
	public $TypesPeriodeJob = array() ;
	public $TypesNotifJob = array() ;
	public $TypesActiviteJob = array() ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->ChemRelRegServsPersists = \Rpa2p\Config\Chemin::REP_REG_SERV_PERSIST ;
	}
	public function CreeBDPrinc()
	{
		return new BDPrinc() ;
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeTypesActiviteJob() ;
		$this->ChargeTypesPeriodeJob() ;
		$this->ChargeTypesNotifJob() ;
	}
	protected function InsereTypeNotifJob($typeNotif)
	{
		$this->TypesNotifJob[$typeNotif->Id()] = $typeNotif ;
	}
	protected function ChargeTypesNotifJob()
	{
		$this->InsereTypeNotifJob(new Job\TypeNotif\Silencieux()) ;
		$this->InsereTypeNotifJob(new Job\TypeNotif\Email()) ;
		$this->InsereTypeNotifJob(new Job\TypeNotif\TableauSupervision()) ;
	}
	public function CreeTypePeriodeJob($nom)
	{
		$typePeriode = null ;
		if(isset($this->TypesPeriodeJob[$nom]))
		{
			$classeNative = get_class($this->TypesPeriodeJob[$nom]) ;
			$typePeriode = new $classeNative() ;
		}
		else
		{
			$typePeriode = new Job\TypePeriode\Silencieux() ;
		}
		return $typePeriode ;
	}
	public function CreeTypeActiviteJob($nom)
	{
		$typeActivite = null ;
		if(isset($this->TypesActiviteJob[$nom]))
		{
			$classeNative = get_class($this->TypesActiviteJob[$nom]) ;
			$typeActivite = new $classeNative() ;
		}
		else
		{
			$typeActivite = new Job\TypeActivite\RobotFramework() ;
		}
		return $typeActivite ;
	}
	public function CreeTypeNotifJob($nom)
	{
		$typeNotif = null ;
		if(isset($this->TypesNotifJob[$nom]))
		{
			$classeNative = get_class($this->TypesNotifJob[$nom]) ;
			$typeNotif = new $classeNative() ;
		}
		else
		{
			$typeNotif = new Job\TypeNotif\Silencieux() ;
		}
		return $typeNotif ;
	}
	public function CreeFournTypesNotifJob()
	{
		$fourn = new \Pv\FournisseurDonnees\Direct() ;
		$fourn->Valeurs["jobs"] = array() ;
		$i = 0 ;
		foreach($this->TypesNotifJob as $id => $typeNotif)
		{
			$fourn->Valeurs["jobs"][] = array(
				"id" => $typeNotif->Id(),
				"titre" => $typeNotif->Titre(),
				"index" => $i,
			) ;
			$i++ ;
		}
		return $fourn ;
	}
	protected function InsereTypeActiviteJob($typeActivite)
	{
		$this->TypesActiviteJob[$typeActivite->Id()] = $typeActivite ;
	}
	protected function ChargeTypesActiviteJob()
	{
		$this->InsereTypeActiviteJob(new Job\TypeActivite\ConsolideJob()) ;
		$this->InsereTypeActiviteJob(new Job\TypeActivite\RobotFramework()) ;
	}
	public function CreeFournTypesActiviteJob()
	{
		$fourn = new \Pv\FournisseurDonnees\Direct() ;
		$fourn->Valeurs["jobs"] = array() ;
		$i = 0 ;
		foreach($this->TypesActiviteJob as $id => $typeActivite)
		{
			$fourn->Valeurs["jobs"][] = array(
				"id" => $typeActivite->Id(),
				"titre" => $typeActivite->Titre(),
				"index" => $i,
			) ;
			$i++ ;
		}
		return $fourn ;
	}
	protected function InsereTypePeriodeJob($typePeriode)
	{
		$this->TypesPeriodeJob[$typePeriode->Id()] = $typePeriode ;
	}
	protected function ChargeTypesPeriodeJob()
	{
		$this->InsereTypePeriodeJob(new Job\TypePeriode\Jamais()) ;
		$this->InsereTypePeriodeJob(new Job\TypePeriode\Toujours()) ;
		// $this->InsereTypePeriodeJob(new Job\TypePeriode\ChaqueMois()) ;
		$this->InsereTypePeriodeJob(new Job\TypePeriode\ChaqueSemaine()) ;
		$this->InsereTypePeriodeJob(new Job\TypePeriode\ChaqueJour()) ;
		$this->InsereTypePeriodeJob(new Job\TypePeriode\ChaqueHeure()) ;
	}
	public function CreeFournTypesPeriodeJob()
	{
		$fourn = new \Pv\FournisseurDonnees\Direct() ;
		$fourn->Valeurs["jobs"] = array() ;
		$i = 0 ;
		foreach($this->TypesPeriodeJob as $id => $typePeriode)
		{
			$fourn->Valeurs["jobs"][] = array(
				"id" => $typePeriode->Id(),
				"titre" => $typePeriode->Titre(),
				"index" => $i,
			) ;
			$i++ ;
		}
		return $fourn ;
	}
	protected function ChargeTachesProgs()
	{
		$this->TacheExecActivit = $this->InsereTacheProg('execActivites', new TacheProg\ExecActivites) ;
		$this->TachPlanif = $this->InsereTacheProg('planifExecs', new TacheProg\Planificateur) ;
		$this->TachArch = $this->InsereTacheProg('archiveur', new TacheProg\Archiveur) ;
	}
	public function colorStatusText($val)
	{
		$color = "" ;
		switch(strtoupper($val))
		{
			case "ERROR" :
			{
				$color = "red" ;
			}
			break ;
			case "FAIL" :
			{
				$color = "red" ;
			}
			break ;
			case "PASS" :
			{
				$color = "green" ;
			}
			break ;
		}
		return $color ;
	}
	public function InstallePlanifExecs()
	{
		$bd = $this->CreeBdPrinc() ;
		$ixPremQueue = 2 ;
		$queuesDispo = range($ixPremQueue, \Rpa2p\Config\ExecActivites::MAX_PLANIF) ;
		$ok = $bd->RunSql('drop table if exists rpapp_tache_job') ;
		if(! $ok)
		{
			echo "Err Suppr Job Tache : ".$bd->ConnectionException ;
			return 1 ;
		}
		$lgnsExecEnCours = $bd->FetchSqlRows('select t1.id_planif,
max(t1.id_job) id_job,
max(t1.id) id_exec_job,
max(t2.date_debut) date_debut,
case
	when max(t2.date_debut) is null then 1
	when DATE_ADD(max(t2.date_debut), INTERVAL '.\Rpa2p\Config\ExecActivites::DELAI_ACTIVITE.' SECOND) < NOW() then 1
	else 0
end delai_expire
from rpapp_exec_job t1
left join rpapp_exec_activite t2 on t1.id = t2.id_exec_job
where t1.statut=2
group by t1.id_planif') ;
		// print $bd->LastSqlText.PHP_EOL ;
		$idsJobEnCours = array() ;
		if(is_array($lgnsExecEnCours))
		{
			foreach($lgnsExecEnCours as $i => $lgnExec)
			{
				if($lgnExec["delai_expire"] == 1)
				{
					echo "Expiration du job ".$lgnExec["id_job"]." sur la file ".$lgnExec["id_planif"].PHP_EOL ;
					$idActivit = 
					$lgnActivit = $bd->FetchSqlRow(
						'select t1.id_activite from rpapp_exec_activite t1 where id_exec_job=:0 and statut=2',
						array(
							$lgnExec["id_exec_job"]
						)
					) ;
					$possedeActivit = false ;
					if(count($lgnActivit) > 0)
					{
						$possedeActivit = true ;
					}
					else
					{
						$lgnActivit = $bd->FetchSqlRow('select t1.id, t1.type_activite
from (select * from rpapp_activite where id_job=:0 and actif=1) t1
left join (select * from rpapp_exec_activite where id_exec_job=:1) t2
on t1.id=t2.id_activite
where t2.id_activite is null
limit 0, 1', array($lgnExec["id_job"], $lgnExec["id_exec_job"])) ;
						if(is_array($lgnActivit))
						{
							if(count($lgnActivit) > 0)
							{
								$ok = $bd->RunSql(
									"insert into rpapp_exec_activite (id_exec_job, id_job, id_activite, date_debut, date_fin, total_succes, total_echecs, contenu_brut, delai, statut)
		values (:id_exec_job, :id_job, :id_activite, :date_debut, now(), 0, 1, :contenu_brut, ".\Rpa2p\Config\ExecActivites::DELAI_ACTIVITE.", 2)",
									array(
										"id_exec_job" => $lgnExec["id_exec_job"],
										"id_job" => $lgnExec["id_job"],
										"id_activite" => $lgnActivit["id"],
										"date_debut" => date("Y-m-d H:i:s"),
										"contenu_brut" => "Délai d'exécution expiré",
									)
								) ;
								if(! $ok)
								{
									echo "Exception insert activit. expiree : ".$bd->ConnectionException.PHP_EOL ;
									return ;
								}
								$possedeActivit = true ;
							}
						}
						if($possedeActivit)
						{
							if(\Rpa2p\Config\ExecActivites::MAJ_STATS == false)
							{
								$lgnStatsJob = $bd->FetchSqlRow("select sum(case when statut=0 then 1 else 0 end) total_echecs, sum(case when statut=1 then 1 else 0 end) total_succes
from rpapp_exec_activite t1
where t1.id_exec_job=:0", array($lgnExec["id_exec_job"])) ;
								$bd->RunSql("update rpapp_exec_job set statut=0, date_fin=now(), total_echecs=:2, total_succes=:1
	where id=:0", array(
		$lgnExec["id_exec_job"],
		($lgnStatsJob["total_succes"] !== null) ? $lgnStatsJob["total_succes"] : 0,
		(($lgnStatsJob["total_echecs"] !== null) ? $lgnStatsJob["total_echecs"] : 0) + 1
	)
) ;
							}
							else
							{
								$bd->RunSql("update rpapp_exec_job set statut=0, date_fin=now(), total_echecs=total_echecs+1
	where id=:0", array($lgnExec["id_exec_job"])) ;
							}
						}
						else
						{
							$bd->RunSql("update rpapp_exec_job set statut=0, date_fin=now() where id=:0", $lgnExec["id_exec_job"]) ;
						}
						if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == true)
						{
							$ok = $bd->RunSql("delete from rpapp_exec_queue_".$lgnExec["id_planif"]." where id_job=".$lgnExec["id_job"]) ;
						}
						else
						{
							$ok = $bd->RunSql("delete from rpapp_tache_activite where id_job=".$lgnExec["id_job"]." and id_planif=".$lgnExec["id_job"]) ;
							
						}
						if($this->TacheExecActivit->ExecutionEnCours($lgnExec["id_planif"]))
						{
							$fichLock = $this->TacheExecActivit->ChemFichLock($lgnExec["id_planif"]) ;
							if(file_exists($fichLock))
							{
								$pid = trim(file_get_contents($fichLock)) ;
								if(\Rpa2p\Application::TypeOS() == "WINDOWS")
								{
									shell_exec('taskkill /F /PID '.$pid) ;
								}
								unlink($fichLock) ;
							}
						}
					}
				}
				else
				{
					$ixPlanif = array_search($lgnExec["id_planif"], $queuesDispo) ;
					$idsJobEnCours[] = $lgnExec["id_job"] ;
					if($ixPlanif !== false)
					{
						array_splice($queuesDispo, $ixPlanif, 1) ;
					}
				}
			}
		}
		else
		{
			echo "Exception Queues indisponibles : ".$bd->ConnectionException.PHP_EOL ;
			return 1 ;
		}
		for($noQueue=$ixPremQueue; $noQueue<= \Rpa2p\Config\ExecActivites::MAX_PLANIF; $noQueue++)
		{
			$ixPlanif = array_search($noQueue, $queuesDispo) ;
			if($ixPlanif !== false && $this->TacheExecActivit->ExecutionEnCours($noQueue))
			{
				array_splice($queuesDispo, $ixPlanif, 1) ;
			}
		}
		// Mettre les jobs non demarrés dans l'historique
		$listeJobExclus = join(", ", (count($idsJobEnCours) > 0) ? $idsJobEnCours : array("0")) ;
		$sqlJobsNonDemarr = '' ;
		if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == true)
		{
			$sqlJobsNonDemarr = '' ;	
			foreach($queuesDispo as $ix => $i)
			{
				if($ix > 0)
				{
					$sqlJobsNonDemarr .= ' union ' ;
				}
				$sqlJobsNonDemarr .= 'select distinct id_job, id_planif, id_planif_job from rpapp_exec_queue_'.$i ;
			}
			$sqlJobsNonDemarr .= ' where id_job not in ('.$listeJobExclus.')' ;
		}
		else
		{
			$sqlJobsNonDemarr .= 'select distinct id_job, id_planif, id_planif_job from rpapp_tache_activite' ;
		}
		$ok = $bd->RunSql("insert into rpapp_queue_non_demar (id_job, id_planif, id_planif_job)
".$sqlJobsNonDemarr) ;
		if(! $ok)
		{
			echo "Exception Jobs non démarrés : ".$bd->ConnectionException.PHP_EOL ;
			return 1 ;
		}
		$totqueuesDispo = count($queuesDispo) ;
		if($totqueuesDispo == 0)
		{
			echo "Aucune queue disponible" ;
			return ;
		}
		$exprIdPlanif = '' ;
		// print_r($queuesDispo) ;
		foreach($queuesDispo as $i => $val)
		{
			$exprIdPlanif .= ' when no_planif - 1 % '.$totqueuesDispo.'='.$i.' then '.$val.PHP_EOL ;
		}
		echo "Queues disponibles : ".join(", ", $queuesDispo).PHP_EOL ;
		$sqlJob = 'create table rpapp_tache_job as
select case '.$exprIdPlanif.'
else '.$queuesDispo[0].' end id_planif, id_job, id_planif_job
from (
select @rn:=@rn+1 no_planif, t1.id_job, t1.id_planif_job
	from (SELECT @rn:=0) t0, (select distinct t1.id_job, t3.id id_planif_job from rpapp_activite t1
	inner join rpapp_job t2 on t1.id_job=t2.id
	inner join rpapp_planif_job t3 on t1.id_job=t3.id_job
	where t1.actif=1 and t2.actif=1 and t3.actif=1 and t1.id_job not in ('.$listeJobExclus.') and (1='.((\Rpa2p\Config\ExecActivites::TOUJOURS_EXECUTER) ? 1 : 0).' or ' ;
			$i=0 ;
			foreach($this->TypesPeriodeJob as $nom => $typePrd)
			{
				if($i > 0)
				{
					$sqlJob .= " or " ;
				}
				$sqlJob .= "(t3.type_periode='".$nom."' and (".$typePrd->CondExec("t3")."))"."\n" ;
				$i++ ;
			}
			$sqlJob .= ')) t1
) tt' ;
		// echo $sqlJob.PHP_EOL ;
		$ok = $bd->RunSql($sqlJob) ;
		if(! $ok)
		{
			echo "Err Creation Job Planif : ".$bd->ConnectionException ;
			return 1 ;
		}
		$bd->RunSql('alter table rpapp_tache_job add index(id_job)') ;
		$ok = $bd->RunSql('drop table if exists rpapp_tache_activite') ;
		if(! $ok)
		{
			echo "Err Creation Activite Planif : ".$bd->ConnectionException ;
			return 1 ;
		}
		$ok = $bd->RunSql('create table rpapp_tache_activite as
select t1.*, t3.id_planif, t3.id_planif_job, t2.nom titre_job, t5.type_periode, t5.param1_periode, t5.param2_periode, t5.param3_periode, t5.param4_periode, t5.type_notif, t5.param1_notif, t5.param2_notif, t5.param3_notif, t5.param4_notif, t4.titre titre_application
from rpapp_activite t1
inner join rpapp_job t2 on t1.id_job=t2.id
inner join rpapp_tache_job t3 on t1.id_job=t3.id_job
left join rpapp_application t4 on t2.id_application=t4.id
inner join rpapp_planif_job t5 on t5.id=t3.id_planif_job
where t1.actif=1 and t2.actif=1 and t5.actif=1') ;
		if(! $ok)
		{
			echo "Err Creation Activite Planif : ".$bd->ConnectionException ;
			return 1 ;
		}
		$bd->RunSql('alter table rpapp_tache_activite add index(id_planif)') ;
		$bd->RunSql('alter table rpapp_tache_activite add index(id_job)') ;
		if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == true)
		{
			foreach($queuesDispo as $ix => $i)
			{
				if($this->TacheExecActivit->ExecutionEnCours($i))
				{
					continue ;
				}
				$ok = $bd->RunSql('drop table if exists rpapp_exec_queue_'.$i) ;
				if(! $ok)
				{
					echo "Echec Suppr Queue ".$i." : ".$bd->ConnectionException."\n" ;
				}
				$sql = 'select t1.* from rpapp_tache_activite t1 where id_planif='.$i.' order by t1.id_job' ;
				$ok = $bd->RunSql("create table rpapp_exec_queue_".$i." as ".$sql) ;
				if(! $ok)
				{
					echo "Exception BD : ".$bd->ConnectionException."\n" ;
					return 1 ;
				}
				$bd->RunSql('alter table rpapp_exec_queue_'.$i.' add index(id_job)') ;
			}
		}
		if(\Rpa2p\Config\ExecActivites::REJOUER_NON_DEMARRES == true)
		{
			$ok = $bd->RunSql('delete t1
from rpapp_queue_non_demar t1
inner join rpapp_exec_queue_1 t2 on t1.id_job=t2.id_job and t1.id_planif_job=t2.id_planif_job') ;
			if(! $ok)
			{
				echo "Exception prepare rejouer : ".$bd->ConnectionException ;
			}
			else
			{
				$ok = $bd->RunSql('insert into rpapp_exec_queue_1
select t1.*, 1 id_planif, t3.id_planif_job, t2.nom titre_job, t5.type_periode, t5.param1_periode, t5.param2_periode, t5.param3_periode, t5.param4_periode, t5.type_notif, t5.param1_notif, t5.param2_notif, t5.param3_notif, t5.param4_notif, t4.titre titre_application
from rpapp_activite t1
inner join rpapp_job t2 on t1.id_job=t2.id
inner join (select distinct id_job, id_planif_job from rpapp_queue_non_demar) t3 on t1.id_job=t3.id_job
inner join rpapp_planif_job t5 on t5.id=t3.id_planif_job
left join rpapp_application t4 on t2.id_application=t4.id
where t1.actif=1 and t2.actif=1 and t5.actif=1') ;
				if(! $ok)
				{
					echo "Exception rejoue non demarree : ".$bd->ConnectionException ;
				}
				$bd->RunSql('truncate table rpapp_queue_non_demar') ;
			}
		}
		if(\Rpa2p\Config\ExecActivites::MAJ_INFOS_PLANIF == true)
		{
			$ok = $bd->RunSql('insert into rpapp_info_planif (id_queue, id_job, nom_job, id_planif, type_periode, type_notif)
select t1.id_planif, t1.id_job, t2.nom, t1.id_planif_job, t3.type_periode, t3.type_notif
from rpapp_tache_job t1
inner join rpapp_job t2 on t1.id_job = t2.id
inner join rpapp_planif_job t3 on t1.id_job=t3.id_job and t1.id_planif_job=t3.id') ;
			if(! $ok)
			{
				echo "Echec MAJ infos planif : ".$bd->ConnectionException.PHP_EOL ;
			}
		}
		return 0 ;
	}
	protected function ChargeIHMs()
	{
		$this->InsereIHM('zonePrinc', new ZonePrinc\ZonePrinc) ;
	}
	public function EncrypteFich($valeur, $cle='')
	{
		$crypter = new \Pv\Openssl\Crypter() ;
		$crypter->cipher = \Rpa2p\Config\Cryptage::CYPHER_FICH ;
		$crypter->key = ($cle != '') ? $cle : \Rpa2p\Config\Cryptage::CLE_FICH ;
		return $crypter->encode($valeur) ;
	}
	public function DecrypteFich($valeur, $cle='')
	{
		$crypter = new \Pv\Openssl\Crypter() ;
		$crypter->cipher = \Rpa2p\Config\Cryptage::CYPHER_FICH ;
		$crypter->key = ($cle != '') ? $cle : \Rpa2p\Config\Cryptage::CLE_FICH ;
		return $crypter->decode($valeur) ;
	}
	public function RepOutils()
	{
		return dirname(__FILE__)."/../tools" ;
	}
	public function ExecScriptPsh($script, $args="")
	{
		$chemRes = $this->RepOutils().DIRECTORY_SEPARATOR.$script.".log" ;
		$cmdPs1 = $this->RepOutils().DIRECTORY_SEPARATOR .$script." ".$args ;
		// echo $cmdPs1.PHP_EOL ;
		shell_exec("powershell -File ".$cmdPs1." > ".$chemRes." 2>&1") ;
		$ctnRes = false ;
		if(file_exists($chemRes))
		{
			$ctnRes = file_get_contents($chemRes) ;
			unlink($chemRes) ;
		}
		return $ctnRes ;
	}
	public static function TypeOS()
	{
		$type = "LINUX" ;
		if(PHP_OS == "WINNT" || PHP_OS == "Windows" || PHP_OS == "WIN32")
		{
			return "WINDOWS" ;
		}
		else
		{
			return "LINUX" ;
		}
	}
}
