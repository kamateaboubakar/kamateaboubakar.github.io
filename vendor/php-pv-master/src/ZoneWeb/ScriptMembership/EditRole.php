<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class EditRole extends \Pv\ZoneWeb\Script\Script
{
	public $MaxFiltresEditionParLigne = 1 ;
	public $NomParamId = "id" ;
	public $NomParamIdEdit = "id_edit" ;
	public $NomParamCode = "code" ;
	public $NomParamTitre = "titre" ;
	public $NomParamDesc = "description" ;
	public $NomParamProfils = "roles" ;
	public $NomParamActif = "actif" ;
	public $MsgTitreInvalide = "Mauvais format pour le titre" ;
	public $MsgProfilSimilaire = "Un r&ocirc;le avec le m&ecirc;me titre/code existe d&eacute;j&agrave;" ;
	public $MessageSuccesExecuter = '' ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function DetermineFormPrinc()
	{
		$membership = & $this->ZoneParent->Membership ;
		$bd = & $membership->Database ;
		$scriptListe = & $this->ZoneParent->ScriptListeMembres ;
		$this->FormPrinc = $this->InsereFormPrinc() ;
		if($this->MaxFiltresEditionParLigne > 0)
		{
			$this->FormPrinc->MaxFiltresEditionParLigne = $this->MaxFiltresEditionParLigne ;
		}
		$this->InitFormPrinc() ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FiltreId = $this->FormPrinc->InsereFltLgSelectHttpGet($this->NomParamId, $bd->EscapeVariableName($membership->IdRoleColumn).' = <self>') ;
		if($scriptListe->AfficherId && (count($scriptListe->PrivilegesAfficherId) == 0 || $this->PossedePrivileges($scriptListe->PrivilegesAfficherId)))
		{
			$this->FiltreIdEdit = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamIdEdit, $membership->IdRoleColumn) ;
			$this->FiltreIdEdit->Libelle = strtoupper($membership->IdRoleColumn) ;
			$this->FiltreIdEdit->EstEtiquette = true ;
			$this->FiltreIdEdit->NePasLierColonne = true ;
		}
		$this->FiltreCode = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamCode, $membership->NameRoleColumn) ;
		$this->FiltreCode->Libelle = $membership->NameRoleLabel ;
		$this->FiltreTitre = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamTitre, $membership->TitleRoleColumn) ;
		$this->FiltreTitre->Libelle = $membership->TitleRoleLabel ;
		$this->FiltreDesc = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamDesc, $membership->DescriptionRoleColumn) ;
		$this->FiltreDesc->Libelle = $membership->DescriptionRoleLabel ;
		$this->CompDesc = $this->FiltreDesc->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$this->CompDesc->TotalColonnes = 40 ;
		$this->CompDesc->TotalLignes = 6 ;
		$this->FiltreActif = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamActif, $membership->EnableRoleColumn) ;
		$this->FiltreActif->Libelle = $membership->EnableRoleLabel ;
		$this->FiltreActif->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreActif->ValeurParDefaut = $membership->EnableRoleTrueValue ;
		$this->ChargeFormPrinc() ;
		$this->FiltreProfils = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamProfils, '') ;
		$this->FiltreProfils->Libelle = $membership->ProfileListRoleLabel ;
		$this->CompProfils = $this->FiltreProfils->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsCocher') ;
		$this->CompProfils->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->CompProfils->FournisseurDonnees->BaseDonnees = & $bd ;
		if(! $this->FormPrinc->InclureElementEnCours)
		{
			$this->CompProfils->FournisseurDonnees->RequeteSelection = "(".$membership->SqlProfilesForNewRole().")" ;
		}
		else
		{
			$this->CompProfils->FournisseurDonnees->RequeteSelection = "(".$membership->SqlProfilesForRole().")" ;
			// echo $membership->SqlRolesForRole() ;
			$filtreIdProfil = $this->FormPrinc->ScriptParent->CreeFiltreHttpGet("id") ;
			$filtreIdProfil->Obligatoire = 1 ;
			$filtreIdProfil->ExpressionDonnees = 'ROLE_ID = <self>' ;
			$this->CompProfils->FiltresSelection[] = $filtreIdProfil ;
		}
		$this->CompProfils->NomColonneValeur = "PROFILE_ID" ;
		$this->CompProfils->NomColonneLibelle = "PROFILE_TITLE" ;
		$this->CompProfils->NomColonneValeurParDefaut = "PRIVILEGE_ENABLED" ;
		$this->FormPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->FormPrinc->FournisseurDonnees->BaseDonnees = & $bd ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = $membership->RoleTable ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = $membership->RoleTable ;
		if($this->FormPrinc->Editable == true)
		{
			$this->CritrNonVide = $this->FormPrinc->CommandeExecuter->InsereCritereNonVide(array($this->NomParamTitre)) ;
			$this->CritrExecuter = $this->FormPrinc->CommandeExecuter->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideScriptParent()) ;
		}
		$this->FormPrinc->RedirigeAnnulerVersUrl($this->ZoneParent->ScriptListeRoles->ObtientUrl()) ;
	}
	protected function InitFormPrinc()
	{
	}
	protected function ChargeFormPrinc()
	{
	}
	public function ValideCritere(& $critere)
	{
		if($critere->IDInstanceCalc == $this->CritrExecuter->IDInstanceCalc)
		{
			$membership = & $this->ZoneParent->Membership ;
			$bd = & $membership->Database ;
			$sqlSimil = 'select count(0) TOTAL from '.$bd->EscapeTableName($membership->RoleTable).' t1 where ('.$bd->EscapeVariableName($membership->TitleRoleColumn).'=:title) and id <> :id' ;
			$totalSimil = $bd->FetchSqlValue(
				$sqlSimil,
				array(
					'title' => $this->FiltreTitre->Lie(),
					'id' => ($this->FormPrinc->InclureElementEnCours == 1) ? $this->FiltreId->Lie() : 0,
				),
				'TOTAL'
			) ;
			if($totalSimil === NULL)
			{
				$critere->MessageErreur = 'Exception SQL : '.$bd->ConnectionException ;
				return false ;
			}
			elseif($totalSimil > 0)
			{
				$critere->MessageErreur = $this->MsgProfilSimilaire ;
				return false ;
			}
			return true ;
		}
		else
		{
			return parent::ValideCritere($critere) ;
		}
	}
	protected function RattachePrivileges()
	{
		$cmd = & $this->FormPrinc->CommandeSelectionnee ;				if($cmd->StatutExecution != 1 || ($cmd->Mode != 1 && $cmd->Mode != 2))
		{
			return ;
		}
		$this->FiltreProfils->DejaLie = false ;
		$this->FiltreProfils->NePasLierParametre = false ;
		$this->FiltreProfils->Lie() ;
		$membership = & $this->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		$idRole = 0 ;
		if($cmd->Mode == 2)
		{
			$idRole = $this->FiltreId->Lie() ;
			$sql = "DELETE FROM ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." where ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn)." = ".$basedonnees->ParamPrefix."roleId" ;
			$basedonnees->RunSql($sql, array('roleId' => $idRole)) ;
			// $cmd->CaptureExceptionBaseDonnees($basedonnees, __FILE__, __LINE__) ;
			// echo $sql.'<br>' ;
		}
		else
		{
			$sql = "select ".$basedonnees->EscapeFieldName($membership->RoleTable, $membership->IdRoleColumn)." ROLE_ID from ".$basedonnees->EscapeTableName($membership->RoleTable)." LEFT JOIN ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." ON ".$basedonnees->EscapeFieldName($membership->RoleTable, $membership->RolePrivilegeForeignKey)."=".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn)." where ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn)." is null" ;
			$idRole = $basedonnees->FetchSqlValue($sql, array(), "ROLE_ID") ;
			// echo $sql." : ".$idRole ;
			// echo $sql.'<br>' ;
		}
		$sql = "INSERT INTO ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." (".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn).", ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn).", ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->EnablePrivilegeColumn).") select ".$basedonnees->EscapeFieldName($membership->ProfileTable, $membership->ProfilePrivilegeForeignKey).", ".$basedonnees->ParamPrefix."roleId, ".$basedonnees->ParamPrefix."roleEnabled from ".$basedonnees->EscapeTableName($membership->ProfileTable) ;
		$basedonnees->RunSql($sql, array("roleEnabled" => $membership->EnablePrivilegeFalseValue(), "roleId" => $idRole)) ;
		// print "kk : ".$this->FiltreProfils->ValeurBrute ;
		if($this->FiltreProfils->Lie() !== null && $this->FiltreProfils->Lie() !== "")
		{
			$profilsSelect = explode(",", $this->FiltreProfils->Lie()) ;
			foreach($profilsSelect as $i => $valeur)
			{
				$basedonnees->UpdateRow(
					$membership->PrivilegeTable,
					array($membership->EnablePrivilegeColumn => $membership->EnablePrivilegeTrueValue),
					$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn).'='.$basedonnees->ParamPrefix.'roleId and '.$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn).' = '.$basedonnees->ParamPrefix.'profileId',
					array(
						'roleId' => $idRole,
						'profileId' => $valeur
					)
				) ;
			}
		}

	}
	public function RenduSpecifique()
	{
		$ctn = parent::RenduSpecifique() ;
		if($this->FormPrinc->NomCommandeSelectionnee() == $this->FormPrinc->NomCommandeExecuter && $this->FormPrinc->SuccesCommandeSelectionnee() && $this->FormPrinc->Editable == true)
		{
			$this->RattachePrivileges() ;
		}
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
