<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ListeRoles extends \Pv\ZoneWeb\Script\Script
{
	public $TitreDocument = "Liste des roles" ;
	public $Titre = "Liste des roles" ;
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
			$this->DefColId = $this->TablPrinc->InsereDefColCachee($membership->IdRoleColumn) ;
		}
		else
		{
			$this->DefColId = $this->TablPrinc->InsereDefCol($membership->IdRoleColumn, strtoupper($membership->IdRoleColumn)) ;
		}
		$this->FiltreNom = $this->TablPrinc->InsereFltSelectHttpGet("nom_rech", $bd->SqlIndexOf($membership->NameRoleColumn, '<self>')." > 0") ;
		$this->FiltreNom->Libelle = $membership->NameRoleLabel ;
		$this->FiltreTitre = $this->TablPrinc->InsereFltSelectHttpGet("titre_rech", $bd->SqlIndexOf($membership->TitleRoleColumn, '<self>')." > 0") ;
		$this->FiltreTitre->Libelle = $membership->TitleRoleLabel ;
		$this->ChargeFiltresPrinc() ;
		$this->DefColNom = $this->TablPrinc->InsereDefCol($membership->NameRoleColumn, $membership->NameRoleLabel) ;
		$this->DefColTitre = $this->TablPrinc->InsereDefCol($membership->TitleRoleColumn, $membership->TitleRoleLabel) ;
		$this->DefColActif = $this->TablPrinc->InsereDefColBool($membership->EnableRoleColumn, $membership->EnableRoleLabel) ;
		$this->DefColActif->AlignElement = "center" ;
		$this->DefColDesc = $this->TablPrinc->InsereDefColDetail($membership->DescriptionRoleColumn, $membership->DescriptionRoleLabel) ;
		$this->ChargeDefsColPrinc() ;
		$this->DefColActs = $this->TablPrinc->InsereDefColActions($this->ZoneParent->EnteteActionsTablPrinc) ;
		$this->LienModif = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptModifRole->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleModif
		) ;
		$this->ChargeLiensActPrinc() ;
		$this->LienSuppr = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptSupprRole->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleSuppr
		) ;
		$this->TablPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql ;
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = $membership->RoleTable;
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
