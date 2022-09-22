<?php

namespace Pv\IHM\Zone ;

class Script extends \Pv\Objet\Objet
{
    /*
     * Zone qui contient le script. Doit etre toujours affectee avec la methode
     * AdopteZone()
     * 
     * @var \Pv\IHM\IHM
     */
	public $ZoneParent ;
	public $ApplicationParent ;
	public $NomElementZone = "" ;
	public $CheminRoute = "" ;
	public $NomIntegrationParent = "" ;
	public $NomZoneAppelDistant = "" ;
	public $ValeurAppel = "" ;
	public $CheminIcone = "" ;
	public $CheminMiniature = "" ;
	public $Titre = "" ;
	public $TitreDocument = "" ;
	public $MessageIndisponible = "" ;
	public $Privileges = array() ;
	public $PrivilegesStricts = 0 ;
	public $NecessiteMembreConnecte = 0 ;
	public $AnnulDetectMemberCnx = 0 ;
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeReferantsSurs() ;
	}
	protected function ChargeReferantsSurs()
	{
	}
	public function EstBienRefere()
	{
		return 1 ;
	}
	public function RapporteRequeteEnvoyee()
	{
		$this->ZoneParent->RapporteRequeteEnvoyee() ;
	}
	public function RapporteException($exception)
	{
		$this->ZoneParent->RapporteException($exception) ;
	}
	public function & IntegrationParent()
	{
		return $this->ApplicationParent->ObtientIntegration($this->NomIntegrationParent) ;
	}
	public function & ZoneAppelDistant()
	{
		$ihm = new \Pv\IHM\IHM() ;
		if($this->NomZoneAppelDistant != '' && isset($this->ApplicationParent->IHMs[$this->NomZoneAppelDistant]))
		{
			$ihm = & $this->ApplicationParent->IHMs[$this->NomZoneAppelDistant] ;
		}
		return $ihm ;
	}
	public function DetermineEnvironnement()
	{
	}
	public function PrepareRendu()
	{
	}
	public function EstDisponible()
	{
		$this->VerifieDisponibilite() ;
		return $this->MessageIndisponible == '' ;
	}
	protected function VerifieDisponibilite()
	{
		
	}
	public function EstAccessible()
	{
		if(! $this->NecessiteMembreConnecte)
		{
			return 1 ;
		}
		return $this->ZoneParent->PossedePrivileges($this->Privileges, $this->PrivilegesStricts) ;
	}
	public function Execute()
	{
	}
	public function AccepteAppel($valeurAppel)
	{
		// $valeurInterneAppel = ($this->ValeurAppel != "") ? $this->ValeurAppel : $this->NomElementZone ;
		$valeurInterneAppel = $this->NomElementZone ;
		// echo $valeurInterneAppel." == ".$valeurAppel."<br>" ;
		return ($valeurInterneAppel == $valeurAppel) ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->ValeurAppel = $this->IDInstance ;
	}
	public function AdopteZone($nom, & $zone)
	{
		$this->ZoneParent = & $zone ;
		$this->NomElementZone = $nom ;
		// print get_class($zone->ApplicationParent).' iii <br>' ;
		$this->ApplicationParent = & $this->ZoneParent->ApplicationParent ;
	}
	public function PossedeMembreConnecte()
	{
		return $this->ZoneParent->PossedeMembreConnecte() ;
	}
	public function SurScriptParDefaut()
	{
		return $this->ZoneParent->SurScriptParDefaut() ;
	}
	public function SurScriptConnecte()
	{
		return $this->ZoneParent->SurScriptConnecte() ;
	}
	public function ObtientMembreConnecte()
	{
		return $this->ZoneParent->ObtientMembreConnecte() ;
	}
	public function EstSuperAdminConnecte()
	{
		return $this->ZoneParent->EstSuperAdminConnecte() ;
	}
	public function MembreSuperAdminConnecte()
	{
		return $this->ZoneParent->MembreSuperAdminConnecte() ;
	}
	public function EditMembresPossible()
	{
		return $this->ZoneParent->EditMembresPossible() ;
	}
	public function EditMembershipPossible()
	{
		return $this->ZoneParent->EditMembershipPossible() ;
	}
	public function IdMembreConnecte()
	{
		return $this->ZoneParent->IdMembreConnecte() ;
	}
	public function LoginMembreConnecte()
	{
		return $this->ZoneParent->LoginMembreConnecte() ;
	}
	public function NomCompletMembreConnecte($inverse=0)
	{
		return $this->ZoneParent->NomCompletMembreConnecte($inverse) ;
	}
	public function AttrMembreConnecte($nomAttr)
	{
		return $this->ZoneParent->AttrMembreConnecte($nomAttr) ;
	}
	public function TitreProfilConnecte()
	{
		return $this->ZoneParent->TitreProfilConnecte() ;
	}
	public function PossedeTousPrivileges()
	{
		return $this->ZoneParent->PossedeTousPrivileges() ;
	}
	public function PossedePrivilege($nomRole, $strict=0)
	{
		return $this->ZoneParent->PossedePrivilege($nomRole, $strict) ;
	}
	public function PossedePrivileges($privileges=array(), $strict=0)
	{
		return $this->ZoneParent->PossedePrivileges($privileges, $strict) ;
	}
	public function DoitChangerMotPasse()
	{
		return $this->ZoneParent->DoitChangerMotPasse($this) ;
	}
	public function PeutChangerMotPasse()
	{
		return $this->ZoneParent->PeutChangerMotPasse() ;
	}
	public function InserePrivilege($nomPriv)
	{
		$this->InserePrivilege(array($nomPriv)) ;
	}
	public function InserePrivileges($nomPrivs)
	{
		$this->NecessiteMembreConnecte = 1 ;
		array_splice($this->Privileges, count($this->Privileges), 0, $nomPrivs) ;
	}
	public function DeclarePrivilege($nomPriv)
	{
		$this->DeclarePrivileges(array($nomPriv)) ;
	}
	public function DeclarePrivileges($nomPrivs=array())
	{
		$this->NecessiteMembreConnecte = 1 ;
		$this->Privileges = $nomPrivs ;
	}
}

