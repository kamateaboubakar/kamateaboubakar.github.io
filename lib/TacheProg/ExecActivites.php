<?php

namespace Rpa2p\TacheProg ;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ExecActivites extends TacheProg
{
	public $VarsFich = array() ;
	public function ChemFichLock($idPlanif)
	{
		return dirname(realpath($_SERVER["argv"][0]))."/~exec_activite_".$idPlanif.".lock" ;
	}
	public function ExecutionEnCours($idPlanif)
	{
		$chemFich = $this->ChemFichLock($idPlanif) ;
		$ok = file_exists($chemFich) ;
		if($ok)
		{
			$pid = trim(file_get_contents($chemFich)) ;
			if(\Rpa2p\Application::TypeOS() == "WINDOWS")
			{
				$procPresent = trim(shell_exec('powershell -command "(Get-Process -Id '.$pid.' -Ea SilentlyContinue).length"')) ;
				if($procPresent !== false && $procPresent !== NULL && ($procPresent === "0" || $procPresent === 0))
				{
					unlink($chemFich) ;
					$ok = false ;
				}
				else
				{
					if(date("U") - filemtime($chemFich) > \Rpa2p\Config\ExecActivites::DELAI_ACTIVITE)
					{
						$ok = false ;
						shell_exec("taskkill /F ".$pid." 2>&1") ;
					}
				}
			}
		}
		return $ok ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::TACHE_EXEC_ACTIVIT ;
	}
	protected function ActualiseVarsFich(& $lgn)
	{
		$this->VarsFich["date_execution"] = date("Y-m-d") ;
		$this->VarsFich["heure_execution"] = date("H:i:s") ;
		$this->VarsFich["id_job"] = $lgn["id_job"] ;
		$this->VarsFich["titre_job"] = $lgn["titre_job"] ;
		$this->VarsFich["id_activite"] = $lgn["id"] ;
		$this->VarsFich["titre_activite"] = $lgn["titre"] ;
	}
	protected function ExecuteSession()
	{
		$idPlanif = (isset($_SERVER["argv"]) && isset($_SERVER["argv"][1])) ? intval($_SERVER["argv"][1]) : -1 ;
		if($idPlanif < 0 || $idPlanif > \Rpa2p\Config\ExecActivites::MAX_PLANIF)
		{
			echo "Argument #1 (Id Planif) invalide : il doit etre compris entre 0 et ".\Rpa2p\Config\ExecActivites::MAX_PLANIF. PHP_EOL ;
			return ;
		}
		if($this->ExecutionEnCours($idPlanif))
		{
			echo "Tache en cours d'execution" ;
			return ;
		}
		$fichLock = $this->ChemFichLock($idPlanif) ;
		$pidScript = getmypid() ;
		file_put_contents($fichLock, $pidScript) ;
		$delaiMax = 600 ;
		$delaiAttente = 15 ;
		$delaiEcoule = 0 ;
		while($this->ApplicationParent->TachPlanif->CalculsEnCours() && $delaiAttente <= $delaiMax)
		{
			sleep($delaiAttente) ;
			$delaiEcoule += $delaiAttente ;
		}
		if($delaiAttente >= $delaiMax)
		{
			echo "Delai de planification trop depasse" ;
			return ;
		}
		$bd = $this->ApplicationParent->CreeBdPrinc() ;
		$lgnsVar = $bd->FetchSqlRows("select nom, AES_DECRYPT(FROM_BASE64(valeur), '".\Rpa2p\Config\Cryptage::CLE_VAR."') valeur from rpapp_variable") ;
		if(! is_array($lgnsVar))
		{
			echo "Exception SQL Variables : ".$bd->ConnectionException ;
			return ;
		}
		$this->VarsFich = array() ;
		foreach($lgnsVar as $i => $lgn)
		{
			$this->VarsFich[$lgn["nom"]] = $lgn["valeur"] ;
		}
		$lgnsJob = array() ;
		do
		{
			file_put_contents($fichLock, $pidScript) ;
			if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == false && $idPlanif > 0)
			{
				$lgnsJob = $bd->FetchSqlRows("select distinct id_job from rpapp_tache_activite where id_planif=".$idPlanif." limit 0, ".\Rpa2p\Config\ExecActivites::MAX_JOBS_SESSION) ;
			}
			else
			{
				$lgnsJob = $bd->FetchSqlRows("select distinct id_job from rpapp_exec_queue_".$idPlanif." limit 0, ".\Rpa2p\Config\ExecActivites::MAX_JOBS_SESSION) ;
			}
			if(! is_array($lgnsJob))
			{
				echo "Exception SQL Jobs : ".$bd->ConnectionException ;
				break ;
			}
			elseif(count($lgnsJob) == 0)
			{
				break ;
			}
			$idsJob = array() ;
			foreach($lgnsJob as $i => $lgnTemp)
			{
				$idsJob[] = $lgnTemp["id_job"] ;
			}
			if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == false && $idPlanif > 0)
			{
				$lgnsActivit = $bd->FetchSqlRows("select * from rpapp_tache_activite where id_planif=".$idPlanif." and id_job in(".join(", ", $idsJob).") order by id_job, id_planif_job") ;
			}
			else
			{
				$lgnsActivit = $bd->FetchSqlRows("select * from rpapp_exec_queue_".$idPlanif." where id_job in(".join(", ", $idsJob).") order by id_job, id_planif_job") ;
			}
			$typeNotif = null ;
			$typeActivite = null ;
			$totalSuccesJob = 0 ;
			$totalFailsJob = 0 ;
			$idJob = 0 ;
			$idPlanifJob = 0 ;
			$msgErrJob = array() ;
			if(is_array($lgnsActivit))
			{
				$lgnJob = array() ;
				foreach($lgnsActivit as $i => $lgn)
				{
					file_put_contents($fichLock, $pidScript) ;
					// echo $idJob." ".$idPlanifJob." == ".$lgnJob["id_job"]." ".$lgnJob["id_planif_job"].PHP_EOL ;
					if($idJob != $lgn["id_job"] || $idPlanifJob != $lgn["id_planif_job"])
					{
						if($idJob != 0)
						{
							$bd->UpdateRow(
								"rpapp_exec_job",
								array(
									"date_fin" => date("Y-m-d H:i:s"), 
									"total_succes" => $totalSuccesJob, 
									"total_echecs" => $totalFailsJob,
									"statut" => ($totalFailsJob == 0) ? 1 : 0,
								),
								"id=:0",
								array($idJobExec)
							) ;
							$bd->UpdateRow(
								"rpapp_job",
								array(
									"date_dern_exec" => date("Y-m-d H:i:s"), 
									"succes_dern_exec" => $totalSuccesJob, 
									"echec_dern_exec" => $totalFailsJob,
									"statut_dern_exec" => ($totalFailsJob == 0) ? 1 : 0,
									"id_dern_exec" => $idJobExec,
									"msg_dern_exec" => json_encode($msgErrJob),
								),
								"id=:0",
								array($idJob)
							) ;
							$lgnJob["total_echecs"] = $totalFailsJob ;
							$lgnJob["total_succes"] = $totalSuccesJob ;
							$typeNotif->TermineExecJob($lgnJob, $bd, $this) ;
							file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR . \Rpa2p\Config\Chemin::REPORTS. DIRECTORY_SEPARATOR . "exec_jobs" . DIRECTORY_SEPARATOR . $idJobExec.".html", $typeNotif->ContenuHtml()) ;
							if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == false && $idPlanif > 0)
							{
								$bd->RunSql('delete from rpapp_tache_activite where id_job='.$idJob.' and id_planif_job='.$idPlanifJob) ;
							}
							else
							{
								$bd->RunSql('delete from rpapp_exec_queue_'.$idPlanif.' where id_job='.$idJob.' and id_planif_job='.$idPlanifJob) ;
							}
						}
						// $bd->RunSql("update rpapp_a")
						$idCtrl = uniqid() ;
						$ok = $bd->InsertRow("rpapp_exec_job", array("id_job" => $lgn["id_job"], "id_planif_job" => $idPlanifJob, 'statut' => 2, "id_ctrl" => $idCtrl, "id_planif" => $idPlanif)) ;
						if($ok)
						{
							$idJobExec = $bd->FetchSqlValue("select id from rpapp_exec_job where id_ctrl=:0 ", array($idCtrl), "id") ;
						}
						else
						{
							echo "Exception SQL Creation Job : ".$bd->ConnectionException."\r\n" ;
							break ;
						}
						$totalSuccesJob = 0 ;
						$totalFailsJob = 0 ;
						$msgErrJob = array() ;
						$idJob = $lgn["id_job"] ;
						$idPlanifJob = $lgn["id_planif_job"] ;
						$typeNotif = $this->ApplicationParent->CreeTypeNotifJob($lgn["type_notif"]) ;
						$typeNotif->PrepareExecJob($lgn, $bd, $this) ;
					}
					$this->ActualiseVarsFich($lgn) ;
					$dateDebut = date("Y-m-d H:i:s") ;
					$totalEssais = 0 ;
					$typeActivite = null ;
					$doitReessayer = true ;
					do
					{
						$tmstmpDebutActivit = date("U") ;
						$typeActivite = $this->ApplicationParent->CreeTypeActiviteJob($lgn["type_activite"]) ;
						$typeActivite->Demarre($lgn, $bd, $this) ;
						$tmstmpFinActivit = date("U") ;
						$totalEssais++ ;
						$doitReessayer = $typeActivite->DoitReessayer() ;
						if($doitReessayer)
						{
							$bd->RunSql(
								"insert into rpapp_exec_activite_err (id_exec_job, id_job, id_activite, date_debut, date_fin, contenu_brut, delai)
values (:id_exec_job, :id_job, :id_activite, :date_debut, :date_fin, :contenu_brut, :delai)",
								array(
									'id_exec_job' => $idJobExec,
									'id_job' => $lgn["id_job"],
									"id_activite" => $lgn["id"],
									"date_debut" => date("Y-m-d H:i:s", $tmstmpDebutActivit),
									"date_fin" => date("Y-m-d H:i:s", $tmstmpFinActivit),
									"contenu_brut" => (($typeActivite->ResultExec->NomFichBrut != "") ? file_get_contents($typeActivite->RepActivit.DIRECTORY_SEPARATOR .$typeActivite->ResultExec->NomFichBrut) : $typeActivite->ResultExec->ContenuHtml),
									"delai" => $tmstmpFinActivit - $tmstmpDebutActivit
								)
							) ;
						}
					}
					while($doitReessayer && $totalEssais < $typeActivite->MaxEssais) ;
					$typeNotif->AnalyseExecActivit($lgn, $typeActivite, $bd, $this) ;
					$dateFin = date("Y-m-d H:i:s") ;
					$bd->InsertRow(
						'rpapp_exec_activite',
						array(
							'id_exec_job' => $idJobExec,
							'id_job' => $lgn["id_job"],
							"id_activite" => $lgn["id"],
							'date_debut' => $dateDebut,
							'date_fin' => $dateFin,
							'delai' => $typeActivite->ResultExec->Delai,
							'total_succes' => $typeActivite->ResultExec->TotalSucces,
							'total_echecs' => $typeActivite->ResultExec->TotalEchecs,
							'contenu_brut' => (($typeActivite->ResultExec->NomFichBrut != "") ? file_get_contents($typeActivite->RepActivit.DIRECTORY_SEPARATOR .$typeActivite->ResultExec->NomFichBrut) : $typeActivite->ResultExec->ContenuHtml),
							'statut' => ($typeActivite->ResultExec->EstSucces) ? 1 : 0
						)
					) ;
					$lgnExecActivit = $bd->FetchSqlRow(
						"select id from rpapp_exec_activite where id_exec_job=:0 and id_activite=:1",
						array($idJobExec, $lgn["id"])
					) ;
					if(count($typeActivite->ResultExec->Infos) > 0)
					{
						foreach($typeActivite->ResultExec->Infos as $m => $info)
						{
							$bd->InsertRow(
								"rpapp_info_exec_activite",
								array(
									"id_exec_activite" => $lgnExecActivit["id"],
									"id_exec_job" => $idJobExec,
									"id_activite" => $lgn["id"],
									"nom" => $info->Nom,
									"valeur" => $info->Valeur,
									"niveau_alerte" => $info->NiveauAlerte,
								)
							) ;
						}
					}
					$bd->UpdateRow(
						"rpapp_activite",
						array(
							"date_dern_exec" => $dateFin, 
							"statut_dern_exec" => ($typeActivite->ResultExec->EstSucces) ? 1 : 0,
							"msg_dern_exec" => ($typeActivite->ResultExec->EstSucces) ? "" : $typeActivite->ResultExec->MsgErreur,
						),
						"id=:0",
						array($lgn["id"])
					) ;
					if($typeActivite->ResultExec->EstSucces)
					{
						$totalSuccesJob ++ ;
					}
					else
					{
						$totalFailsJob ++ ;
						$msgErrJob[] = array($lgn["titre"], $typeActivite->ResultExec->MsgErreur) ;
					}
					if(\Rpa2p\Config\ExecActivites::MAJ_STATS)
					{
						$bd->UpdateRow(
							"rpapp_exec_job",
							array(
								"date_fin" => date("Y-m-d H:i:s"), 
								"total_succes" => $totalSuccesJob, 
								"total_echecs" => $totalFailsJob
							),
							"id=:0",
							array($idJobExec)
						) ;
					}
					$lgnJob = $lgn ;
				}
				if($idJobExec != 0)
				{
					$bd->UpdateRow(
						"rpapp_exec_job",
						array(
							"date_fin" => date("Y-m-d H:i:s"),
							"total_succes" => $totalSuccesJob,
							"total_echecs" => $totalFailsJob,
							"statut" => ($totalFailsJob == 0) ? 1 : 0
						),
						"id=:0",
						array($idJobExec)
					) ;
					$bd->UpdateRow(
						"rpapp_job",
						array(
							"date_dern_exec" => date("Y-m-d H:i:s"), 
							"succes_dern_exec" => $totalSuccesJob, 
							"echec_dern_exec" => $totalFailsJob,
							"statut_dern_exec" => ($totalFailsJob == 0) ? 1 : 0,
							"msg_dern_exec" => json_encode($msgErrJob),
							"id_dern_exec" => $idJobExec,
						),
						"id=:0",
						array($idJob)
					) ;
					$lgnJob["total_echecs"] = $totalFailsJob ;
					$lgnJob["total_succes"] = $totalSuccesJob ;
					$typeNotif->TermineExecJob($lgnJob, $bd, $this) ;
					file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR . \Rpa2p\Config\Chemin::REPORTS. DIRECTORY_SEPARATOR . "exec_jobs" . DIRECTORY_SEPARATOR . $idJobExec.".html", $typeNotif->ContenuHtml()) ;
					if(\Rpa2p\Config\ExecActivites::CACHE_QUEUES == false && $idPlanif > 0)
					{
						$bd->RunSql('delete from rpapp_tache_activite where id_job='.$lgnJob["id_job"].' and id_planif_job='.$lgnJob["id_planif_job"]) ;
					}
					else
					{
						$bd->RunSql('delete from rpapp_exec_queue_'.$idPlanif.' where id_job='.$lgnJob["id_job"].' and id_planif_job='.$lgnJob["id_planif_job"]) ;
					}
				}
			}
			else
			{
				echo "Exception SQL Activites : ".$bd->ConnectionException.PHP_EOL ;
				break ;
			}
		}
		while(count($lgnsJob) > 0) ;
		if(file_exists($fichLock))
		{
			unlink($fichLock) ;
		}
	}
	public function colorStatusText($val)
	{
		return $this->ApplicationParent->colorStatusText($val) ;
	}
}
