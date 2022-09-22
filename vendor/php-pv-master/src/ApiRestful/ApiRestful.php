<?php

namespace Pv\ApiRestful ;

class ApiRestful extends \Pv\IHM\IHM
{
	public $TypeIHM = "API" ;
	public $CrypteurToken ;
	public $CheminRacineApi = "/" ;
	public $NomClasseAuth = '\Pv\ApiRestful\Auth\Distant' ;
	public $Auth ;
	public $Routes = array() ;
	public $VersionMin = 1 ;
	public $VersionMax = 1 ;
	public $NomTableSession = "membership_session" ;
	public $DelaiExpirSession = 900 ;
	public $TotalJoursExpirDevice = 90 ;
	public $MaxSessionsMembre = 0 ;
	public $EncodageDocument = "utf-8" ;
	public $OriginesAutorisees = "*" ;
	public $RouteParDefaut ;
	public $Reponse ;
	public $InclureStatutReponse = true ;
	public $EncodageJsonNatif = true ;
	public $Requete ;
	public $Metadatas ;
	public $NomClasseMembership ;
	public $Membership ;
	public $InclureRoutesMembership = true ;
	public $NomRouteAppelee ;
	public $PrivilegesEditMembership = array() ;
	public $PrivilegesEditMembres = array() ;
	protected $NomRoutesEditMembership = array() ;
	public $AutoriserInscription = false ;
	public $AutoriserModifPrefs = false ;
	public $CrypterReponse = 0 ;
	public $CypherCryptReponse = "AES-256-CBC" ;
	public $CleCryptReponse = "AP1Res6Ful142" ;
	public $HmacCryptReponse = "sha256" ;
	public $NomClasseRouteRecouvreMP = '\Pv\ApiRestful\RouteMembership\RecouvreMP' ;
	public $NomClasseRouteConnexion = '\Pv\ApiRestful\RouteMembership\Connexion' ;
	public $NomClasseRouteInscription = '\Pv\ApiRestful\RouteMembership\Inscription' ;
	public $NomClasseRouteDeconnexion = '\Pv\ApiRestful\RouteMembership\Deconnexion' ;
	public $NomClasseRouteModifPrefs = '\Pv\ApiRestful\RouteMembership\ModifPrefs' ;
	public $NomClasseRouteChangeMotPasse = '\Pv\ApiRestful\RouteMembership\ChangeMotPasse' ;
	public $NomClasseRouteAjoutMembre = "PvRouteAjoutMembreRestful" ;
	public $NomClasseRouteModifMembre = "PvRouteModifMembreRestful" ;
	public $NomClasseRouteChangeMPMembre = "PvRouteChangeMPMembreRestful" ;
	public $NomClasseRouteSupprMembre = "PvRouteSupprMembreRestful" ;
	public $NomClasseRouteListeMembres = "PvRouteListeMembresRestful" ;
	public $NomClasseRouteAjoutProfil = "PvRouteAjoutProfilRestful" ;
	public $NomClasseRouteModifProfil = "PvRouteModifProfilRestful" ;
	public $NomClasseRouteSupprProfil = "PvRouteSupprProfilRestful" ;
	public $NomClasseRouteListeProfils = "PvRouteListeProfilsRestful" ;
	public $NomClasseRouteAjoutRole = "PvRouteAjoutRoleRestful" ;
	public $NomClasseRouteModifRole = "PvRouteModifRoleRestful" ;
	public $NomClasseRouteSupprRole = "PvRouteSupprRoleRestful" ;
	public $NomClasseRouteListeRoles = "PvRouteListeRolesRestful" ;
	public $NomRouteRecouvreMP = "reinitialise_password" ;
	public $NomRouteConnexion = "connexion" ;
	public $NomRouteInscription = "inscription" ;
	public $NomRouteDeconnexion = "deconnexion" ;
	public $NomRouteModifPrefs = "modifInfosPerso" ;
	public $NomRouteImporteMembre = "importe" ;
	public $NomRouteChangeMPMembre = "change_mp_membre" ;
	public $NomRouteChangeMotPasse = "change_password" ;
	public $NomRoutesAcces = "acces" ;
	public $NomRoutesMonEspace = "mon_espace" ;
	public $NomRoutesMembres = "membres" ;
	public $NomRoutesProfils = "profils" ;
	public $NomRoutesRoles = "roles" ;
	public $NomRoutesServeursAD = "serveurs_ad" ;
	public $NomParamMaxElementsCollection = "size" ;
	public $NomParamIndiceDebutCollection = "start" ;
	public $NomParamSensTriCollection = "sort" ;
	public $NomParamColonnesCollection = "fields" ;
	public $InclureMetadatasEntete = true ;
	public function CreeBDPrinc()
	{
		return $this->ApplicationParent->CreeBDPrinc() ;
	}
	public function CreeDBPrinc()
	{
		return $this->ApplicationParent->CreeDBPrinc() ;
	}
	public function CreeFournisseurDonneesPrinc()
	{
		return $this->ApplicationParent->CreeFournisseurDonneesPrinc() ;
	}
	public function CreeFournDonneesPrinc()
	{
		return $this->ApplicationParent->CreeFournisseurDonneesPrinc() ;
	}
	public function CreeFournPrinc()
	{
		return $this->ApplicationParent->CreeFournisseurDonneesPrinc() ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Metadatas = new \StdClass() ;
		$this->CrypteurToken = $this->CreeCrypteurToken() ;
	}
	protected function CreeCrypteurToken()
	{
		$crypter = new \Pv\Openssl\Crypter() ;
		$crypter->key = 'kotn9'.get_class($this) ;
		return $crypter ;
	}
	public function & InscritRoute($nom, $cheminRoute, & $route)
	{
		if(strpos($cheminRoute, '/') !== 0)
		{
			$cheminRoute = '/'.$cheminRoute ;
		}
		$this->Routes[$nom] = & $route ;
		$route->AdopteApi($nom, $cheminRoute, $this) ;
		return $route ;
	}
	public function & InsereRoute($nom, $cheminRoute, $route)
	{
		return $this->InscritRoute($nom, $cheminRoute, $route) ;
	}
	public function & InsereRouteClasse($nom, $cheminRoute, $nomClasse)
	{
		if(! class_exists($nomClasse))
		{
			die("[InsereRoute] : la classe $nomClasse n'existe pas. Veuillez corriger") ;
		}
		$route = new $nomClasse() ;
		return $this->InscritRoute($nom, $cheminRoute, $route) ;
	}
	public function CreeRoutePrinc()
	{
		return new Route\Route() ;
	}
	public function & InsereRoutePrinc($nom, $cheminRoute)
	{
		return $this->InsereRoute($nom, $chemin, $this->CreeRoutePrinc()) ;
	}
	public function CreeCollectionPrinc()
	{
		return new Route\Collection() ;
	}
	public function & InsereCollectionPrinc($nom, $cheminRoute)
	{
		return $this->InsereRoute($nom, $chemin, $this->CreeCollectionPrinc()) ;
	}
	public function CreeElementPrinc()
	{
		return new Route\Element() ;
	}
	public function & InsereElementPrinc($nom, $cheminRoute)
	{
		return $this->InsereRoute($nom, $chemin, $this->CreeElementPrinc()) ;
	}
	public function & InsereSinglePrinc($nom, $cheminRoute)
	{
		return $this->InsereRoute($nom, $chemin, $this->CreeElementPrinc()) ;
	}
	public function & InsereIndividuelPrinc($nom, $cheminRoute)
	{
		return $this->InsereRoute($nom, $chemin, $this->CreeElementPrinc()) ;
	}
	public function & InsereRouteParDefaut($route)
	{
		$this->RouteParDefaut = $route ;
		$route->AdopteApi("accueil", "", $this) ;
		return $this->RouteParDefaut ;
	}
	protected function DetecteRouteAppelee()
	{
		$this->MethodeHttp = $this->Requete->Methode ;
		$this->NomRouteAppelee = '' ;
		$cheminRacineApi = $this->CheminRacineApi ;
		if($cheminRacineApi == '')
		{
			$cheminRacineApi = $this->CheminFichierRelatif ;
		}
		if(strpos($cheminRacineApi, '/') !== 0)
		{
			$cheminRacineApi = '/'.$cheminRacineApi ;
		}
		foreach($this->Routes as $nom => $route)
		{
			preg_match_all("/\{([a-zA-Z0-9\_]+)\}/", $route->CheminRouteApi, $nomsArgsRoute) ;
			$cheminRegexRoute = preg_quote($cheminRacineApi, '/')
				.preg_replace("/\\\\{[a-zA-Z0-9\_]+\\\\}/", '([^\/]+)', preg_quote($route->CheminRouteApi, '/')) ;
			// echo $nom." : ".$cheminRegexRoute." !== ".$this->ValeurParamRoute."<br>\n" ;
			// exit ;
			if(preg_match('/^'.$cheminRegexRoute.'$/', $this->ValeurParamRoute, $valeursArgsRoute) && ($route->MethodeHttp == '' || $route->MethodeHttp == $this->Requete->Methode) && $route->ApprouveAppel($this))
			{
				$this->NomRouteAppelee = $nom ;
				if(count($nomsArgsRoute[1]) > 0)
				{
					for($i=1; $i<count($valeursArgsRoute); $i++)
					{
						$this->ArgsRouteAppelee[$nomsArgsRoute[1][$i - 1]] = $valeursArgsRoute[$i] ;
					}
				}
			}
		}
		if($this->NomRouteAppelee != '')
		{
			$this->RouteAppelee = & $this->Routes[$this->NomRouteAppelee] ;
		}
	}
	public function PossedeRouteAppelee()
	{
		return $this->NomRouteAppelee != '' ;
	}
	public function & BDMembership()
	{
		return $this->Membership->Database ;
	}
	protected function ChargeMembership()
	{
		$nomClasseMembership = $this->NomClasseMembership ;
		if($nomClasseMembership != '')
		{
			if(class_exists($nomClasseMembership))
			{
				$this->Membership = new $nomClasseMembership($this) ;
			}
			else
			{
				die('La classe Membership '.$nomClasseMembership.' n\'est pas declaree') ;
			}
		}
		else
		{
			return ;
		}
		$this->DetecteMembreConnecte() ;
		if($this->InclureRoutesMembership == true)
		{
			$this->DetermineRoutesMembership() ;
		}
	}
	protected function DetecteMembreConnecte()
	{
		$this->Auth->ChargeSession($this) ;
	}
	protected function ChargeRoutesMSConnecte()
	{
		$this->InsereRouteClasse($this->NomRoutesMonEspace."_".$this->NomRouteModifPrefs, $this->NomRoutesMonEspace."/".$this->NomRouteModifPrefs, $this->NomClasseRouteModifPrefs) ;
		$this->InsereRouteClasse($this->NomRoutesMonEspace."_".$this->NomRouteDeconnexion, $this->NomRoutesMonEspace."/".$this->NomRouteDeconnexion, $this->NomClasseRouteDeconnexion) ;
	}
	protected function ChargeRoutesMSNonConnecte()
	{
		$this->RouteRecouvreMP = $this->InsereRouteClasse($this->NomRoutesAcces."_".$this->NomRouteRecouvreMP, $this->NomRoutesAcces."/".$this->NomRouteRecouvreMP, $this->NomClasseRouteRecouvreMP) ;
		$this->RouteInscription = $this->InsereRouteClasse($this->NomRoutesAcces."_".$this->NomRouteInscription, $this->NomRoutesAcces."/".$this->NomRouteInscription, $this->NomClasseRouteInscription) ;
		$this->RouteConnexion = $this->InsereRouteClasse($this->NomRoutesAcces."_".$this->NomRouteConnexion, $this->NomRoutesAcces."/".$this->NomRouteConnexion, $this->NomClasseRouteConnexion) ;
	}
	protected function DetermineRoutesMembership()
	{
		if(! $this->PossedeMembreConnecte())
		{
			$this->ChargeRoutesMSNonConnecte() ;
		}
		else
		{
			$this->ChargeRoutesMSConnecte() ;
		}
	}
	public function PossedeMembreConnecte()
	{
		$ok = 0 ;
		if($this->Membership != null)
		{
			if($this->EstPasNul($this->Membership->MemberLogged))
			{
				if(! $this->Membership->UseGuestMember || $this->Membership->MemberLogged->Id != $this->Membership->GuestMemberId)
				{
					$ok = 1 ;
				}
			}
		}
		return $ok ;
	}
	public function ObtientMembreConnecte()
	{
		$membre = null ;
		if($this->EstPasNul($this->Membership))
		{
			if($this->Membership->MemberLogged != null)
			{
				$membre = $this->Membership->MemberLogged ;
			}
		}
		return $membre ;
	}
	public function EstSuperAdmin($membre)
	{
		if($this->Membership->RootMemberId != "" && $membre->Id == $this->Membership->RootMemberId)
		{
			return 1 ;
		}
		return 0 ;
	}
	public function EstSuperAdminConnecte()
	{
		return $this->MembreSuperAdminConnecte() ;
	}
	public function MembreSuperAdminConnecte()
	{
		if(! $this->PossedeMembreConnecte())
		{
			return 0 ;
		}
		return $this->EstSuperAdmin($this->Membership->MemberLogged) ;
	}
	public function EditMembershipPossible()
	{
		if($this->PossedeMembreConnecte() && count($this->PrivilegesEditMembership) == 0)
			return 1 ;
		return $this->PossedePrivileges($this->PrivilegesEditMembership) ;
	}
	public function EditMembresPossible()
	{
		if($this->PossedeMembreConnecte() && count($this->PrivilegesEditMembres) == 0)
			return 1 ;
		return $this->PossedePrivileges($this->PrivilegesEditMembres) ;
	}
	public function IdMembreConnecte()
	{
		if(! $this->PossedeMembreConnecte())
		{
			return 0 ;
		}
		return $this->Membership->MemberLogged->Id ;
	}
	public function LoginMembreConnecte()
	{
		if(! $this->PossedeMembreConnecte())
		{
			return 0 ;
		}
		return $this->Membership->MemberLogged->Login ;
	}
	public function AttrMembreConnecte($nomAttr)
	{
		if(! $this->PossedeMembreConnecte()|| ! isset($this->Membership->MemberLogged->RawData[$nomAttr]))
		{
			return null ;
		}
		return $this->Membership->MemberLogged->RawData[$nomAttr] ;
	}
	public function TitreProfilConnecte()
	{
		if(! $this->PossedeMembreConnecte()|| ! isset($this->Membership->MemberLogged->Profile))
		{
			return null ;
		}
		return $this->Membership->MemberLogged->Profile->Title ;
	}
	public function PossedeTousPrivileges()
	{
		$ok = 1 ;
		foreach($this->Membership->MemberLogged->Profile->Privileges as $nomRole => $priv)
		{
			if($priv->Enabled == 0)
			{
				$ok = 0 ;
				break ;
			}
		}
		return $ok ;
	}
	public function PossedePrivilege($nomRole, $strict=0)
	{
		return $this->PossedePrivileges(array($nomRole), $strict) ;
	}
	public function PossedePrivileges($privileges=array(), $strict=0)
	{
		$ok = 0 ;
		$privilegesSpec = $privileges ;
		if($strict == 0 && count($this->PrivilegesPassePartout) > 0)
			array_splice($privileges, 0, 0, $this->PrivilegesPassePartout) ;
		if($this->PossedeMembreConnecte() == 0)
		{
			return 0 ;
		}
		if(count($privilegesSpec) == 0)
		{
			return 1 ;
		}
		$membre = $this->Membership->MemberLogged ;
		if(count($privileges) > 0)
		{
			foreach($privileges as $i => $nomRole)
			{
				if(isset($membre->Profile->Privileges[$nomRole]))
				{
					if($membre->Profile->Privileges[$nomRole]->Enabled)
					{
						$ok = 1 ;
						break ;
					}
				}
			}
		}
		return $ok ;
	}
	protected function PrepareExecution()
	{
	}
	protected function TermineExecution()
	{
	}
	public function ChargeConfig()
	{
	}
	protected function ChargeRoutes()
	{
	}
	public function SuccesReponse()
	{
		return $this->Reponse->EstSucces() ;
	}
	public function EchecReponse()
	{
		return $this->Reponse->EstEchec() ;
	}
	protected function DetermineEnvironnement()
	{
		if($this->NomClasseAuth != '')
		{
			$nomClasse = $this->NomClasseAuth ;
			$this->Auth = new $nomClasse() ;
		}
		$this->Requete = new \Pv\ApiRestful\Requete() ;
		$this->Reponse = new \Pv\ApiRestful\Reponse() ;
		$this->ValeurParamRoute = $this->Requete->CheminRelatifRoute ;
		if($this->ValeurParamRoute != '' && $this->ValeurParamRoute[strlen($this->ValeurParamRoute) - 1] == "/")
		{
			$this->ValeurParamRoute = substr($this->ValeurParamRoute, 0, strlen($this->ValeurParamRoute) - 1) ;
		}
	}
	public function Execute()
	{
		$this->DetermineEnvironnement() ;
		$this->ChargeMembership() ;
		if($this->Reponse->EstSucces())
		{
			$this->ChargeRoutes() ;
			$this->PrepareExecution() ;
			$this->DetecteRouteAppelee() ;
			if($this->PossedeRouteAppelee())
			{
				if($this->RouteAppelee->EstAccessible())
				{
					$this->RouteAppelee->Execute() ;
				}
				else
				{
					$this->Reponse->ConfirmeNonAutoris() ;
				}
			}
			else
			{
				if($this->EstPasNul($this->RouteParDefaut))
				{
					$this->RouteParDefaut->Execute() ;
				}
				else
				{
					$this->Reponse->ConfirmeNonTrouve() ;
				}
			}
		}
		$this->Reponse->EnvoieRendu($this) ;
		$this->TermineExecution() ;
		$this->Requete = null ;
		$this->Reponse = null ;
		exit ;
	}
	public function ArgRouteAppelee($nom, $valeurDefaut=null)
	{
		return (isset($this->ArgsRouteAppelee[$nom])) ? $this->ArgsRouteAppelee[$nom] : $valeurDefaut ;
	}
	public function ArgRoute($nom, $valeurDefaut=null)
	{
		return (isset($this->ArgsRouteAppelee[$nom])) ? $this->ArgsRouteAppelee[$nom] : $valeurDefaut ;
	}
}