<?php

namespace Rpa2p\ZonePrinc\Script\Propriete ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $TitreDocument = "Liste des propriétés" ;
	public $Titre = "Liste des propriétés" ;
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
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "rpapp_propriete" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("nom_rech", "instr(nom, <self>) > 0") ;
		$flt->Libelle = "Nom" ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefCol("nom", "Nom") ;
		$this->TablPrinc->InsereDefColDetail("description", "Description") ;
		$acts = $this->TablPrinc->InsereDefColActions('Actions') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifPropriete&id=${id}\')', 'Modifier') ;
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
<a class="btn btn-primary" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutPropriete\') ;">Ajouter</a>
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
