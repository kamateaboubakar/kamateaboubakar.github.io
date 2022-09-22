<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ChangeMotPasse extends \Pv\ZoneWeb\Script\Script
{
	public $NomParamAncMotPasse = "anc_mot_passe" ;
	public $NomParamNouvMotPasse = "nouv_mot_passe" ;
	public $NomParamConfMotPasse = "conf_mot_passe" ;
	public $LibelleAncMotPasse = "Ancien mot de passe" ;
	public $LibelleNouvMotPasse = "Nouveau mot de passe" ;
	public $LibelleConfMotPasse = "Confirmer le nouveau mot de passe" ;
	public $MsgAncMotPasseVide = "Veuillez renseigner l'ancien mot de passe" ;
	public $MsgNouvMotPasseVide = "Le nouveau mot de passe ne doit pas rester vide" ;
	public $MsgMotPasseNonConf = "Veuillez confirmer le nouveau mot de passe" ;
	public $MsgConfMotPasseVide = "Veuillez confirmer le nouveau mot de passe" ;
	public $MsgNouvMotPasseInvalide = "Format du nouveau mot de passe invalide" ;
	public $MsgMotsPasseSimilaires = "Les mots de passes sont identiques" ;
	public $MsgIdentifiantsIncorrects = "Mot de passe incorrect, veuillez r&eacute;essayer." ;
	public $MaxFiltresEditionParLigne = 1 ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function DetermineFormPrinc()
	{
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->FormPrinc->InclureElementEnCours = 0 ;
		if($this->MaxFiltresEditionParLigne > 0)
		{
			$this->FormPrinc->MaxFiltresEditionParLigne = $this->MaxFiltresEditionParLigne ;
		}
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AppliqueScriptParent' ;
		$this->FormPrinc->AdopteScript("formPrinc", $this) ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FiltreAncMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamAncMotPasse, '') ;
		$this->FiltreAncMotPasse->Libelle = $this->LibelleAncMotPasse ;
		$this->FiltreAncMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		$this->FiltreNouvMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamNouvMotPasse, '') ;
		$this->FiltreNouvMotPasse->Libelle = $this->LibelleNouvMotPasse ;
		$this->FiltreNouvMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		$this->FiltreConfMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamConfMotPasse, '') ;
		$this->FiltreConfMotPasse->Libelle = $this->LibelleConfMotPasse ;
		$this->FiltreConfMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		$this->FormPrinc->RedirigeAnnulerVersUrl("?") ;
	}
	public function AppliqueCommande(& $cmd)
	{
		if($cmd->IDInstanceCalc == $this->FormPrinc->CommandeExecuter->IDInstanceCalc)
		{
			if(trim($this->FiltreAncMotPasse->Lie()) == '')
			{
				$cmd->RenseigneErreur($this->MsgAncMotPasseVide) ;
				return ;
			}
			if(trim($this->FiltreNouvMotPasse->Lie()) == '')
			{
				$cmd->RenseigneErreur($this->MsgNouvMotPasseVide) ;
				return ;
			}
			if(trim($this->FiltreConfMotPasse->Lie()) == '')
			{
				$cmd->RenseigneErreur($this->MsgConfMotPasseVide) ;
				return ;
			}
			if($this->FiltreNouvMotPasse->Lie() != $this->FiltreConfMotPasse->Lie())
			{
				$cmd->RenseigneErreur($this->MsgMotPasseNonConf) ;
				return ;
			}
			if(! \Pv\Misc::validate_password_format($this->FiltreNouvMotPasse->Lie()))
			{
				$cmd->RenseigneErreur($this->MsgNouvMotPasseInvalide) ;
				return ;
			}
			$membership = & $this->ZoneParent->Membership ;
			$bd = & $membership->Database ;
			if($this->FiltreAncMotPasse->Lie() == $this->FiltreNouvMotPasse->Lie())
			{
				return ;
			}
			$idMembre = $membership->ValidateConnection($this->LoginMembreConnecte(), $this->FiltreAncMotPasse->Lie()) ;
			if($idMembre > 0)
			{
				$passwordVal = $bd->ParamPrefix."password" ;
				if($membership->PasswordMemberExpr != '')
				{
					if(stripos($membership->PasswordMemberExpr, "<self>") !== false)
					{
						$passwordVal = str_ireplace("<self>", $bd->ParamPrefix."password", $membership->PasswordMemberExpr) ;
					}
					else
					{
						$passwordVal = $membership->PasswordMemberExpr."(".$bd->ParamPrefix."password)" ;
					}
				}
				$ok = $bd->RunSql(
					'update '.$bd->EscapeTableName($membership->MemberTable).' set '.$bd->EscapeVariableName($membership->PasswordMemberColumn).'='.$passwordVal.'
where '.$bd->EscapeVariableName($membership->IdMemberColumn).'=:id',
					array(
						'id' => $idMembre,
						'password' => $this->FiltreNouvMotPasse->Lie(),
					)
				) ;
				if($ok)
				{
					$cmd->ConfirmeSucces() ;
				}
				else
				{
					$cmd->RenseigneErreur('Exception SQL : '.$bd->ConnectionException) ;
				}
			}
			else
			{
				$cmd->RenseigneErreur($this->MsgIdentifiantsIncorrects) ;
			}
		}
		else
		{
			parent::AppliqueCommande($cmd) ;
		}
	}
	public function RenduSpecifique()
	{
		$ctn = parent::RenduSpecifique() ;
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
}
