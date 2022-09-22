<?php

namespace Rpa2p\TacheProg ;

class Archiveur extends \Pv\TacheProg\TacheProg
{
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::TACHE_ARCH ;
	}
	public function ExecuteSession()
	{
		$this->ApplicationParent->ExecScriptPsh("KillExpiredProcess.ps1") ;
		$bd = $this->ApplicationParent->CreeBdPrinc() ;
		$delaiArch = \Rpa2p\Config\BD::ARCH_DELAI ;
		echo "Archivage dans la Base de donnees...".PHP_EOL ;
		$ok = $bd->RunSql("insert into rpapp_arch_exec_activite
select t1.*, t3.titre titre_activite, t4.nom nom_job, t4.id_application, t4.id_membre_creation, t3.id_environnement, t5.titre titre_environnement, t6.titre titre_application
from rpapp_exec_activite t1
inner join rpapp_exec_job t2 on t1.id_exec_job=t2.id
inner join rpapp_activite t3 on t1.id_activite=t3.id
inner join rpapp_job t4 on t2.id_job=t4.id
inner join rpapp_environnement t5 on t3.id_environnement=t5.id
inner join rpapp_application t6 on t4.id_application=t6.id
where t1.date_fin < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		if(! $ok)
		{
			echo "Exception Cree Arch. Exec Activites : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		$ok = $bd->RunSql("delete from rpapp_exec_activite where date_fin < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		if(! $ok)
		{
			echo "Exception Suppr Anc. Exec Activites : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		echo " - Executions activites OK".PHP_EOL ;
		$ok = $bd->RunSql("insert into rpapp_arch_exec_job
select t2.*, t4.id_application, t4.nom nom_job, t4.id_membre_creation, t4.reference_job, t6.titre titre_application
from rpapp_exec_job t2
inner join rpapp_job t4 on t2.id_job=t4.id
inner join rpapp_application t6 on t4.id_application=t6.id
where t2.date_fin < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		if(! $ok)
		{
			echo "Exception Suppr Anc. Jobs : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		$ok = $bd->RunSql("delete from rpapp_exec_job where (statut <> 2 and date_fin is null) or date_fin < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		// print_r($bd->LastSqlText) ;
		if(! $ok)
		{
			echo "Exception Suppr Anc. Jobs : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		echo " - Executions Jobs OK".PHP_EOL ;
		$ok = $bd->RunSql("insert into rpapp_arch_info_planif
select t1.*
from rpapp_info_planif t1
where t1.date_action < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		if(! $ok)
		{
			echo "Exception Cree Arch. Infos Planifications : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		$ok = $bd->RunSql("delete from rpapp_info_planif where date_action < date_add(concat(YEAR(NOW()), '-', MONTH(NOW()), '-01'), INTERVAL -".$delaiArch." DAY)") ;
		if(! $ok)
		{
			echo "Exception Suppr Anc. Infos planifications : ".$bd->ConnectionException.PHP_EOL ;
			return ;
		}
		echo " - Infos planifications OK".PHP_EOL ;
		$repRpts = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR .\Rpa2p\Config\Chemin::REPORTS) ;
		echo "Suppression des logs : ".$repRpts.PHP_EOL ;
		if($repRpts != "")
		{
			if(\Rpa2p\Application::TypeOS() == "WINDOWS")
			{
				shell_exec('ForFiles /p "'.$repRpts.'" /s /d -'.($delaiArch + 1).' /c "cmd /c del @file') ;
			}
		}
		else
		{
			echo "Repertoire REPORTS inexistant, veuillez verifier." ;
		}
		echo "Archivage reussie." ;
	}
}
