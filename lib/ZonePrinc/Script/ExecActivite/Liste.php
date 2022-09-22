<?php

namespace Rpa2p\ZonePrinc\Script\ExecActivite ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $ActiverAutoRafraich = true ;
	public $DelaiAutoRafraich = 30 ;
	public $TitreDocument = "Suivi des executions" ;
	public $Titre = "Suivi des executions" ;
	public $EstScriptSession = true ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
		$this->CtnJsFermModalPrinc = 'ActualiseFormulaire'.$this->TablPrinc->IDInstanceCalc.'()' ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->TablPrinc->AppliqueScriptParentValsSuppl() ;
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "(
select t1.*, '' details, t3.titre titre_activite, t4.nom nom_job, t3.id_environnement, t5.titre titre_environnement, t4.id_application, t6.titre titre_application
from rpapp_exec_activite t1
inner join rpapp_exec_job t2 on t1.id_exec_job=t2.id
inner join rpapp_activite t3 on t1.id_activite=t3.id
inner join rpapp_job t4 on t2.id_job=t4.id
inner join rpapp_environnement t5 on t3.id_environnement=t5.id
inner join rpapp_application t6 on t4.id_application=t6.id
)" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("titre_rech", "instr(titre_activite, <self>) > 0") ;
		$flt->Libelle = "Titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("job", "id_job = <self>") ;
		$flt->Libelle = "Job" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->InclureElementHorsLigne = true ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "(select t1.id, concat(t1.nom , '@', t2.titre) nom_complet from rpapp_job t1
inner join rpapp_application t2 on t1.id_application=t2.id
order by t1.nom)" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "nom_complet" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_debut", "date(date_debut) >= <self>") ;
		$flt->Libelle = "Date debut" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_fin", "date(date_debut) <= <self>") ;
		$flt->Libelle = "Date fin" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("application", "id_application = <self>") ;
		$flt->Libelle = "Application" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->InclureElementHorsLigne = true ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("environnement", "id_environnement = <self>") ;
		$flt->Libelle = "Environnement" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->InclureElementHorsLigne = true ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_environnement" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefColCachee("details") ;
		$this->TablPrinc->InsereDefColCachee("contenu_brut") ;
		$this->TablPrinc->AccepterTriColonneInvisible = true ;
		$this->TablPrinc->SensColonneTri = "desc" ;
		$this->TablPrinc->InsereDefCol("titre_application", "Application") ;
		$this->TablPrinc->InsereDefCol("nom_job", "Job") ;
		$this->TablPrinc->InsereDefCol("titre_activite", "Activt&eacute;") ;
		$this->TablPrinc->InsereDefCol("titre_environnement", "Environnement") ;
		$this->TablPrinc->InsereDefColDateTimeFr("date_fin", "Date") ;
		$this->TablPrinc->InsereDefColChoix("statut", "Statut", "", array(
			"0" => '<span class="text-danger">Echec</span>',
			"1" => '<span class="text-success">Ok</span>',
			"2" => '<span class="text-info">En cours</span>',
		)) ;
		$this->TablPrinc->InsereDefCol("delai", "D&eacute;lai (s)") ;
		$defCol = $this->TablPrinc->InsereDefColDetail('details', "D&eacute;tails") ;
		/*
		$acts = $this->TablPrinc->InsereDefColActions('Actions') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=ajoutActivite&id_job=${id}\')', 'Créer activité') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=demarreJob&id=${id}\')', 'Démarrer') ;
		$this->TablPrinc->InsereLienAction($acts, '?appelleScript=listeActivitesJob&id=${id}', 'Activités') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifJob&id=${id}\')', 'Modifier') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifPlanifJob&id=${id}\')', 'Planification') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifMailJob&id=${id}\')', 'Mail') ;
		*/
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		$lgn = $ligneDonnees ;
		if($lgn["contenu_brut"] != "")
		{
			$outFile = new \Alk\RobotFramework\Output() ;
			@$outFile->loadXml($lgn["contenu_brut"]) ;
			$tests = $outFile->tests() ;
			$ctnDetails = "" ;
			$ctnKw = "" ;
			foreach($tests as $i => $test)
			{
				if($ctnKw != '')
				{
					$ctnKw .= "; " ;
				}
				$ctnKw .= ''.htmlentities($test->name).' : '.(($test->status == 'PASS') ? 'OK' : 'NOK').' ('.$test->elapsed.' sec)' ;
			}
			$ctnDetails = $ctnKw ;
			if($ctnDetails != '' && $outFile->errorMessage != '')
			{
				$ctnDetails .= "; " ;
			}
			if($outFile->errorMessage != '')
			{
				$ctnDetails .= htmlentities($outFile->errorMessage) ;
			}
			$lgn["details"] = $ctnDetails ;
		}
		else
		{
			$lgn["details"] = "-- Details non renseignes --" ;
		}
		return $lgn ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		if($this->TablPrinc->IDInstanceCalc == $composant->IDInstanceCalc)
		{
			return '' ;
		}
		return '' ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->RenduLiensPrinc() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
