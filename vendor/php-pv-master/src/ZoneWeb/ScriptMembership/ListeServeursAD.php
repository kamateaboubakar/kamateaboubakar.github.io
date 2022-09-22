<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ListeServeursAD extends \Pv\ZoneWeb\Script\Script
{
	public $TitreDocument = "Liste des connexions AD" ;
	public $Titre = "Liste des connexions AD" ;
	public $AfficherId = true ;
	public $PrivilegesAfficherId = array() ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$membership = & $this->ZoneParent->Membership ;
		$bd = & $membership->Database ;
		$this->TablPrinc = $this->InsereTablPrinc() ;
		$this->TablPrinc->AdopteScript("tablPrinc", $this) ;
		$this->TablPrinc->ChargeConfig() ;
		if($this->AfficherId == 0 || (count($this->PrivilegesAfficherId) > 0 && $this->PossedePrivileges($this->PrivilegesAfficherId)))
		{
			$this->DefColId = $this->TablPrinc->InsereDefColCachee($membership->IdADServerColumn) ;
		}
		else
		{
			$this->DefColId = $this->TablPrinc->InsereDefCol($membership->IdADServerColumn, strtoupper($membership->IdADServerColumn)) ;
		}
		$this->FiltreHote = $this->TablPrinc->InsereFltSelectHttpGet("hote_rech", $bd->SqlIndexOf($membership->HostADServerColumn, '<self>')." > 0") ;
		$this->FiltreHote->Libelle = $membership->HostADServerLabel ;
		$this->ChargeFiltresPrinc() ;
		$this->DefColHote = $this->TablPrinc->InsereDefCol($membership->HostADServerColumn, $membership->HostADServerLabel) ;
		$this->DefColPort = $this->TablPrinc->InsereDefCol($membership->PortADServerColumn, $membership->PortADServerLabel) ;
		$this->DefColDomaine = $this->TablPrinc->InsereDefCol($membership->DomainADServerColumn, $membership->DomainADServerLabel) ;
		$this->DefColDn = $this->TablPrinc->InsereDefCol($membership->DnADServerColumn, $membership->DnADServerLabel) ;
		$this->DefColActif = $this->TablPrinc->InsereDefColBool($membership->EnableADServerColumn, $membership->EnableADServerLabel) ;
		$this->DefColActif->AlignElement = "center" ;
		$this->ChargeDefsColPrinc() ;
		$this->DefColActs = $this->TablPrinc->InsereDefColActions($this->ZoneParent->EnteteActionsTablPrinc) ;
		$this->LienModif = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptModifServeurAD->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleModif
		) ;
		$this->ChargeLiensActPrinc() ;
		$this->LienSuppr = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptSupprServeurAD->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleSuppr
		) ;
		$this->TablPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = $membership->ADServerTable;
		$this->TablPrinc->FournisseurDonnees->BaseDonnees = & $bd ;
	}
	protected function ChargeFiltresPrinc()
	{
	}
	protected function ChargeDefsColPrinc()
	{
	}
	protected function ChargeLiensActPrinc()
	{
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= parent::RenduSpecifique() ;
		$ctn .= $this->TablPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
