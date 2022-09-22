<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ListeProfils extends \Pv\ZoneWeb\Script\Script
{
	public $TitreDocument = "Liste des profils" ;
	public $Titre = "Liste des profils" ;
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
			$this->DefColId = $this->TablPrinc->InsereDefColCachee($membership->IdProfileColumn) ;
		}
		else
		{
			$this->DefColId = $this->TablPrinc->InsereDefCol($membership->IdProfileColumn, strtoupper($membership->IdProfileColumn)) ;
		}
		$this->FiltreTitre = $this->TablPrinc->InsereFltSelectHttpGet("titre_rech", $bd->SqlIndexOf($membership->TitleProfileColumn, '<self>')." > 0") ;
		$this->FiltreTitre->Libelle = $membership->TitleProfileLabel ;
		$this->ChargeFiltresPrinc() ;
		$this->DefColTitre = $this->TablPrinc->InsereDefCol($membership->TitleProfileColumn, $membership->TitleProfileLabel) ;
		$this->DefColActif = $this->TablPrinc->InsereDefColBool($membership->EnableProfileColumn, $membership->EnableProfileLabel) ;
		$this->DefColActif->AlignElement = "center" ;
		$this->DefColDesc = $this->TablPrinc->InsereDefColDetail($membership->DescriptionProfileColumn, $membership->DescriptionProfileLabel) ;
		$this->ChargeDefsColPrinc() ;
		$this->DefColActs = $this->TablPrinc->InsereDefColActions($this->ZoneParent->EnteteActionsTablPrinc) ;
		$this->LienModif = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptModifProfil->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleModif
		) ;
		$this->ChargeLiensActPrinc() ;
		$this->LienSuppr = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptSupprProfil->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleSuppr
		) ;
		$this->TablPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = $membership->ProfileTable;
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
