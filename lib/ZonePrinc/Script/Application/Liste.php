<?php

namespace Rpa2p\ZonePrinc\Script\Application ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $TitreDocument = "Liste des applications" ;
	public $Titre = "Liste des applications" ;
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
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("nom_rech", "instr(reference_app, <self>) > 0 or instr(titre, <self>) > 0") ;
		$flt->Libelle = "Titre" ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefCol("reference_app", "Reference") ;
		$this->TablPrinc->InsereDefCol("titre", "Titre") ;
		$this->TablPrinc->InsereDefColDetail("description", "Description") ;
		$acts = $this->TablPrinc->InsereDefColActions('Actions') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifApp&id=${id}\')', 'Modifier') ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		if($this->TablPrinc->IDInstanceCalc == $composant->IDInstanceCalc)
		{
			return '' ;
		}
		return '' ;
	}
	public function RenduLiensPrinc()
	{
		$ctn = '' ;
		$ctn .= '<div class="card">
<div class="card-header">
<a class="btn btn-primary" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutApp\') ;">Ajouter</a>
</div>
</div>
<br>' ;
		return $ctn ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->RenduLiensPrinc() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
