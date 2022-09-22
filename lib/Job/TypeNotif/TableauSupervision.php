<?php

namespace Rpa2p\Job\TypeNotif ;

class TableauSupervision extends TypeNotif
{
	protected $CaptureImgs = true ;
	public function Id()
	{
		return 'tableau_supervision' ;
	}
	public function Titre()
	{
		return 'Tableau de Supervision' ;
	}
	public function EnvoieAlerte(& $lgnJob, & $bd, & $tacheProg)
	{
		$lgnJour = $bd->FetchSqlRow("select date(date_exec_jour) date_exec_jour, date(now()) date_actu_jour from rpapp_job where id=:0", array($lgnJob["id_job"])) ;
		if(is_array($lgnJour))
		{
			if(count($lgnJour) == 0 || $lgnJour["date_exec_jour"] != $lgnJour["date_actu_jour"])
			{
				$ok = $bd->RunSql("update rpapp_job set date_exec_jour=now(), succes_exec_jour=0, echec_exec_jour=0 where id=:0", array($lgnJob["id_job"])) ;
				if(! $ok)
				{
					echo "Exception SQL Maj Date : ".$bd->ConnectionException.PHP_EOL ;
				}
			}
		}
		else
		{
			echo "Exception SQL Recup Job : ".$bd->ConnectionException.PHP_EOL ;
		}
		$ok = $bd->RunSql("update rpapp_job set date_exec_jour=now(), statut_dern_exec=:1, succes_exec_jour=case when :1=1 then succes_exec_jour + 1 else succes_exec_jour end, echec_exec_jour=case when :1=0 then echec_exec_jour + 1 else echec_exec_jour end, succes_exec_total=case when :1=1 then succes_exec_total + 1 else succes_exec_total end, echec_exec_total=case when :1=0 then echec_exec_total + 1 else echec_exec_total end
where id=:0", array($lgnJob["id_job"], ($lgnJob["total_echecs"] == 0) ? 1 : 0)) ;
		if(! $ok)
		{
			echo "Exception SQL Maj Job : ".$bd->ConnectionException.PHP_EOL ;
		}
	}
}
