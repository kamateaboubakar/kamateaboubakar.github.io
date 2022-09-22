<?php

namespace Rpa2p\ZonePrinc\Script\Environnement ;

use \Rpa2p\ZonePrinc\Script ;

class Liste extends Script\Script
{
	public $TitreDocument = "Liste des environnements" ;
	public $Titre = "Liste des environnements" ;
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
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "rpapp_environnement" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("nom_rech", "instr(titre, <self>) > 0") ;
		$flt->Libelle = "Titre" ;
		$flt = $this->TablPrinc->InsereFltSelectHttpGet("en_prod_rech", "en_production=<self>") ;
		$flt->Libelle = "En production" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool) ;
		$comp->InclureElementHorsLigne = true ;
		$this->TablPrinc->InsereDefColCachee("id") ;
		$this->TablPrinc->InsereDefCol("titre", "Titre") ;
		$this->TablPrinc->InsereDefColBool("en_production", "En Production") ;
		$acts = $this->TablPrinc->InsereDefColActions('Actions') ;
		$this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifEnv&id=${id}\')', 'Modifier') ;
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
<a class="btn btn-primary" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutEnv\') ;">Ajouter</a>
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
