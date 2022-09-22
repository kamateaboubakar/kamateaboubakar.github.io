<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class EditServeurAD extends \Pv\ZoneWeb\Script\Script
{
	public $MaxFiltresEditionParLigne = 1 ;
	public $NomParamId = "id" ;
	public $NomParamIdEdit = "id" ;
	public $NomParamHote = "host" ;
	public $NomParamPort = "port" ;
	public $NomParamDomain = "domain" ;
	public $NomParamDn = "dn" ;
	public $NomParamUseV3 = "use_protocol_v3" ;
	public $NomParamFollowRefs = "follow_referrals" ;
	public $NomParamActif = "active" ;
	public $MsgHoteInvalide = "Mauvais format pour l'hote" ;
	public $MsgServeurADSimilaire = "Une connexion AD avec le m&ecirc;me hote/Dn existe d&eacute;j&agrave;" ;
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
		$this->FiltreId = $this->FormPrinc->InsereFltLgSelectHttpGet($this->NomParamId, $bd->EscapeVariableName($membership->IdADServerColumn).' = <self>') ;
		if($scriptListe->AfficherId && (count($scriptListe->PrivilegesAfficherId) == 0 || $this->PossedePrivileges($scriptListe->PrivilegesAfficherId)))
		{
			$this->FiltreIdEdit = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamIdEdit, $membership->IdADServerColumn) ;
			$this->FiltreIdEdit->Libelle = strtoupper($membership->IdADServerLabel) ;
			$this->FiltreIdEdit->EstEtiquette = true ;
			$this->FiltreIdEdit->NePasLierColonne = true ;
		}
		$this->FiltreHote = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamHote, $membership->HostADServerColumn) ;
		$this->FiltreHote->Libelle = $membership->HostADServerLabel ;
		$this->FiltrePort = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamPort, $membership->PortADServerColumn) ;
		$this->FiltrePort->Libelle = $membership->PortADServerLabel ;
		$this->FiltreDomain = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamDomain, $membership->DomainADServerColumn) ;
		$this->FiltreDomain->Libelle = $membership->DomainADServerLabel ;
		$this->FiltreDn = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamDn, $membership->DnADServerColumn) ;
		$this->FiltreDn->Libelle = $membership->DnADServerLabel ;
		$this->FiltreUseV3 = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamUseV3, $membership->UseProtocolV3ADServerColumn) ;
		$this->FiltreUseV3->Libelle = $membership->UseProtocolV3ADServerLabel ;
		$this->FiltreUseV3->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreUseV3->ValeurParDefaut = $membership->UseProtocolV3ADServerDefaultValue ;
		$this->FiltreFollowRefs = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamFollowRefs, $membership->FollowReferralsADServerColumn) ;
		$this->FiltreFollowRefs->Libelle = $membership->FollowReferralsADServerLabel ;
		$this->FiltreFollowRefs->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreFollowRefs->ValeurParDefaut = $membership->FollowReferralsADServerDefaultValue ;
		$this->FiltreActif = $this->FormPrinc->InsereFltEditHttpPost($this->NomParamActif, $membership->EnableADServerColumn) ;
		$this->FiltreActif->Libelle = $membership->EnableADServerLabel ;
		$this->FiltreActif->DeclareComposant('\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool') ;
		$this->FiltreActif->ValeurParDefaut = 1 ;
		$this->ChargeFormPrinc() ;
		$this->FormPrinc->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
		$this->FormPrinc->FournisseurDonnees->BaseDonnees = & $bd ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = $membership->ADServerTable ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = $membership->ADServerTable ;
		if($this->FormPrinc->Editable == true)
		{
			$this->CritrNonVide = $this->FormPrinc->CommandeExecuter->InsereCritereNonVide(array($this->NomParamHote, $this->NomParamDomain)) ;
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
			$sqlSimil = 'select count(0) TOTAL from '.$bd->EscapeTableName($membership->ADServerTable).' t1 where ('.$bd->EscapeVariableName($membership->HostADServerColumn).'=:host and '.$bd->EscapeVariableName($membership->PortADServerColumn).'=:port and '.$bd->EscapeVariableName($membership->DnADServerColumn).'=:dn) and id <> :id' ;
			$totalSimil = $bd->FetchSqlValue(
				$sqlSimil,
				array(
					'host' => $this->FiltreHote->Lie(),
					'port' => $this->FiltrePort->Lie(),
					'dn' => $this->FiltreDn->Lie(),
					'id' => ($this->FormPrinc->InclureElementEnCours == true) ? $this->FiltreId->Lie() : 0,
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
				$critere->MessageErreur = $this->MsgServeurADSimilaire ;
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
