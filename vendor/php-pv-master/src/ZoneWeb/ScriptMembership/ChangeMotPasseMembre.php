<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class ChangeMotPasseMembre extends \Pv\ZoneWeb\Script\Script
{
	public $TitreDocument = "Changer mot de passe membre" ;
	public $Titre = "Changer mot de passe membre" ;
	public $NomParamIdMembre = "id" ;
	public $NomParamLogin = "login" ;
	public $NomParamNouvMotPasse = "nouv_mot_passe" ;
	public $NomParamConfMotPasse = "conf_mot_passe" ;
	public $LibelleIdMembre = "ID" ;
	public $LibelleLogin = "Login" ;
	public $LibelleNouvMotPasse = "Nouveau mot de passe" ;
	public $LibelleConfMotPasse = "Confirmer le nouveau mot de passe" ;
	public $MsgNouvMotPasseVide = "Le nouveau mot de passe ne doit pas rester vide" ;
	public $MsgMotPasseNonConf = "Veuillez confirmer le nouveau mot de passe" ;
	public $MsgConfMotPasseVide = "Veuillez confirmer le nouveau mot de passe" ;
	public $MsgNouvMotPasseInvalide = "Format du nouveau mot de passe invalide" ;
	public $MaxFiltresEditionParLigne = 1 ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function DetermineFormPrinc()
	{
		$membership = & $this->ZoneParent->Membership ;
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->FormPrinc->InclureElementEnCours = true ;
		if($this->MaxFiltresEditionParLigne > 0)
		{
			$this->FormPrinc->MaxFiltresEditionParLigne = $this->MaxFiltresEditionParLigne ;
		}
		$this->FormPrinc->NomClasseCommandeExecuter = '\Pv\ZoneWeb\Commande\AppliqueScriptParent' ;
		$this->FormPrinc->AdopteScript("formPrinc", $this) ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FiltreIdMembre = $this->FormPrinc->InsereFltSelectHttpGet($this->NomParamIdMembre, $membership->IdMemberColumn.'=<self>') ;
		$scriptListe = & $this->ZoneParent->ScriptListeMembres ;
		if($scriptListe->AfficherId && (count($scriptListe->PrivilegesAfficherId) == 0 || $this->PossedePrivileges($scriptListe->PrivilegesAfficherId)))
		{
			$this->FiltreId = $this->FormPrinc->InsereFltEditHttpPost("id", $membership
	->IdMemberColumn) ;
			$this->FiltreId->Libelle = $this->LibelleIdMembre ;
			$this->FiltreId->EstEtiquette = true ;
			$this->FiltreId->NePasLierColonne = true ;
		}
		$this->FiltreLogin = $this->FormPrinc->InsereFltEditHttpPost($this->
NomParamLogin, $membership->LoginMemberColumn) ;
		$this->FiltreLogin->Libelle = $this->LibelleLogin ;
		$this->FiltreLogin->EstEtiquette = true ;
		$this->FiltreLogin->NePasLierColonne = true ;
		$this->FiltreNouvMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamNouvMotPasse, '') ;
		$this->FiltreNouvMotPasse->Libelle = $this->LibelleNouvMotPasse ;
		$this->FiltreNouvMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		$this->FiltreConfMotPasse = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamConfMotPasse, '') ;
		$this->FiltreConfMotPasse->Libelle = $this->LibelleConfMotPasse ;
		$this->FiltreConfMotPasse->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse) ;
		$this->FormPrinc->RedirigeAnnulerVersUrl("?".$this->ZoneParent->NomParamScriptAppele."=".$this->ZoneParent->NomScriptListeMembres) ;
		$this->FormPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->FormPrinc->FournisseurDonnees->BaseDonnees = & $membership->Database ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = $membership->MemberTable ;
	}
	public function AppliqueCommande(& $cmd)
	{
		if($cmd->IDInstanceCalc == $this->FormPrinc->CommandeExecuter->IDInstanceCalc)
		{
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
			$idMembre = $this->FiltreIdMembre->Lie() ;
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
