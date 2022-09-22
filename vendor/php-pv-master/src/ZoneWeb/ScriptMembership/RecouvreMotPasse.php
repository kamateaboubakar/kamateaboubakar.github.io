<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class RecouvreMotPasse extends \Pv\ZoneWeb\Script\Script
{
	public $Titre = "Mot de passe oubli&eacute;" ;
	public $TitreDocument = "Mot de passe oubli&eacute;" ;
	public $EmailEnvoiRecouvr = '' ;
	public $SujetMailSuccesRecouvr = 'R&eacute;initialisation de mot de passe' ;
	public $CorpsMailSuccesRecouvr = '<p>Votre mot de passe a &eacute;t&eacute; r&eacute;initialis&eacute; avec succ&egrave;s :</p>
<div><b>Login :</b> ${login}</div>
<div><b>Mot de passe :</b> ${motPasse}</div>
<p>Cordialement.</p>' ;
	public $SujetMailDemRecouvr = 'R&eacute;initialisation de mot de passe' ;
	public $CorpsMailDemRecouvr = '<p>Vous avez demand&eacute; de r&eacute;initialiser votre mot de passe.</p>
<p>Veuillez cliquer <a href="${url}">ICI</a> pour confirmer.</p>
<p>Cordialement</p>' ;
	public $MessageSuccesEnvoiMail = "Les instructions &agrave; suivre pour recup&eacute;rer votre mot de passe vous ont &eacute;t&eacute; envoy&eacute;es par mail" ;
	public $MessageErreurEnvoiMail = "Impossible d'envoyer un mail de confirmation." ;
	public $MessageSuccesDansMail = "Votre mot de passe vous a &eacute;t&eacute; envoy&eacute; par mail" ;
	public $MessageSuccesAffiche = "Voici votre nouveau mot de passe : " ;
	public $LibelleRetourConnexion = "Retour &agrave; la page de connexion" ;
	public $MessageErreur = "Invalide Nom d'utilisateur / Email" ;
	public $EnvoiParMail = 0 ;
	public $MaxFiltresEditionParLigne = 1 ;
	public $ConfirmParUrl = 0 ;
	public $NomParamLogin = "login" ;
	public $NomParamEmail = "email" ;
	public $NomParamConfirm = "confirm" ;
	public $MessageConfirm = "" ;
	public $LibelleCmdExecuter = "R&eacute;cup&eacute;rer" ;
	public $MotPasseGenere ;
	public $MessageExceptionRecouvr ;
	protected $DemandeConfirm = 0 ;
	public $LgnMembreRecouvr = array() ;
	public $FiltreLogin ;
	public $FiltreEmail ;
	protected function GenereNouvMotPasse()
	{
		return uniqid() ;
	}
	protected function ExtraitLgnMembre(& $filtres)
	{
		$membership = & $this->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		$sql = "select * from ".$membership->MemberTable.' MEMBER_TABLE where '.$basedonnees->EscapeFieldName('MEMBER_TABLE', $membership->LoginMemberColumn).'='.$basedonnees->ParamPrefix.'Login' ;
		$params = array('Login' => $filtres[0]->Lie()) ;
		if($membership->LoginWithEmail == 0)
		{
			$sql .= ' and '.$basedonnees->EscapeFieldName('MEMBER_TABLE', $membership->EmailMemberColumn).'='.$basedonnees->ParamPrefix.'Email' ;
			$params["Email"] = $filtres[1]->Lie() ;
		}
		$ligneMembre = $basedonnees->FetchSqlRow($sql, $params) ;
		return $ligneMembre ;
	}
	protected function ExtraitEmailMembre($ligneMembre)
	{
		$membership = & $this->ZoneParent->Membership ;
		return ($membership->LoginWithEmail == 1) ? $ligneMembre[$membership->LoginMemberColumn] : $ligneMembre[$membership->EmailMemberColumn] ;
	}
	public function ReinitMotPasse(& $filtres)
	{
		$ligneMembre = $this->ExtraitLgnMembre($filtres) ;
		$ok = 0 ;
		$membership = & $this->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		if(is_array($ligneMembre) && count($ligneMembre) > 0)
		{
			$this->MotPasseGenere = $this->GenereNouvMotPasse() ;
			$ligneMembre["motPasse"] = $this->MotPasseGenere ;
			$ligneMembre["login"] = $ligneMembre[$membership->LoginMemberColumn] ;
			$nouvValeurs = array($membership->PasswordMemberColumn => $this->MotPasseGenere) ;
			if($membership->MustChangePasswordMemberColumn != "")
			{
				$nouvValeurs[$membership->MustChangePasswordMemberColumn] = $membership->MustChangePasswordMemberTrueValue ;
			}
			if($membership->PasswordMemberExpr != "")
			{
				$passwordVal = $basedonnees->ParamPrefix."mot_passe" ;
				if(stripos($membership->PasswordMemberExpr, "<self>") !== false)
				{
					$passwordVal = str_ireplace("<self>", $basedonnees->ExprParamPattern, $membership->PasswordMemberExpr) ;
				}
				else
				{
					$passwordVal = $membership->PasswordMemberExpr."(".$basedonnees->ExprParamPattern.")" ;
				}
				$nouvValeurs[$basedonnees->ExprKeyName] = array(
					$membership->PasswordMemberColumn => $membership->PasswordMemberExpr.'('.$basedonnees->ExprParamPattern.')'
				) ;
			}
			$ok = $basedonnees->UpdateRow(
				$membership->MemberTable,
				$nouvValeurs,
				$membership->IdMemberColumn.' = '.$basedonnees->ParamPrefix.'Id',
				array('Id' => $ligneMembre[$membership->IdMemberColumn])
			) ;
		}
		else
		{
			$ok = 0 ;
		}
		if($ok && $this->EnvoiParMail == 1)
		{
			$email = $this->ExtraitEmailMembre($ligneMembre) ;
			$sujetMail = \Pv\Misc::_parse_pattern($this->SujetMailSuccesRecouvr, $ligneMembre) ;
			$corpsMail = \Pv\Misc::_parse_pattern($this->CorpsMailSuccesRecouvr, $ligneMembre) ;
			\Pv\Misc::send_html_mail($email, $sujetMail, $corpsMail, $this->EmailEnvoiRecouvr) ;
		}
		$this->LgnMembreRecouvr = $ligneMembre ;
		return $ok ;
	}
	public function EnvoiMailConfirm(& $filtres)
	{
		$ligneMembre = $this->ExtraitLgnMembre($filtres) ;
		$ok = 1 ;
		$membership = & $this->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		if(! is_array($ligneMembre) || count($ligneMembre) == 0)
		{
			$ok = 0 ;
		}
		else
		{
			$email = $this->ExtraitEmailMembre($ligneMembre) ;
			$sujetMail = \Pv\Misc::_parse_pattern($this->SujetMailDemRecouvr, $ligneMembre) ;
			$corpsMail = \Pv\Misc::_parse_pattern($this->CorpsMailDemRecouvr, $ligneMembre) ;
			$ok = \Pv\Misc::send_html_mail($email, $sujetMail, $corpsMail, $this->EmailEnvoiRecouvr) ;
			$ok = 1 ;
		}
		return $ok ;
	}
	public function EnvoiMailDem(& $filtres)
	{
		$ligneMembre = $this->ExtraitLgnMembre($filtres) ;
		$ok = 1 ;
		$membership = & $this->ZoneParent->Membership ;
		$basedonnees = & $membership->Database ;
		if(! is_array($ligneMembre) || count($ligneMembre) == 0)
		{
			$ok = 0 ;
		}
		else
		{
			$email = $this->ExtraitEmailMembre($ligneMembre) ;
			$ligneMembre["url"] = $this->ObtientUrl()."&".$this->NomParamLogin."=".urlencode($ligneMembre[$membership->LoginMemberColumn])."&".$this->NomParamEmail."=".urlencode(($membership->LoginWithEmail == 1) ? $ligneMembre[$membership->LoginMemberColumn] : $ligneMembre[$membership->EmailMemberColumn])."&".$this->NomParamConfirm."=1" ;
			$ligneMembre["login"] = $ligneMembre[$membership->LoginMemberColumn] ;
			$sujetMail = \Pv\Misc::_parse_pattern($this->SujetMailDemRecouvr, $ligneMembre) ;
			$corpsMail = \Pv\Misc::_parse_pattern($this->CorpsMailDemRecouvr, $ligneMembre) ;
			// echo $email."<br>".$sujetMail."<br>".$corpsMail."<br>".$this->EmailEnvoiRecouvr."<br>" ;
			$ok = \Pv\Misc::send_html_mail($email, $sujetMail, $corpsMail, $this->EmailEnvoiRecouvr) ;
		}
		return $ok ;
	}
	public function DetermineEnvironnement()
	{
		$this->DetermineConfirm() ;
		$this->DetermineFormPrinc() ;
		parent::DetermineEnvironnement() ;
	}
	protected function DetermineConfirm()
	{
		if($this->ConfirmParUrl == 0 || \Pv\Misc::_GET_def($this->NomParamConfirm) != 1)
		{
			return ;
		}
		$this->DemandeConfirm = 1 ;
		$filtres = array($this->CreeFiltreHttpGet($this->NomParamLogin), $this->CreeFiltreHttpGet($this->NomParamEmail)) ;
		$ok = $this->ReinitMotPasse($filtres) ;
		if($ok)
		{
			if($this->EnvoiParMail == 1)
			{
				$this->MessageConfirm = $this->MessageSuccesDansMail ;
			}
			else
			{
				$this->MessageConfirm = $this->MessageSuccesAffiche.' '.$this->MotPasseGenere ;
			}
		}
		else
		{
			$this->MessageConfirm = $this->MessageErreur ;
		}
	}
	protected function DetermineFormPrinc()
	{
		$membership = & $this->ZoneParent->Membership ;
		$this->FormPrinc = $this->InsereFormPrinc() ;
		if($this->LibelleCmdExecuter != '')
		{
			$this->FormPrinc->LibelleCommandeExecuter = $this->LibelleCmdExecuter ;
		}
		$this->FormPrinc->InscrireCommandeAnnuler = 0 ;
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AppliqueScriptParent' ;
		$this->FormPrinc->InclureElementEnCours = 0 ;
		if($this->MaxFiltresEditionParLigne > 0)
		{
			$this->FormPrinc->MaxFiltresEditionParLigne = $this->MaxFiltresEditionParLigne ;
		}
		$this->FormPrinc->AdopteScript("formPrinc", $this) ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FiltreLogin = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamLogin, "") ;
		$this->FiltreLogin->Libelle = $membership->LoginMemberLabel ;
		$this->FiltreEmail = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamEmail, "") ;
		$this->FiltreEmail->Libelle = $membership->EmailMemberLabel ;
	}
	public function AppliqueCommande(& $cmd)
	{
		switch($cmd->IDInstanceCalc)
		{
			case $this->FormPrinc->CommandeExecuter->IDInstanceCalc :
			{
				$this->AppliqueCmdExecuter($cmd) ;
			}
			break ;
		}
	}
	public function AppliqueCmdExecuter(& $cmd)
	{
		if($this->ConfirmParUrl == 0)
		{
			$ok = $this->ReinitMotPasse($this->FormPrinc->FiltresEdition) ;
			if($ok)
			{
				if($this->EnvoiParMail == 1)
				{
					$cmd->ConfirmeSucces($this->MessageSuccesEnvoiMail) ;
				}
				else
				{
					$cmd->ConfirmeSucces($this->MessageSuccesAffiche.' '.$this->MotPasseGenere) ;
				}
			}
			else
			{
				$cmd->RenseigneErreur($this->MessageErreur) ;
			}
		}
		else
		{
			$ok = $this->EnvoiMailDem($this->FormPrinc->FiltresEdition) ;
			if($ok)
			{
				$cmd->ConfirmeSucces($this->MessageSuccesEnvoiMail) ;
			}
			else
			{
				$cmd->RenseigneErreur($this->MessageErreurEnvoiMail) ;
			}
		}
	}
	public function RenduSpecifique()
	{
		$ctn = "" ;
		if($this->ConfirmParUrl == 1 && $this->MessageConfirm != "")
		{
			$ctn .= '<p>'.$this->MessageConfirm.'</p>' ;
		}
		else
		{
			$ctn .= $this->FormPrinc->RenduDispositif() ;
			$ctn .= '<br />
<p><a href="'.$this->ZoneParent->ScriptConnexion->ObtientUrl().'">'.$this->LibelleRetourConnexion.'</a></p>' ;
		}
		return $ctn ;
	}
}