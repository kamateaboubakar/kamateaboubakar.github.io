<?php

namespace Rpa2p\ZonePrinc\Script\ExecJob ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $ActiverAutoRafraich = true ;
	public $DelaiAutoRafraich = 30 ;
	public $TitreDocument = "Suivi des Jobs" ;
	public $Titre = "Suivi des Jobs" ;
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
select t2.*, case when t2.statut = 2 then 0 else 1 end termine, t4.id_application, t4.nom nom_job, t4.id_membre_creation, t6.titre titre_application
from rpapp_exec_job t2
inner join rpapp_job t4 on t2.id_job=t4.id
inner join rpapp_application t6 on t4.id_application=t6.id
)" ;
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
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("application", "id_application = <self>") ;
		$flt->Libelle = "Application" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->InclureElementHorsLigne = true ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_debut", "date(date_debut) >= <self>") ;
		$flt->Libelle = "Date debut" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_fin", "date(date_debut) <= <self>") ;
		$flt->Libelle = "Date fin" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefColCachee("termine") ;
		$this->TablPrinc->AccepterTriColonneInvisible = true ;
		$this->TablPrinc->SensColonneTri = "desc" ;
		$this->TablPrinc->InsereDefCol("titre_application", "Application") ;
		$this->TablPrinc->InsereDefCol("nom_job", "Job") ;
		$this->TablPrinc->InsereDefCol("total_succes", "Succ&egrave;s") ;
		$this->TablPrinc->InsereDefCol("total_echecs", "Echecs") ;
		$this->TablPrinc->InsereDefColDateTimeFr("date_debut", "Debut") ;
		$this->TablPrinc->InsereDefColDateTimeFr("date_fin", "Fin") ;
		$this->TablPrinc->InsereDefColChoix("statut", "Statut", "", array(
			"0" => '<span class="text-danger">Echec</span>',
			"1" => '<span class="text-success">Ok</span>',
			"2" => '<span class="text-info">En cours</span>',
		)) ;
		$acts = $this->TablPrinc->InsereDefColActions('Actions') ;
		$lienRpt = $this->TablPrinc->InsereLienAction($acts, '?appelleScript=rapportExecJob&id=${id}', '<i class="fas fa-file-alt"></i> Rapport') ;
		$lienRpt->NomDonneesValid = "termine" ;
		$lienRpt->ClasseCSS = "btn btn-info" ;
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		$lgn = $ligneDonnees ;
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
