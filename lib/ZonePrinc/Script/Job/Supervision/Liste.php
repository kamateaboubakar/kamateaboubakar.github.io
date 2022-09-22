<?php

namespace Rpa2p\ZonePrinc\Script\Job\Supervision ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $TitreDocument = "Supervision des jobs" ;
	public $Titre = "Supervision des jobs" ;
	public $ActiverAutoRafraich = true ;
	public $DelaiAutoRafraich = 30 ;
	public $NomDocumentWeb = "ecran_supervision" ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereRepetPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "(select t1.*, case when statut_dern_exec=0 then 'warning' else 'light' end css_statut, case when statut_dern_exec=0 then 'danger' else 'success' end css_text, succes_exec_jour + echec_exec_jour nb_exec_jour, succes_exec_total + echec_exec_total nb_exec_total, t2.titre titre_application, DATE_FORMAT(date_exec_jour,'%d/%m/%Y %H:%i:%s') dern_date_exec
from rpapp_job t1
left join rpapp_application t2 on t1.id_application=t2.id
where date_exec_jour is not null and (succes_exec_total > 0 or echec_exec_total > 0))" ;
		$this->TablPrinc->AccepterTriColonneInvisible = true ;
		$this->TablPrinc->SensColonneTri = "asc" ;
		$this->TablPrinc->InsereDefColCachee("statut_dern_exec") ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefColCachee("reference_job") ;
		$this->TablPrinc->InsereDefColCachee("dern_date_exec") ;
		$this->TablPrinc->InsereDefColCachee("css_statut") ;
		$this->TablPrinc->InsereDefColCachee("css_text") ;
		$this->TablPrinc->InsereDefColCachee("titre_application") ;
		$this->TablPrinc->InsereDefColCachee("succes_exec_jour") ;
		$this->TablPrinc->InsereDefColCachee("succes_exec_total") ;
		$this->TablPrinc->InsereDefColCachee("nb_exec_jour") ;
		$this->TablPrinc->InsereDefColCachee("nb_exec_total") ;
		$this->TablPrinc->InsereDefColCachee("titre_application") ;
		$this->TablPrinc->InsereDefColCachee("titre_application") ;
		$this->TablPrinc->InsereDefColCachee("nom") ;
		$this->TablPrinc->InsereDefColBool("actif") ;
		$this->TablPrinc->ContenuLigneModele = '<div class="col-sm-3 mt-3">
<div class="card bg-${css_statut}">
<div class="card-body">
<h2>${nom}</h2>
<h4>${titre_application}</h4>
</div>
<div class="card-header">
<div class="col"><b>Dernier contr&ocirc;le</b> : <span class="text-primary">${dern_date_exec}</span></div>
<div class="col"><b>Aujourd\'hui</b> : <span class="text-${css_text}">${succes_exec_jour}/${nb_exec_jour}</span></div>
<div class="col"><b>Total</b> : ${succes_exec_total}/${nb_exec_total}</div>
</div>
</div>
</div>' ;
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
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
