<?php

namespace Rpa2p\ZonePrinc\Script\ExecJob ;

use \Rpa2p\ZonePrinc\Script ;

class Rapport extends Script\Script
{
	public function EstAccessible()
	{
		$ok = parent::EstAccessible() ;
		if(! $ok)
		{
			return $ok ;
		}
		$this->ParamId = intval(\Pv\Misc::_GET_def("id")) ;
		if($this->ParamId <= 0)
		{
			return false ;
		}
		$this->DetermineLgnPrinc() ;
		$ok= (is_array($this->LgnPrinc) && count($this->LgnPrinc) > 0) ;
		if($ok)
		{
			$this->TitreDocument = "Rapport Job ".$this->LgnPrinc["titre_application"]." / ".$this->LgnPrinc["nom_job"] ;
			$this->Titre = "Rapport Job ".$this->LgnPrinc["titre_application"]." / ".$this->LgnPrinc["nom_job"] ;
		}
		return $ok ;
	}
	public function DetermineLgnPrinc()
	{
		$bd = $this->CreeBdPrinc() ;
		$this->LgnPrinc = $bd->FetchSqlRow('select t1.*, t2.nom nom_job, t3.titre titre_application
from rpapp_exec_job t1
inner join rpapp_job t2 on t1.id_job = t2.id
inner join rpapp_application t3 on t2.id_application=t3.id
where t1.id=:0', array($this->ParamId)) ;
	}
	public function RenduSpecifique()
	{
		$cssStatut = ($this->LgnPrinc["total_echecs"] == 0) ? "success" : "danger" ;
		$chemFichRpt = dirname(__FILE__)."/../../".\Rpa2p\Config\Chemin::REPORTS."/exec_jobs/".$this->ParamId.".html" ;
		$ctn = '' ;
		$ctn .= '<div class="card">
<div class="card-body">
<div class="row p-2">
<div class="col"><b>Statut :</b></div>
<div class="col text-'.$cssStatut.'">'.$this->LgnPrinc["total_succes"].'/'.($this->LgnPrinc["total_succes"] + $this->LgnPrinc["total_echecs"]).' r&eacute;ussi(s)</div>
</div>
<div class="row p-2">
<div class="col"><b>Date debut :</b></div>
<div class="col">'.\Pv\Misc::date_time_fr($this->LgnPrinc["date_debut"]).'</div>
</div>
<div class="row p-2">
<div class="col"><b>Delai :</b></div>
<div class="col">'.(strtotime($this->LgnPrinc["date_fin"]) - strtotime($this->LgnPrinc["date_debut"])).' sec</div>
</div>
</div>
<div class="card-header text-center">
<a class="btn btn-info" href="'.$this->ZoneParent->UrlRedirScriptSession().'">Retour</a>
</div>
</div>
<br>'.PHP_EOL ;
		if(! file_exists($chemFichRpt))
		{
			$ctn .= '<div class="alert alert-danger">Rapport inexistant ou supprim&eacute;</div>' ;
			return $ctn ;
		}
		$ctn .= file_get_contents($chemFichRpt) ;
		return $ctn ;
	}
}
