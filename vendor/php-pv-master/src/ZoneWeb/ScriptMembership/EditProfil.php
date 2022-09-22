<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class EditProfil extends \Pv\ZoneWeb\Script\Script
{
	public $MaxFiltresEditionParLigne = 1 ;
	public $NomParamId = "id" ;
	public $NomParamIdEdit = "id_edit" ;
	public $NomParamTitre = "titre" ;
	public $NomParamDesc = "description" ;
	public $NomParamRoles = "roles" ;
	public $NomParamActif = "actif" ;
	public $MsgTitreInvalide = "Mauvais format pour le titre" ;
	public $MsgProfilSimilaire = "Un profil avec le m&ecirc;me titre existe d&eacute;j&agrave;" ;
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
		$this->FiltreId = $this->FormPrinc->InsereFltLgSelectHttpGet($this->NomParamId, $bd->EscapeVariableName($membership->IdProfileColumn).' = <self>') ;
		if($scriptListe->AfficherId && (count($scriptListe->PrivilegesAfficherId) == 0 || $this->PossedePrivileges($scriptListe->PrivilegesAfficherId)))
		{
			$this->FiltreIdEdit = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamIdEdit, $membership->IdProfileColumn) ;
			$this->FiltreIdEdit->Libelle = strtoupper($membership->IdProfileColumn) ;
			$this->FiltreIdEdit->EstEtiquette = true ;
			$this->FiltreIdEdit->NePasLierColonne = true ;
		}
		$this->FiltreTitre = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamTitre, $membership->TitleProfileColumn) ;
		$this->FiltreTitre->Libelle = $membership->TitleProfileLabel ;
		$this->FiltreDesc = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamDesc, $membership->DescriptionProfileColumn) ;
		$this->FiltreDesc->Libelle = $membership->DescriptionProfileLabel ;
		$this->CompDesc = $this->FiltreDesc->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$this->CompDesc->TotalColonnes = 40 ;
		$this->CompDesc->TotalLignes = 6 ;
		$this->FiltreActif = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamActif, $membership->EnableProfileColumn) ;
		$this->FiltreActif->Libelle = $membership->EnableProfileLabel ;
		$this->FiltreActif->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreActif->ValeurParDefaut = $membership->EnableProfileTrueValue ;
		$this->ChargeFormPrinc() ;
		$this->FiltreRoles = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamRoles, '') ;
		$this->FiltreRoles->Libelle = $membership->RoleListProfileLabel ;
		$this->CompRoles = $this->FiltreRoles->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsCocher') ;
		$this->CompRoles->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->CompRoles->FournisseurDonnees->BaseDonnees = & $bd ;
		if(! $this->FormPrinc->InclureElementEnCours)
		{
			$this->CompRoles->FournisseurDonnees->RequeteSelection = "(".$membership->SqlRolesForNewProfile().")" ;
		}
		else
		{
			$this->CompRoles->FournisseurDonnees->RequeteSelection = "(".$membership->SqlRolesForProfile().")" ;
			// echo $membership->SqlRolesForProfile() ;
			$filtreIdProfil = $this->FormPrinc->ScriptParent->CreeFiltreHttpGet("id") ;
			$filtreIdProfil->Obligatoire = 1 ;
			$filtreIdProfil->ExpressionDonnees = 'PROFILE_ID = <self>' ;
			$this->CompRoles->FiltresSelection[] = $filtreIdProfil ;
		}
		$this->CompRoles->NomColonneValeur = "ROLE_ID" ;
		$this->CompRoles->NomColonneLibelle = "ROLE_TITLE" ;
		// $this->CompRoles->NomColonneValeurParDefaut = "PRIVILEGE_ENABLED" ;
		$this->FormPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->FormPrinc->FournisseurDonnees->BaseDonnees = & $bd ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = $membership->ProfileTable ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = $membership->ProfileTable ;
		if($this->FormPrinc->Editable == true)
		{
			$this->CritrNonVide = $this->FormPrinc->CommandeExecuter->InsereCritereNonVide(array($this->NomParamTitre)) ;
			$this->CritrExecuter = $this->FormPrinc->CommandeExecuter->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideScriptParent()) ;
		}
		$this->FormPrinc->RedirigeAnnulerVersUrl($this->ZoneParent->ScriptListeProfils->ObtientUrl()) ;
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
			$sqlSimil = 'select count(0) TOTAL from '.$bd->EscapeTableName($membership->ProfileTable).' t1 where ('.$bd->EscapeVariableName($membership->TitleProfileColumn).'=:title) and id <> :id' ;
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
		$cmd = & $this->FormPrinc->CommandeSelectionnee ;
		if($cmd->StatutExecution != 1 || ($cmd->Mode != 1 && $cmd->Mode != 2))
		{
			return ;
		}
		$this->FiltreRoles->DejaLie = false ;
		$this->FiltreRoles->NePasLierParametre = false ;
		$this->FiltreRoles->Lie() ;
		$membership = & $this->FormPrinc->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		$idProfil = 0 ;
		if($cmd->Mode == 2)
		{
			$idProfil = $this->FiltreId->Lie() ;
			$sql = "DELETE FROM ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." where ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn)." = ".$basedonnees->ParamPrefix."profileId" ;
			$basedonnees->RunSql($sql, array('profileId' => $idProfil)) ;
			// $this->CaptureExceptionBaseDonnees($basedonnees, __FILE__, __LINE__) ;
			// echo $sql.'<br>' ;
		}
		else
		{
			$sql = "select ".$basedonnees->EscapeFieldName($membership->ProfileTable, $membership->IdProfileColumn)." PROFILE_ID from ".$basedonnees->EscapeTableName($membership->ProfileTable)." LEFT JOIN ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." ON ".$basedonnees->EscapeFieldName($membership->ProfileTable, $membership->ProfilePrivilegeForeignKey)."=".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn)." where ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn)." is null" ;
			$idProfil = $basedonnees->FetchSqlValue($sql, array(), "PROFILE_ID") ;
			// echo $sql.'<br>' ;
		}
		$sql = "INSERT INTO ".$basedonnees->EscapeTableName($membership->PrivilegeTable)." (".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn).", ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn).", ".$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->EnablePrivilegeColumn).") select ".$basedonnees->EscapeFieldName($membership->RoleTable, $membership->RolePrivilegeForeignKey).", ".$basedonnees->ParamPrefix."profileId, ".$basedonnees->ParamPrefix."profileEnabled from ".$basedonnees->EscapeTableName($membership->RoleTable) ;
		$basedonnees->RunSql($sql, array("profileEnabled" => $membership->EnablePrivilegeFalseValue(), "profileId" => $idProfil)) ;
		if($this->FiltreRoles->Lie() !== null && $this->FiltreRoles->Lie() !== "")
		{
			$rolesSelect = explode(",", $this->FiltreRoles->Lie()) ;
			foreach($rolesSelect as $i => $valeur)
			{
				$basedonnees->UpdateRow(
					$membership->PrivilegeTable,
					array($membership->EnablePrivilegeColumn => $membership->EnablePrivilegeTrueValue),
					$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->ProfilePrivilegeColumn).'='.$basedonnees->ParamPrefix.'profileId and '.$basedonnees->EscapeFieldName($membership->PrivilegeTable, $membership->RolePrivilegeColumn).' = '.$basedonnees->ParamPrefix.'roleId',
					array(
						'profileId' => $idProfil,
						'roleId' => $valeur
					)
				) ;
				// echo $basedonnees->LastSqlText.' '.print_r($basedonnees->LastSqlParams, true).'<br />' ;
			}
		}
		// echo $sql.'<br>' ;
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
