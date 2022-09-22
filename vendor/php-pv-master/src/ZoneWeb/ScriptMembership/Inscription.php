<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class Inscription extends EditMembre
{
	public $CibleModification = 3 ;
	public $Titre = "Inscription" ;
	public $TitreDocument = "Inscription" ;
	public $Securiser = 0 ;
	public $FiltreCaptcha ;
	public $CompCaptcha ;
	public $NomParamCaptcha = "image_securise" ;
	public $LibelleFiltreCaptcha = "Code de s&eacute;curit&eacute;" ;
	public $LibelleCmdExecuter = "S'inscrire" ;
	public $InclureMsgConnexion = 1 ;
	public $AlignMsgConnexion = "center" ;
	public $FormatMsgConnexion = 'D&eacute;j&agrave; inscrit ? <a href="${url}">Connectez-vous !</a>' ;
	public $ActiverConfirmMail = 0 ;
	public $MsgSuccesConfirmMail = '<b>${login_member}</b>, Votre inscription a &eacute;t&eacute; confirm&eacute;e. Vous pouvez d&eacute;sormais vous connecter sur le site web' ;
	public $EmailEnvoiConfirm = 'inscriptions@localhost' ;
	public $MsgErreurConfirmMail = 'Votre inscription n\'a pas &eacute;t&eacute; confirm&eacute;e. Veuillez v&eacute;rifier dans votre bo&icirc;te mail.' ;
	public $SujetMailConfirm = 'Confirmation inscription membre' ;
	public $CorpsMailConfirm = '<p>Bonjour ${login_member},</p>
<p>Veuillez cliquer sur ce lien pour confirmer votre inscription.</p>
<p><a href="${url}">${url}</a></p>
Cordialement' ;
	public $EnvoiMailSucces = 0 ;
	public $EnvoiMailSuccesConfirm = 0 ;
	public $SujetMailSuccesConfirm = 'Compte ${login_member} confirme' ;
	public $CorpsMailSuccesConfirm = '<p>Bonjour ${login_member},</p>
<p>Votre compte a ete bien confirme. Bienvenue sur notre site web.</p>
Cordialement' ;
	public $MsgSuccesCmdExecuter = 'Votre inscription a &eacute;t&eacute; prise en compte' ;
	public $MsgSuccesEnvoiMailConfirm = 'Veuillez v&eacute;rifier votre bo&icirc;te e-mail pour confirmer votre inscription.' ;
	protected $NomColConfirmMail = "enable_confirm_mail" ;
	protected $NomColCodeConfirmMail = "code_confirm_mail" ;
	protected $_FltConfirmMail ;
	protected $_FltCodeConfirm ;
	protected $NomParamLoginConfirm = "login_confirm" ;
	protected $NomParamCodeConfirm = "code_confirm" ;
	protected $NomParamEmailConfirm = "email_confirm" ;
	protected $DemandeConfirmMail = -1 ;
	protected $MailSuccesConfirmEnvoye = false ;
	protected $LgnMembreConfirm = null ;
	public $Detaille = 1 ;
	protected $ValeurDefautNomMembre = "Utilisateur" ;
	protected $ValeurDefautPrenomMembre = "Sans nom" ;
	protected $ValeurDefautAdresseMembre = "" ;
	protected $ValeurDefautContactMembre = "" ;
	protected $ConfirmMailSoumis = 0 ;
	public $AutoriserUrlsRetour = 0 ;
	public $NomParamUrlRetour = "urlRetour" ;
	public $ValeurUrlRetour = "" ;
	public $ConnecterNouveauMembre = 0 ;
	public $UrlAutoConnexionMembre = "?" ;
	protected function InitFormPrinc()
	{
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\InscriptionMembre' ;
	}
	public function DetermineEnvironnement()
	{
		$this->DetermineUrlRetour() ;
		parent::DetermineEnvironnement() ;
		$this->DetermineConfirm() ;
	}
	protected function DetermineUrlRetour()
	{
		if($this->AutoriserUrlsRetour == 1)
		{
			$this->ValeurUrlRetour = \Pv\Misc::_GET_def($this->NomParamUrlRetour) ;
			if($this->ValeurUrlRetour != '' && \Pv\Misc::validate_url_format($this->ValeurUrlRetour) == 0)
			{
				$this->ValeurUrlRetour = '' ;
			}
		}
	}
	protected function DetermineConfirm()
	{
		if(! $this->DoitConfirmMail() || (! isset($_GET[$this->NomParamLoginConfirm]) || ! isset($_GET[$this->NomParamCodeConfirm]) || ! isset($_GET[$this->NomParamEmailConfirm])))
		{
			return ;
		}
		$this->ConfirmMailSoumis = 1 ;
		$login = $_GET[$this->NomParamLoginConfirm] ;
		$code = $_GET[$this->NomParamCodeConfirm] ;
		$email = $_GET[$this->NomParamEmailConfirm] ;
		$membership = & $this->ZoneParent->Membership ;
		$bd = $membership->Database ;
		$nomColEmail = ($membership->LoginWithEmail == 0) ? $membership->EmailMemberColumn : $membership->LoginMemberColumn ;
		$sql = 'select * from '.$bd->EscapeTableName($membership->MemberTable).' where '.$bd->EscapeFieldName($membership->MemberTable, $membership->LoginMemberColumn).' = :login and '.$bd->EscapeFieldName($membership->MemberTable, $nomColEmail).'= :email and '.$bd->EscapeFieldName($membership->MemberTable, $this->NomColCodeConfirmMail).'= :code and '.$bd->EscapeFieldName($membership->MemberTable, $this->NomColConfirmMail).'=1' ;
		$lgn = $bd->FetchSqlRow($sql, array("login" => $login, "email" => $email, "code" => $code)) ;
		if(is_array($lgn) && count($lgn) > 0)
		{
			$this->LgnMembreConfirm = $lgn ;
			$this->LgnMembreConfirm["login_member"] = $lgn[$membership->LoginMemberColumn] ;
			$email = ($membership->LoginWithEmail == 0) ? $this->LgnMembreConfirm[$membership->EmailMemberColumn] : $this->LgnMembreConfirm[$membership->LoginMemberColumn] ;
			$ok = $bd->UpdateRow(
				$membership->MemberTable,
				array(
					$this->NomColCodeConfirmMail => '',
					$membership->EnableMemberColumn => $membership->EnableMemberTrueValue,
					$this->NomColConfirmMail => 0
				),
				$bd->EscapeFieldName($membership->MemberTable, $membership->LoginMemberColumn).' = :login',
				array("login" => $login)
			) ;
			if($ok)
			{
				$this->DemandeConfirmMail = 1 ;
				if($this->EnvoiMailSuccesConfirm)
				{
					$sujetMail = \Pv\Misc::_parse_pattern($this->SujetMailSuccesConfirm, $this->LgnMembreConfirm) ;
					$corpsMail = \Pv\Misc::_parse_pattern($this->CorpsMailSuccesConfirm, $this->LgnMembreConfirm) ;
					$this->MailSuccesConfirmEnvoye = \Pv\Misc::send_html_mail($email, $sujetMail, $corpsMail, $this->EmailEnvoiConfirm) ;
				}
				if(($this->AutoriserUrlsRetour== 1 && $this->ValeurUrlRetour != '') || $this->ConnecterNouveauMembre == 1)
				{
					$this->AutoConnecteNouveauMembre($this->LgnMembreConfirm[$membership->IdMemberColumn]) ;
				}
				if($this->AutoriserUrlsRetour== 1 && $this->ValeurUrlRetour != '')
				{
					\Pv\Misc::redirect_to($this->ValeurUrlRetour) ;
				}
			}
			else
			{
				$this->DemandeConfirmMail = 0 ;
			}
		}
		else
		{
			$this->DemandeConfirmMail = 0 ;
		}
	}
	public function AutoConnecteNouveauMembre($idMembre)
	{
		$this->ZoneParent->Membership->LogonMember($idMembre) ;
	}
	protected function ChargeFormPrinc()
	{
		$form = & $this->FormPrinc ;
		$membership = & $this->ZoneParent->Membership ;
		parent::ChargeFormPrinc() ;
		if($this->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != '')
		{
			$form->ParamsGetSoumetFormulaire[$this->NomParamUrlRetour] = $this->ValeurUrlRetour ;
		}
		if($this->Securiser)
		{
			$this->FiltreCaptcha = $form->InsereFltEditHttpPost($this->NomParamCaptcha) ;
			$this->FiltreCaptcha->Libelle = $this->LibelleFiltreCaptcha ;
			$this->CompCaptcha = $this->FiltreCaptcha->DeclareComposant("\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCommonCaptcha") ;
		}
		if($this->Detaille == 0)
		{
			$nomFltsOblig = array($this->NomParamLogin, $this->NomParamMotPasse, $this->NomParamEmail, $this->NomParamProfil) ;
			if($membership->ConfirmSetPasswordEnabled == 1)
			{
				$nomFltsOblig[] = "filtreConfirmMotPasseMembre" ;
			}
			foreach($form->FiltresEdition as $i => & $flt)
			{
				if($flt->TypeLiaisonParametre != "get" && $flt->TypeLiaisonParametre != "post")
				{
					continue ;
				}
				if(! in_array($flt->NomParametreLie, $nomFltsOblig))
				{
					$flt->Invisible = 1 ;
				}
			}
			$this->FiltreNom->ValeurParDefaut = $this->ValeurDefautNomMembre ;
			$this->FiltrePrenom->ValeurParDefaut = $this->ValeurDefautPrenomMembre ;
			$this->FiltreAdresse->ValeurParDefaut = $this->ValeurDefautAdresseMembre ;
			$this->FiltreContact->ValeurParDefaut = $this->ValeurDefautContactMembre ;
		}
		if($this->DoitConfirmMail())
		{
			$this->_FltConfirmMail = $form->InsereFltEditFixe("confirm_mail", 1, $this->NomColConfirmMail) ;
			$this->_FltCodeConfirm = $form->InsereFltEditFixe("code_confirm", rand(1000, 9999), $this->NomColCodeConfirmMail) ;
		}
		else
		{
			if($this->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != '')
			{
				$form->RedirigeExecuterVersUrl($this->ValeurUrlRetour) ;
			}
		}
		$form->CommandeExecuter->Libelle = $this->LibelleCmdExecuter ;
		$form->CommandeExecuter->MessageSuccesExecution = $this->MsgSuccesCmdExecuter ;
		if($this->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != '')
		{
			$form->RedirigeAnnulerVersUrl($this->ValeurUrlRetour) ;
		}
		else
		{
			$form->RedirigeAnnulerVersScript($this->ZoneParent->NomScriptConnexion) ;
		}
	}
	public function CodeConfirmMail()
	{
		return $this->_FltCodeConfirm->Lie() ;
	}
	public function DoitConfirmMail()
	{
		return ($this->ActiverConfirmMail && $this->NomColConfirmMail != '' && $this->NomColCodeConfirmMail != '') ;
	}
	public function ValideCritere(& $critere)
	{
		if($critere->IDInstanceCalc == $this->CritrExecuter->IDInstanceCalc)
		{
			$ok = true ;
			if($this->Securiser == true)
			{
				$ok = $this->FiltreCaptcha->Composant->VerifieValeurSoumise($this->FiltreCaptcha->Lie()) ;
			}
			if($ok)
			{
				return parent::ValideCritere($critere) ;
			}
			else
			{
				$critere->MessageErreur = "Le code de s&eacute;curit&eacute; saisi est incorrect" ;
				return false ;
			}
		}
		else
		{
			return parent::ValideCritere($critere) ;
		}
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		if($this->DemandeConfirmMail == -1)
		{
			$ctn = parent::RenduSpecifique() ;
			if($this->InclureMsgConnexion == 1)
			{
				$ctn .= $this->RenduMsgConnexion() ;
			}
		}
		elseif($this->DemandeConfirmMail == 0)
		{
			$ctn .= '<p>'.$this->MsgErreurConfirmMail.'</p>' ;
		}
		else
		{
			$ctn .= '<p>'.\Pv\Misc::_parse_pattern($this->MsgSuccesConfirmMail, $this->LgnMembreConfirm).'</p>' ;
		}
		return $ctn ;
	}
	protected function RenduMsgConnexion()
	{
		$ctn = '' ;
		$paramsUrlCnx = array() ;
		if($this->ZoneParent->ScriptConnexion->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != '')
		{
			$paramsUrlCnx[$this->ZoneParent->ScriptConnexion->NomParamUrlRetour] = $this->ValeurUrlRetour ;
		}
		$params = array(
			'url' => $this->ZoneParent->ScriptConnexion->ObtientUrlParam($paramsUrlCnx),
			'chemin_icone' => $this->ZoneParent->ScriptConnexion->CheminIcone,
			'titre' => $this->ZoneParent->ScriptConnexion->Titre
		) ;
		$ctn .= '<p align="'.$this->AlignMsgConnexion.'">'.\Pv\Misc::_parse_pattern($this->FormatMsgConnexion, $params).'</p>' ;
		return $ctn ;
	}
}
