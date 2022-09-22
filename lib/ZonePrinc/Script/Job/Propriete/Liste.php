<?php

namespace Rpa2p\ZonePrinc\Script\Job\Propriete ;

class Liste extends \Rpa2p\ZonePrinc\Script\Job\ModPart
{
	public $TitreDocument = "Propriétés du Job" ;
	public $Titre = "Propriétés du Job" ;
	public function DetermineEnvironnement()
	{
		$this->ParamId = intval((isset($_GET["id"])) ? $_GET["id"] : 0) ;
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->ChargeConfig() ;
		$this->TablPrinc->ToujoursAfficher = true ;
		$this->CtnJsFermModalPrinc = 'ActualiseFormulaire'.$this->TablPrinc->IDInstanceCalc.'()' ;
		$this->TablPrinc->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
		$this->TablPrinc->AppliqueScriptParentValsSuppl() ;
		$this->TablPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = "rpapp_propriete_job" ;
		$flt = $this->TablPrinc->InsereFltSelectFixe("id_job", $this->ParamId, "id_job = <self>") ;
		if($this->PossedePrivilege("consult_infos_systeme"))
		{
			$this->TablPrinc->InsereDefCol("id", "ID") ;
		}
		else
		{
			$this->TablPrinc->InsereDefColCachee("id") ;
		}
		$defCol = $this->TablPrinc->InsereDefCol('valeur', "Valeur") ;
		$acts = $this->TablPrinc->InsereDefColActions("Actions") ;
		$lien1 = $this->TablPrinc->InsereLienAction($acts, 'javascript:ouvreUrlModal(\'?appelleScript=modifProprieteJob&id=${id}\')', '<i class="fa fa-edit"></i> Modifier') ;
		$lien1->ClasseCSS = "btn btn-primary" ;
		$this->TablPrinc->ParamsGetSoumetFormulaire[] = "id" ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= parent::RenduSpecifique() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
