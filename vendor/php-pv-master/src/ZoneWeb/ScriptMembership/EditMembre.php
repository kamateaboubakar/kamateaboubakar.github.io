<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class EditMembre extends \Pv\ZoneWeb\Script\Script
{
	public $MaxFiltresEditionParLigne = 1 ;
	public $NomParamId = "id" ;
	public $NomParamIdEdit = "id_edit" ;
	public $NomParamLogin = "login" ;
	public $NomParamMotPasse = "mot_passe" ;
	public $NomParamEmail = "email" ;
	public $NomParamNom = "nom" ;
	public $NomParamPrenom = "prenom" ;
	public $NomParamProfil = "profil" ;
	public $NomParamActif = "actif" ;
	public $NomParamAdresse = "adresse" ;
	public $NomParamContact = "contact" ;
	public $NomParamADActive = "ad_active" ;
	public $NomParamServeurAD = "serveur_ad" ;
	public $MsgLoginInvalide = "Mauvais format pour le login" ;
	public $MsgMotPasseInvalide = "Mauvais format pour le mot de passe" ;
	public $MsgEmailInvalide = "Mauvais format pour l'email" ;
	public $MsgMembreSimilaire = "Un membre avec le m&ecirc;me login/email existe d&eacute;j&agrave;" ;
	public $MsgSuccesInscription = "Vous avez &eacute;t&eacute; inscrit avec succ&ecirc;s. Vous pouvez vous connecter." ;
	public $MessageSuccesExecuter = '' ;
	public $CibleModification = 1 ;
	public $IdsProfilAcceptes = array() ;
	public $LibelleCmdExecuter = "S'inscrire" ;
	public $IdProfilParDefaut = 0 ;
	public $ValeurActiveParDefaut = 0 ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function DetermineFormPrinc()
	{
		$membership = & $this->ZoneParent->Membership ;
		$bd = & $membership->Database ;
		$this->FormPrinc = $this->InsereFormPrinc() ;
		if($this->MaxFiltresEditionParLigne > 0)
		{
			$this->FormPrinc->MaxFiltresEditionParLigne = $this->MaxFiltresEditionParLigne ;
		}
		$this->InitFormPrinc() ;
		if($this->CibleModification == 2)
		{
			$this->FormPrinc->InclureElementEnCours = true ;
		}
		elseif($this->CibleModification == 3)
		{
			$this->FormPrinc->InclureElementEnCours = false ;
			$this->FormPrinc->MsgExecSuccesCommandeExecuter = $this->MsgSuccesInscription ;
		}
		$this->FormPrinc->ChargeConfig() ;
		if($this->CibleModification != 2)
		{
			$this->FiltreId = $this->FormPrinc->InsereFltLgSelectHttpGet($this->NomParamId, $bd->EscapeVariableName($membership->IdMemberColumn).' = <self>') ;
		}
		else
		{
			$this->FiltreId = $this->FormPrinc->InsereFltLgSelectFixe($this->NomParamId, $this->IdMembreConnecte(), $bd->EscapeVariableName($membership->IdMemberColumn).' = <self>') ;
		}
		if($this->ZoneParent->EditMembresPossible())
		{
			$scriptListe = & $this->ZoneParent->ScriptListeMembres ;
			if($scriptListe->AfficherId && (count($scriptListe->PrivilegesAfficherId) == 0 || $this->PossedePrivileges($scriptListe->PrivilegesAfficherId)))
			{
				$this->FiltreIdEdit = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamIdEdit, $membership->IdMemberColumn) ;
				$this->FiltreIdEdit->Libelle = strtoupper($membership->IdMemberColumn) ;
				$this->FiltreIdEdit->EstEtiquette = true ;
				$this->FiltreIdEdit->NePasLierColonne = true ;
			}
		}
		$this->FiltreLogin = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamLogin, $membership->LoginMemberColumn) ;
		$this->FiltreLogin->Libelle = $membership->LoginMemberLabel ;
		if($this->FormPrinc->InclureElementEnCours == 0)
		{
			$this->FiltreMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamMotPasse, $membership->PasswordMemberColumn) ;
			$this->FiltreMotPasse->Libelle = $membership->PasswordMemberLabel ;
			if($membership->PasswordMemberExpr != '')
			{
				if(stripos($membership->PasswordMemberExpr, "<self>") !== false)
				{
					$this->FiltreMotPasse->ExpressionColonneLiee = str_ireplace('<self>', $membership->Database->ExprParamPattern, $membership->PasswordMemberExpr) ;
				}
				else
				{
					$this->FiltreMotPasse->ExpressionColonneLiee = $membership->PasswordMemberExpr.'(<self>)' ;
				}
			}
			else
			{
				$this->FiltreMotPasse->ExpressionColonneLiee = $membership->PasswordMemberExpr ;
			}
			$this->FiltreMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		}
		if(! $membership->LoginWithEmail)
		{
			$this->FiltreEmail = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamEmail, $membership->EmailMemberColumn) ;
			$this->FiltreEmail->Libelle = $membership->EmailMemberLabel ;
		}
		$this->FiltreNom = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamNom, $membership->LastNameMemberColumn) ;
		$this->FiltreNom->Libelle = $membership->LastNameMemberLabel ;
		$this->FiltrePrenom = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamPrenom, $membership->FirstNameMemberColumn) ;
		$this->FiltrePrenom->Libelle = $membership->FirstNameMemberLabel ;
		$this->FiltreContact = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamContact, $membership->ContactMemberColumn) ;
		$this->FiltreContact->Libelle = $membership->ContactMemberLabel ;
		$this->FiltreAdresse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamAdresse, $membership->AddressMemberColumn) ;
		$this->FiltreAdresse->Libelle = $membership->AddressMemberLabel ;
		if($membership->ADActivatedMemberColumn != '')
		{
			$this->FiltreADActive = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamADActive, $membership->ADActivatedMemberColumn) ;
			$this->FiltreADActive->Libelle = $membership->ADActivatedMemberLabel ;
			$this->FiltreADActive->ValeurParDefaut = $membership->ADActivatedMemberTrueValue ;
			$this->FiltreADActive->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
			$this->FiltreServeurAD = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamServeurAD, $membership->ADServerMemberColumn) ;
			$this->FiltreServeurAD->Libelle = $membership->ADServerMemberLabel ;
			$this->CompServeurAD = $this->FiltreServeurAD->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect') ;
			$this->CompServeurAD->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
			$this->CompServeurAD->FournisseurDonnees->BaseDonnees = & $bd ;
			$this->CompServeurAD->FournisseurDonnees->RequeteSelection = "(select ".$membership->IdADServerColumn." id, ".$bd->SqlConcat(array($membership->HostADServerColumn, "':'", $membership->PortADServerColumn, "'/'", $membership->DnADServerColumn))." label from ".$bd->EscapeTableName($membership->ADServerTable)." t1 where ".$bd->EscapeVariableName($membership->EnableADServerColumn)."=1)" ;
			$this->CompServeurAD->NomColonneValeur = "id" ;
			$this->CompServeurAD->NomColonneLibelle = "label" ;
		}
		$this->FiltreActif = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamActif, $membership->EnableMemberColumn) ;
		$this->FiltreActif->Libelle = $membership->EnableMemberLabel ;
		if($this->CibleModification == 3)
		{
			$this->FiltreActif->ValeurParDefaut = $this->ValeurActiveParDefaut ;
			$this->FiltreActif->Invisible = true ;
		}
		elseif($this->CibleModification == 2)
		{
			$this->FiltreActif->Invisible = true ;
		}
		$this->FiltreActif->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreActif->ValeurParDefaut = $membership->EnableMemberTrueValue ;
		$this->ChargeFormPrinc() ;
		$idsProfil = $this->IdsProfilAcceptes ;
		if($this->IdProfilParDefaut > 0 && ! in_array($this->IdProfilParDefaut, $idsProfil))
		{
			$idsProfil[] = $this->IdProfilParDefaut ;
		}
		if($this->CibleModification == 3 && count($idsProfil) == 1)
		{
			$this->FiltreProfil = $this->FormPrinc->InsereFltEditFixe($this->NomParamProfil, $idsProfil[0], $membership->ProfileMemberColumn) ;
		}
		else
		{
			$this->FiltreProfil = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamProfil, $membership->ProfileMemberColumn) ;
			$this->FiltreProfil->Libelle = $membership->ProfileMemberLabel ;
			$this->CompProfil = $this->FiltreProfil->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect') ;
			$this->CompProfil->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
			$this->CompProfil->FournisseurDonnees->BaseDonnees = & $bd ;
			if($this->CibleModification == 3 && count($idsProfil) > 0)
			{
				$this->CompProfil->FournisseurDonnees->RequeteSelection = '(select * from '.$bd->EscapeTableName($membership->ProfileTable).' where '.$bd->EscapeVariableName($membership->IdProfileColumn).' in ('.join(', ', $idsProfil).'))' ;
			}
			else
			{
				$this->CompProfil->FournisseurDonnees->RequeteSelection = $bd->EscapeTableName($membership->ProfileTable) ;
			}
			if($this->CibleModification == 3 && $this->IdProfilParDefaut != 0)
			{
				$this->FiltreProfil->ValeurParDefaut = $this->IdProfilParDefaut ;
			}
			if($this->CibleModification == 2)
			{
				$this->FiltreProfil->EstEtiquette = true ;
				$this->FiltreProfil->NePasLierColonne = true ;
			}
			$this->CompProfil->NomColonneValeur = $membership->IdProfileColumn ;
			$this->CompProfil->NomColonneLibelle = $membership->TitleProfileColumn ;
		}
		$this->FormPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->FormPrinc->FournisseurDonnees->BaseDonnees = & $bd ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = $membership->MemberTable ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = $membership->MemberTable ;
		if($this->FormPrinc->Editable == true)
		{
			$paramsNonVide = array($this->NomParamLogin, $this->NomParamNom, $this->NomParamPrenom) ;
			if($membership->ADActivatedMemberColumn == '')
			{
				$paramsNonVide[] = $this->NomParamMotPasse ;
			}
			if($this->CibleModification == 1)
			{
				$paramsNonVide[] = $this->NomParamProfil ;
			}
			if(! $membership->LoginWithEmail)
			{
				$paramsNonVide[] = $this->NomParamEmail ;
			}
			$this->CritrNonVide = $this->FormPrinc->CommandeExecuter->InsereCritereNonVide($paramsNonVide) ;
			$this->CritrExecuter = $this->FormPrinc->CommandeExecuter->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideScriptParent()) ;
		}
		if($this->CibleModification == 1)
		{
			$this->FormPrinc->RedirigeAnnulerVersUrl($this->ZoneParent->ScriptListeMembres->ObtientUrl()) ;
		}
		else
		{
			$this->FormPrinc->RedirigeAnnulerVersUrl($this->ZoneParent->ObtientUrl()) ;
		}
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
			if(! \Pv\Misc::validate_name_user_format($this->FiltreLogin->Lie()))
			{
				$critere->MessageErreur = $this->MsgLoginInvalide ;
				return false ;
			}
			if($this->FormPrinc->InclureElementEnCours == false)
			{
				if(($membership->ADServerMemberColumn == '' || $this->FiltreADActive->Lie() == 0) && ! \Pv\Misc::validate_password_format($this->FiltreMotPasse->Lie()))
				{
					$critere->MessageErreur = $this->MsgMotPasseInvalide ;
					return false ;
				}
			}
			if(! \Pv\Misc::validate_email_format($this->FiltreEmail->Lie()))
			{
				$critere->MessageErreur = $this->MsgEmailInvalide ;
				return false ;
			}
			$sqlSimil = 'select count(0) TOTAL from '.$bd->EscapeTableName($membership->MemberTable).' t1 where ('.$bd->EscapeVariableName($membership->LoginMemberColumn).'=:login or '.$bd->EscapeVariableName($membership->EmailMemberColumn).'=:email) and id <> :id' ;
			$totalSimil = $bd->FetchSqlValue(
				$sqlSimil,
				array(
					'login' => $this->FiltreLogin->Lie(),
					'email' => $this->FiltreEmail->Lie(),
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
				$critere->MessageErreur = $this->MsgMembreSimilaire ;
				return false ;
			}
			return true ;
		}
		else
		{
			return parent::ValideCritere($critere) ;
		}
	}
	public function RenduSpecifique()
	{
		$ctn = parent::RenduSpecifique() ;
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
