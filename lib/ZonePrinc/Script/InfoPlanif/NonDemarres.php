<?php

namespace Rpa2p\ZonePrinc\Script\InfoPlanif ;

use \Rpa2p\ZonePrinc\Script ;

class NonDemarres extends Script\Script
{
	public $TitreDocument = "Jobs non démarrés" ;
	public $Titre = "Jobs non démarrés" ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->TablPrinc->AppliqueScriptParentValsSuppl() ;
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "(SELECT t1.*, t2.nom nom_job, t2.id_application, t3.titre titre_application, t4.type_periode, t4.type_notif
FROM `rpapp_queue_non_demar` t1
inner join rpapp_job t2 on t1.id_job=t2.id
inner join rpapp_application t3 on t2.id_application=t3.id
inner join rpapp_planif_job t4 on t1.id_planif_job=t4.id and t1.id_job=t4.id_job)" ;
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
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_debut", "date(date_planif) >= <self>") ;
		$flt->Libelle = "Date debut" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("date_fin", "date(date_planif) <= <self>") ;
		$flt->Libelle = "Date fin" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate) ;
		$this->TablPrinc->SensColonneTri = "desc" ;
		$this->TablPrinc->InsereDefColCachee("id_application") ;
		$this->TablPrinc->InsereDefColCachee("type_notif") ;
		$this->TablPrinc->InsereDefColCachee("type_periode") ;
		$this->TablPrinc->InsereDefColDateTimeFr("date_planif", "Date") ;
		$this->TablPrinc->InsereDefCol("id_planif", "Num. queue") ;
		$this->TablPrinc->InsereDefCol("nom_job", "Job") ;
		$this->TablPrinc->InsereDefCol("titre_application", "Application") ;
		$this->TablPrinc->InsereDefColHtml('${titre_notif}', "Notification") ;
		$this->TablPrinc->InsereDefColHtml('${titre_periode}', "Période") ;
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		$lgn = $ligneDonnees ;
		$lgn["titre_notif"] = $this->ApplicationParent->TypesNotifJob[$ligneDonnees["type_notif"]]->Titre() ;
		$lgn["titre_periode"] = $this->ApplicationParent->TypesPeriodeJob[$ligneDonnees["type_periode"]]->Titre() ;
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
