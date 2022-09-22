<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ListeMembres extends \Pv\ZoneWeb\Script\Script
{
	public $TitreDocument = "Liste des membres" ;
	public $Titre = "Liste des membres" ;
	public $LibelleChangeMP = "Mot de passe" ;
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
		$this->TablPrinc->ChargeConfig() ;
		if($this->AfficherId == 0 || (count($this->PrivilegesAfficherId) > 0 && $this->PossedePrivileges($this->PrivilegesAfficherId)))
		{
			$this->DefColId = $this->TablPrinc->InsereDefColCachee($membership->IdMemberColumn) ;
		}
		else
		{
			$this->DefColId = $this->TablPrinc->InsereDefCol($membership->IdMemberColumn, strtoupper($membership->IdMemberColumn)) ;
		}
		$this->DefColChangeMp = $this->TablPrinc->InsereDefColCachee("CAN_CHANGE_PWD") ;
		$this->ChargeFiltresPrinc() ;
		$this->DefColLogin = $this->TablPrinc->InsereDefCol($membership->LoginMemberColumn, $membership->LoginMemberLabel) ;
		$this->DefColNom = $this->TablPrinc->InsereDefCol($membership->LastNameMemberColumn, $membership->LastNameMemberLabel) ;
		$this->DefColPrenom = $this->TablPrinc->InsereDefCol($membership->FirstNameMemberColumn, $membership->FirstNameMemberLabel) ;
		$this->DefColActif = $this->TablPrinc->InsereDefColBool($membership->EnableMemberColumn, $membership->EnableMemberLabel) ;
		$this->DefColActif->AlignElement = "center" ;
		$this->DefColProfil = $this->TablPrinc->InsereDefCol("MEMBER_PROFILE", $membership->ProfileMemberLabel) ;
		$this->ChargeDefsColPrinc() ;
		$this->DefColActs = $this->TablPrinc->InsereDefColActions($this->ZoneParent->EnteteActionsTablPrinc) ;
		$this->LienModif = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptModifMembre->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleModif
		) ;
		$this->LienChangeMP = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptChangeMPMembre->ObtientUrlFmt(array('id' => '${id}')),
			$this->LibelleChangeMP
		) ;
		$this->LienChangeMP->NomDonneesValid = "CAN_CHANGE_PWD" ;
		$this->ChargeLiensActPrinc() ;
		$this->LienSuppr = $this->TablPrinc->InsereLienAction(
			$this->DefColActs,
			$this->ZoneParent->ScriptSupprMembre->ObtientUrlFmt(array('id' => '${id}')),
			$this->ZoneParent->LibelleSuppr
		) ;
		$this->TablPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql ;
		$exprChangeMpPossible = '1' ;
		if($membership->GuestMemberId > 0)
		{
			$exprChangeMpPossible = $bd->EscapeVariableName($membership->IdMemberColumn).' <> '.intval($membership->GuestMemberId) ;
		}
		if($membership->ADActivatedMemberColumn != '')
		{
			$exprChangeMpPossible .= ' and case when '.$membership->ADActivatedMemberColumn.' <> \''.$membership->ADActivatedMemberTrueValue.'\' then 1 else 0 end' ;
		}
		$this->TablPrinc->FournisseurDonnees->RequeteSelection = '(select t1.*, ('.$exprChangeMpPossible.') CAN_CHANGE_PWD, t2.'.$bd->EscapeVariableName($membership->TitleProfileColumn).' MEMBER_PROFILE from '.$bd->EscapeTableName($membership->MemberTable).' t1
inner join '.$bd->EscapeTableName($membership->ProfileTable).' t2 on t1.'.$bd->EscapeVariableName($membership->ProfileMemberColumn).'=t2.'.$bd->EscapeVariableName($membership->IdProfileColumn).')';
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
