<?php

namespace Rpa2p\Job\TypeActivite ;

class ConsolideJob extends TypeActivite
{
	public $NomsFiltreEdit = array("date_min") ;
	public function Id()
	{
		return 'consolide_job' ;
	}
	public function Titre()
	{
		return 'Consolidé des jobs exécutés' ;
	}
	public function ClasseFa()
	{
		return 'fa fa-list' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$this->FltDateMin = $form->InsereFltEditHttpPost("date_min", "") ;
		$this->FltDateMin->Libelle = "Date min" ;
		$this->FltDateMin->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate()) ;
		$this->FltNvErr = $form->InsereFltEditHttpPost("niveau_alerte", "") ;
		$this->FltNvErr->Libelle = "Statut d'exécution" ;
		$comp = $this->FltNvErr->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->FournisseurDonnees = new \Pv\FournisseurDonnees\Direct() ;
		$comp->FournisseurDonnees->Valeurs["niveaux"] = array(
			array("id" => "0", "lib" => "Tous"),
			array("id" => "2", "lib" => "Echecs"),
			array("id" => "1", "lib" => "Succès"),
		) ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "lib" ;
		$this->FltProp = $form->InsereFltEditHttpPost("id_propriete", "") ;
		$this->FltProp->Libelle = "Propriété filtre" ;
		$comp = $this->FltProp->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->FournisseurDonnees = $form->ApplicationParent->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_propriete" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom" ;
		$comp->InclureElementHorsLigne = true ;
		$this->FltValProp = $form->InsereFltEditHttpPost("valeur_propriete", "") ;
		$this->FltValProp->Libelle = "Valeur filtre" ;
		$comp = $this->FltValProp->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne()) ;
		$this->FltSujet = $form->InsereFltEditHttpPost("sujet_mail", "") ;
		$this->FltSujet->Libelle = "Sujet du mail" ;
		$this->FltTo = $form->InsereFltEditHttpPost("to_mail", "") ;
		$this->FltTo->Libelle = "Destinataire(s) (séparer par ', ')" ;
		$this->FltCC = $form->InsereFltEditHttpPost("cc_mail", "") ;
		$this->FltCC->Libelle = "Destinataire(s) CC (séparer par ', ')" ;
		if($form->InclureElementEnCours == true)
		{
			$repActivit = \Rpa2p\Config\Chemin::REP_ACTIVITES. DIRECTORY_SEPARATOR . intval($_GET["id"]) ;
			$config = new \StdClass() ;
			if(file_exists($repActivit."/config.json"))
			{
				$config = json_decode(file_get_contents($repActivit."/config.json")) ;
			}
			if(isset($config->dateMin))
			{
				$this->FltDateMin->ValeurParDefaut = $config->dateMin ;
				$this->FltNvErr->ValeurParDefaut = $config->nvErr ;
				$this->FltSujet->ValeurParDefaut = $config->sujetMail ;
				$this->FltProp->ValeurParDefaut = $config->idPropriete ;
				$this->FltValProp->ValeurParDefaut = $config->valPropriete ;
				$this->FltTo->ValeurParDefaut = $config->toMail ;
				$this->FltCC->ValeurParDefaut = $config->ccMail ;
			}
		}
	}
	public function AppliqueActCmdEdit(& $actCmd)
	{
		$cmd = & $actCmd->CommandeParent ;
		$form = & $cmd->FormulaireDonneesParent ;
		if($this->FltDateMin->Invisible == false)
		{
			$config = new \StdClass() ;
			$config->dateMin = ($this->FltDateMin->Lie() == "") ? date("Y-m-h") : $this->FltDateMin->Lie() ;
			$config->nvErr = $this->FltNvErr->Lie() ;
			$config->idPropriete = $this->FltProp->Lie() ;
			$config->valPropriete = $this->FltValProp->Lie() ;
			$config->sujetMail = $this->FltSujet->Lie() ;
			$config->toMail = $this->FltTo->Lie() ;
			$config->ccMail = $this->FltCC->Lie() ;
			if($form->InclureElementEnCours == false)
			{
				$bd = $form->ScriptParent->CreeBdPrinc() ;
				$idActivit = $bd->FetchSqlValue('select max(id) id from rpapp_activite where id_job=:0', array($form->ScriptParent->FltJob->Lie()), "id") ;
				$bd->RunSql("insert into rpapp_consolide_job values (:0, :1)", array($idActivit, $config->dateMin." 00:00:00")) ;
			}
			else
			{
				$idActivit = $form->ScriptParent->FltId->Lie() ;
			}
			$repActivit = \Rpa2p\Config\Chemin::REP_ACTIVITES. DIRECTORY_SEPARATOR . $idActivit ;
			if(! is_dir($repActivit))
			{
				mkdir($repActivit) ;
			}
			$chemConfig = $repActivit. DIRECTORY_SEPARATOR . "config.json" ;
			file_put_contents($chemConfig, json_encode($config)) ;
		}
	}
	public function ExecuteInstructions(& $lgnActivit, & $bd, & $tacheProg)
	{
		$configCtn = file_get_contents($this->RepActivit.DIRECTORY_SEPARATOR ."config.json") ;
		if($configCtn === false || empty($configCtn))
		{
			$this->ResultExec->Statut = "FAIL" ;
			$this->ResultExec->EstSucces = 0 ;
			$this->ResultExec->Delai = 1 ;
			$this->ResultExec->TotalEchecs = 1 ;
			$this->ResultExec->MsgErreur = "Exception config : ".$bd->ConnectionException ;
			$this->ResultExec->ContenuHtml = "Exception config : ".$bd->ConnectionException ;
			return ;
		}
		$config = json_decode(trim($configCtn)) ;
		if(! isset($config->idPropriete))
		{
			$config->idPropriete = 0 ;
			$config->valPropriete = 0 ;
		}
		$dateDernExec = "" ;
		$lgnConsolid = $bd->FetchSqlRow("select date_exec from rpapp_consolide_job where id_activite=:0", array($this->IdActivit)) ;
		if(! is_array($lgnConsolid))
		{
			$this->ResultExec->Statut = "FAIL" ;
			$this->ResultExec->EstSucces = 0 ;
			$this->ResultExec->Delai = 1 ;
			$this->ResultExec->TotalEchecs = 1 ;
			$this->ResultExec->MsgErreur = "Exception Infos consolid : ".$bd->ConnectionException ;
			$this->ResultExec->ContenuHtml = "Exception Infos consolid : ".$bd->ConnectionException ;
			return ;
		}
		else
		{
			$dateDernExec = $lgnConsolid["date_exec"] ;
		}
		$paramsSql = array($dateDernExec) ;
		$sql = "select t2.titre titre_application, t1.id, t1.nom, t1.statut_dern_exec, t1.msg_dern_exec, t1.date_dern_exec, t1.succes_dern_exec, t1.echec_dern_exec
from rpapp_job t1
inner join rpapp_application t2 on t1.id_application=t2.id".PHP_EOL ;
		if($config->idPropriete != "" && $config->idPropriete > 0)
		{
			$sql .= "inner join rpapp_propriete_job prop on t1.id=prop.id_job".PHP_EOL ;
		}
		$sql .= "where t1.actif=1 and t1.date_dern_exec >= :0" ;
		if($config->idPropriete != "" && $config->idPropriete > 0)
		{
			$sql .= " and prop.valeur=:1" ;
			$paramsSql[] = $config->valPropriete ;
		}
		if($config->nvErr != 0) {
			$sql .= " and t1.statut_dern_exec=".intval($config->nvErr) ;
		}
		$sql .= " order by case when statut_dern_exec=1 then 1 else 0 end, t1.date_dern_exec desc" ;
		
		$lgns = $bd->FetchSqlRows($sql, $paramsSql) ;
		$ctnHtml = '' ;
		if(! is_array($lgns))
		{
			$this->ResultExec->Statut = "FAIL" ;
			$this->ResultExec->EstSucces = 0 ;
			$this->ResultExec->Delai = 1 ;
			$this->ResultExec->TotalEchecs = 1 ;
			$this->ResultExec->MsgErreur = "Exception Jobs consolid : ".$bd->ConnectionException ;
			$this->ResultExec->ContenuHtml = "Exception Jobs consolid : ".$bd->ConnectionException ;
		}
		$ctnDetails = '<style>
	table, tr, td, th {
		border:1px solid black ;
	}
</style>
<table width="100%" cellspacing="0" cellpadding="4">
<thead>
	<tr>
		<th>Application</th>
		<th>Job</th>
		<th>Date</th>
		<th>Statut</th>
		<th>Message</th>
	</tr>
</thead>
<tbody>'.PHP_EOL ;
		if(count($lgns) <= 1)
		{
			$ctnDetails .= '<tr>
<td align="center" colspan="5">-- Aucune exécution --</td>
</tr>' ;
			$ctnHtml = '(Aucune exécution)' ;
		}
		else
		{
			foreach($lgns as $i => $lgn2)
			{
				if($lgn2["id"] == $lgnActivit["id_job"])
				{
					continue ;
				}
				$bgColor = ($lgn2["statut_dern_exec"] == 1) ? "#6cff81" : "#ff8b8b" ;
				$msgErr = "" ;
				if(! empty($lgn2["msg_dern_exec"]))
				{
					$argsErr = json_decode($lgn2["msg_dern_exec"]) ;
					if(is_array($argsErr))
					{
						foreach($argsErr as $k => $argErr)
						{
							if($msgErr != "")
							{
								$msgErr .= "; " ;
							}
							$msgErr .= '<b>'.htmlentities($argErr[0]).'</b> : '.htmlentities($argErr[1]) ;
						}
					}
					else
					{
						$msgErr = $argsErr ;
					}
				}
				$ctnDetails .= '<tr>
<td>'.htmlentities($lgn2["titre_application"]).'</td>
<td>'.htmlentities($lgn2["nom"]).'</td>
<td>'.\Pv\Misc::date_time_fr($lgn2["date_dern_exec"]).'</td>
<td style="background-color:'.$bgColor.'">'.htmlentities($lgn2["succes_dern_exec"]." / ".($lgn2["succes_dern_exec"] + $lgn2["echec_dern_exec"])).'</td>
<td>'.$msgErr.'</td>
</tr>' ;
				if($i <= 10)
				{
					if($ctnHtml != '')
					{
						$ctnHtml .= '; ' ;
					}
					$ctnHtml .= htmlentities($lgn2["nom"]).' : '.htmlentities($lgn2["succes_dern_exec"]." / ".($lgn2["succes_dern_exec"] + $lgn2["echec_dern_exec"])) ;
				}
			}
		}
		$ctnDetails .= '</tbody>
</table>' ;
		$this->ResultExec->Statut = "PASS" ;
		$this->ResultExec->EstSucces = 1 ;
		$this->ResultExec->Delai = 1 ;
		$this->ResultExec->TotalSucces = 1 ;
		$this->ResultExec->TotalEchecs = 0 ;
		$this->ResultExec->ContenuHtml = $ctnHtml ;
		// file_put_contents(dirname(__FILE__)."/../../res.html", $ctnHtml) ;
		$exprJobs = 'de tous les jobs' ;
		if($config->nvErr == 1)
		{
			$exprJobs = 'des jobs r&eacute;ussis' ;
		}
		elseif($config->nvErr == 2)
		{
			$exprJobs = 'des jobs &eacute;chou&eacute;s' ;
		}
		$ok = $bd->RunSql("update rpapp_consolide_job set date_exec=now() where id_activite=".$this->IdActivit) ;
		$sujetMail = \Pv\Misc::clean_special_chars($config->sujetMail) ;
		$corpsMail = '<p>Bonjour,</p>
<p>Ci-dessous le consolid&eacute; '.$exprJobs.' depuis le '.date("d/m/y H:i:s", strtotime($dateDernExec)).'.</p>'.PHP_EOL.$ctnDetails.'<p>Cordialement,</p>' ;
		$this->EnvoieMail($sujetMail, $corpsMail, array(), $config->toMail, $config->ccMail) ;
	}
}
