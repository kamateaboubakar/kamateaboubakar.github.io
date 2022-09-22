<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class Connexion extends \Pv\ZoneWeb\Script\Script
{
	public $Titre = "Connexion" ;
	public $TitreDocument = "Connexion" ;
	public $LibellePseudo = "Nom d'utilisateur" ;
	public $NomParamPseudo = "pseudo" ;
	public $ValeurParamPseudo = "" ;
	public $LibelleMotPasse = "Mot de passe" ;
	public $NomParamMotPasse = "motDePasse" ;
	public $ValeurParamMotPasse = "" ;
	public $NomParamSoumetTentative = "tentativeConnexion" ;
	public $NomClsCSSFormulaireDonnees = "FormulaireConnexion" ;
	public $ValeurParamSoumetTentative = 1 ;
	public $TentativeConnexionEnCours = 0 ;
	public $TentativeConnexionValidee = 0 ;
	public $UrlConnexionReussie = "" ;
	public $UrlConnexionEchouee = "" ;
	public $NomScriptConnexionReussie = "accueil" ;
	public $NomScriptConnexionEchouee = "" ;
	public $MessageConnexionReussie = 'Bienvenue, ${PSEUDO}. Vous vous &ecirc;tes connect&eacute; avec succ&egrave;s' ;
	public $IdMembre = -1 ;
	public $NecessiteMembreConnecte = 0 ;
	public $AfficherBoutonSoumettre = 1 ;
	public $AlignBoutonSoumettre = "center" ;
	public $AfficherMessageErreur = 1 ;
	public $LibelleBoutonSoumettre = "Se connecter" ;
	public $MessageConnexionEchouee = "" ;
	public $MessageErreurValidation = "Nom d'utilisateur / Mot de passe invalide." ;
	public $MessageExceptionValidation = "Une Erreur inconnue est survenue." ;
	public $UtiliserMessageExplicite = 1 ;
	public $MessageMotPasseIncorrect = "Le mot de passe est incorrect" ;
	public $MessageMembreNonTrouve = "Utilisateur non trouv&eacute;" ;
	public $MessageMembreNonActif = "Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute;" ;
	public $MessageAuthADEchoue = "Echec de l'authentification sur le serveur Active Directory" ;
	public $MessageAuthServeurADInaccessible = "Le serveur Active Directory est indisponible" ;
	public $AutoriserUrlsRetour = 0 ;
	public $ValeurUrlRetour = "" ;
	public $NomParamUrlRetour = "urlRetour" ;
	public $ClasseCSSErreur = "" ;
	public $MessageAccesUrlRetour = "Vous devez vous connecter pour avoir acc&egrave;s &agrave; cette page." ;
	public $ParamsUrlInscription = array() ;
	public $ParamsUrlRecouvreMP = array() ;
	public $MessagesErreurValidation = array() ;
	protected function DetecteTentativeConnexion()
	{
		$this->TentativeConnexionEnCours = 0 ;
		if(isset($_POST[$this->NomParamSoumetTentative]))
		{
			$this->TentativeConnexionEnCours = ($_POST[$this->NomParamSoumetTentative] == $this->ValeurParamSoumetTentative) ? 1 : 0 ;
			$this->ValeurParamPseudo = (isset($_POST[$this->NomParamPseudo])) ? $_POST[$this->NomParamPseudo] : "" ;
			$this->ValeurParamMotPasse = (isset($_POST[$this->NomParamMotPasse])) ? $_POST[$this->NomParamMotPasse] : "" ;
		}
		if($this->AutoriserUrlsRetour == 1)
		{
			$this->ValeurUrlRetour = (isset($_GET[$this->NomParamUrlRetour])) ? $_GET[$this->NomParamUrlRetour] : "" ;
			if($this->ValeurUrlRetour != '' && \Pv\Misc::validate_url_format($this->ValeurUrlRetour) == 0)
			{
				$this->ValeurUrlRetour = '' ;
			}
		}
	}
	protected function UrlSoumetTentativeConnexion()
	{
		return $this->ObtientUrl().(($this->AutoriserUrlsRetour == 1) ? '&'.$this->NomParamUrlRetour.'='.urlencode($this->ValeurUrlRetour) : '') ;
	}
	protected function RenduMessageErreur()
	{
		$ctn = '' ;
		$msgErreur = '' ;
		if($this->TentativeConnexionEnCours && $this->TentativeConnexionValidee == 0)
		{
			$msgErreur = $this->MessageConnexionEchouee ;
		}
		elseif($this->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != '')
		{
			$msgErreur = $this->MessageAccesUrlRetour ;
		}
		if($msgErreur != '')
		{
			$ctn .= '<div class="erreur'.(($this->ClasseCSSErreur != '') ? ' '.$this->ClasseCSSErreur : '').'">'.$msgErreur.'</div>'.PHP_EOL ;
		}
		return $ctn ;
	}
	protected function ValideTentativeConnexion()
	{
		return 1 ;
	}
	public function DetermineEnvironnement()
	{
		$this->DetecteTentativeConnexion() ;
		$this->IdMembre = -1 ;
		$this->TentativeConnexionValidee = 0 ;
		if($this->TentativeConnexionEnCours && ! $this->ZoneParent->EstNul($this->ZoneParent->Membership) && $this->ValideTentativeConnexion())
		{
			$this->IdMembre = $this->ZoneParent->Membership->ValidateConnection(trim($this->ValeurParamPseudo), trim($this->ValeurParamMotPasse)) ;
			$this->TentativeConnexionValidee = ($this->IdMembre != $this->ZoneParent->Membership->IdMemberNotFoundValue) ? 1 : 0 ;
			// print_r($this->ZoneParent->Membership->Database) ;
			// print_r($this->IdMembre.' jjj') ;
			// exit ;
		}
		if($this->TentativeConnexionValidee == 1)
		{
			$this->SauveSessionMembre() ;
			$this->RedirigeConnexionReussie() ;
		}
		else
		{
			$url = '' ;
			if($this->NomScriptConnexionEchouee != '' && isset($this->ZoneParent->Scripts[$this->NomScriptConnexionEchouee]))
			{
				$url = $this->ZoneParent->Scripts[$this->NomScriptConnexionEchouee]->ObtientUrl() ;
			}
			elseif($this->UrlConnexionEchouee != '')
			{
				$url = $this->UrlConnexionEchouee ;
			}
			if($url != '')
			{
				$url = \Pv\Misc::update_url_params(array('connexionEchouee' => 1)) ;
				\Pv\Misc::redirect_to($url) ;
			}
			elseif($this->UtiliserMessageExplicite)
			{
				$this->MessageConnexionEchouee = $this->MessageErreurValidation ;
				switch($this->ZoneParent->Membership->LastValidateError)
				{
					case \Pv\Membership\Sql::VALIDATE_ERROR_DB_ERROR :
					{
						$this->MessageConnexionEchouee = 'Exception BD : '.$this->ZoneParent->Membership->Database->ConnectionException ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_MEMBER_NOT_FOUND :
					{
						$this->MessageConnexionEchouee = $this->MessageMembreNonTrouve ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_MEMBER_NOT_ENABLED :
					{
						$this->MessageConnexionEchouee = $this->MessageMembreNonActif ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_PASSWORD_INCORRECT :
					{
						$this->MessageConnexionEchouee = $this->MessageMotPasseIncorrect ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_AD_AUTH_FAILED :
					{
						$this->MessageConnexionEchouee = $this->MessageAuthADEchoue ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_CONNECT_ERROR :
					{
						$this->MessageConnexionEchouee = $this->MessageAuthServeurADInaccessible ;
					}
					break ;
					case \Pv\Membership\Sql::VALIDATE_ERROR_AD_PASSWORD_EMPTY :
					{
						$this->MessageConnexionEchouee = $this->MessageMotPasseIncorrect ;
					}
					break ;
					default :
					{
						if(isset($this->MessagesErreurValidation[$this->ZoneParent->Membership->LastValidateError]))
						{
							$this->MessageConnexionEchouee = $this->MessagesErreurValidation[$this->ZoneParent->Membership->LastValidateError] ;
						}
						else
						{
							$this->MessageConnexionEchouee = $this->MessageExceptionValidation ;
						}
					}
					break ;
				}
			}
			else
			{
				$this->MessageConnexionEchouee = $this->MessageErreurValidation ;
			}
		}
	}
	protected function SauveSessionMembre()
	{
		$this->ZoneParent->Membership->LogonMember($this->IdMembre) ;
	}
	protected function RedirigeConnexionReussie()
	{
		$url = $this->ExtraitUrlConnexionReussie() ;
		if($url != '')
		{
			\Pv\Misc::redirect_to($url) ;
		}
	}
	protected function ExtraitUrlConnexionReussie()
	{
		$url = '' ;
		if($this->AutoriserUrlsRetour == 1 && $this->ValeurUrlRetour != "")
		{
			return $this->ValeurUrlRetour ;
		}
		if($this->NomScriptConnexionReussie != '' && isset($this->ZoneParent->Scripts[$this->NomScriptConnexionReussie]))
		{
			$url = $this->ZoneParent->Scripts[$this->NomScriptConnexionReussie]->ObtientUrl() ;
		}
		elseif($this->UrlConnexionReussie != '')
		{
			$url = $this->UrlConnexionReussie ;
		}
		return $url ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		if($this->TentativeConnexionValidee)
		{
			$message = \Pv\Misc::_parse_pattern(
				$this->MessageConnexionReussie,
				array(
					"PSEUDO" => $this->ValeurParamPseudo
				)
			) ;
			$ctn .= '<p>'.htmlentities($message).'</p>' ;
		}
		else
		{
			$ctn .= parent::RenduDispositifBrut() ;
		}
		return $ctn ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= '<form class="user_login_box '.$this->NomClsCSSFormulaireDonnees.'" action="'.$this->UrlSoumetTentativeConnexion().'" method="post">'.PHP_EOL ;
		$ctn .= '<div align="center">'.PHP_EOL ;
		$ctn .= $this->RenduMessageErreur() ;
		$ctn .= $this->RenduTableauParametres().PHP_EOL ;
		if($this->AfficherBoutonSoumettre)
		{
			$ctn .= '<p align="'.$this->AlignBoutonSoumettre.'"><input type="submit" value="'.$this->LibelleBoutonSoumettre.'" /></p>'.PHP_EOL ;
		}
		$ctn .= '</div>' ;
		$ctn .= '</form>' ;
		return $ctn ;
	}
	public function RenduTableauParametres()
	{
		$ctn = '' ;
		$ctn .= '<table align="center" cellspacing="0" cellpadding="4" class="user_login_form">
	<tr>
		<td width="50%" align="left">
			<label for="'.$this->NomParamPseudo.'">'.$this->LibellePseudo.'</label>
		</td>
		<td width="*" align="left">
			<input type="text" name="'.$this->NomParamPseudo.'" id="'.$this->NomParamPseudo.'" value="'.htmlentities($this->ValeurParamPseudo).'" />
		</td>
	</tr>
	<tr>
		<td align="left">
			<label for="'.$this->NomParamMotPasse.'">'.$this->LibelleMotPasse.'</label>
		</td>
		<td align="left">
			<input type="password" name="'.$this->NomParamMotPasse.'" id="'.$this->NomParamMotPasse.'" value="" />
		</td>
	</tr>
</table>
<input type="hidden" name="'.$this->NomParamSoumetTentative.'" value="'.htmlentities($this->ValeurParamSoumetTentative).'" />' ;
		return $ctn ;
	}
}
