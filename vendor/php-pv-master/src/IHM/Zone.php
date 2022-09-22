<?php

namespace Pv\IHM ;

class Zone extends \Pv\IHM\IHM
{
	public $TypeIHM = "zone" ;
	public $Scripts = array() ;
	public $NomParamScriptAppele = "appelleScript" ;
	public $AutoDetectParamScriptAppele = 1 ;
	public $ValeurParamScriptAppeleFixe = "" ;
	public $ValeurParamScriptAppele = "" ;
	public $ScriptParDefaut = null ;
	public $NomScriptParDefaut = "accueil" ;
	public $ScriptAppele = null ;
	public $ScriptNonTrouve = null ;
	public $Membership = null ;
	public $NomClasseMembership = null ;
	protected $NomScriptsEditMembership = array() ;
	public $InclureScriptsMembership = 0 ;
	public $PrivilegesEditMembership = array() ;
	public $PrivilegesEditMembres = array() ;
	public $NomClasseScriptDeconnexion = "" ;
	public $NomClasseScriptRecouvreMP = "" ;
	public $NomClasseScriptConnexion = "" ;
	public $NomClasseScriptChangeMotPasse = "" ;
	public $NomClasseScriptChangeMPMembre = "" ;
	public $NomClasseScriptDoitChangerMotPasse = "" ;
	public $NomClasseScriptInscription = "" ;
	public $NomClasseScriptAjoutMembre = "" ;
	public $NomClasseScriptImportMembre = "" ;
	public $NomClasseScriptModifMembre = "" ;
	public $NomClasseScriptModifPrefs = "" ;
	public $NomClasseScriptSupprMembre = "" ;
	public $NomClasseScriptListeMembres = "" ;
	public $NomClasseScriptAjoutProfil = "" ;
	public $NomClasseScriptModifProfil = "" ;
	public $NomClasseScriptSupprProfil = "" ;
	public $NomClasseScriptListeProfils = "" ;
	public $NomClasseScriptAjoutRole = "" ;
	public $NomClasseScriptModifRole = "" ;
	public $NomClasseScriptSupprRole = "" ;
	public $NomClasseScriptListeRoles = "" ;
	public $NomClasseScriptAjoutServeurAD = "" ;
	public $NomClasseScriptModifServeurAD = "" ;
	public $NomClasseScriptSupprServeurAD = "" ;
	public $NomClasseScriptListeServeursAD = "" ;
	public $NomScriptConnexion = "connexion" ;
	public $NomScriptInscription = "inscription" ;
	public $AutoriserInscription = 0 ;
	public $AutoriserModifPrefs = 0 ;
	public $NomScriptRecouvreMP = "recouvreMP" ;
	public $NomScriptDeconnexion = "deconnexion" ;
	public $NomScriptChangeMPMembre = "changeMPMembre" ;
	public $NomScriptChangeMotPasse = "changeMotPasse" ;
	public $NomScriptDoitChangerMotPasse = "doitChangerMotPasse" ;
	public $NomScriptAjoutMembre = "ajoutMembre" ;
	public $NomScriptImportMembre = "importMembre" ;
	public $NomScriptModifMembre = "modifMembre" ;
	public $NomScriptModifPrefs = "modifPrefs" ;
	public $NomScriptSupprMembre = "supprMembre" ;
	public $NomScriptListeMembres = "listeMembres" ;
	public $NomScriptAjoutProfil = "ajoutProfil" ;
	public $NomScriptModifProfil = "modifProfil" ;
	public $NomScriptSupprProfil = "supprProfil" ;
	public $NomScriptListeProfils = "listeProfils" ;
	public $NomScriptAjoutRole = "ajoutRole" ;
	public $NomScriptModifRole = "modifRole" ;
	public $NomScriptSupprRole = "supprRole" ;
	public $NomScriptListeRoles = "listeRoles" ;
	public $NomScriptAjoutServeurAD = "ajoutServeurAD" ;
	public $NomScriptModifServeurAD = "modifServeurAD" ;
	public $NomScriptSupprServeurAD = "supprServeurAD" ;
	public $NomScriptListeServeursAD = "listeServeursAD" ;
	public $ScriptDeconnexion ;
	public $ScriptInscription ;
	public $ScriptConnexion ;
	public $ScriptRecouvreMP ;
	public $ScriptChangeMotPasse ;
	public $ScriptChangeMPMembre ;
	public $ScriptDoitChangerMotPasse ;
	public $ScriptAjoutMembre ;
	public $ScriptModifMembre ;
	public $ScriptImportMembre ;
	public $ScriptModifPrefs ;
	public $ScriptSupprMembre ;
	public $ScriptListeMembres ;
	public $ScriptAjoutProfil ;
	public $ScriptModifProfil ;
	public $ScriptSupprProfil ;
	public $ScriptListeProfils ;
	public $ScriptAjoutRole ;
	public $ScriptModifRole ;
	public $ScriptSupprRole ;
	public $ScriptListeRoles ;
	public $ScriptAjoutServeurAD = null ;
	public $ScriptModifServeurAD = null ;
	public $ScriptSupprServeurAD = null ;
	public $ScriptListeServeursAD = null ;
	public $PrivilegesExceptions = array() ;
	public $PrivilegesPassePartout = array() ;
	public $ExceptionsToujoursVisibles = 0 ;
	public $SecuriserMembership = true ;
	public $ExceptionsVisiblesPourSuperAdmin = 1 ;
	public $ExceptionsAvantRendu = array() ;
	public $Exceptions = array() ;
	public $LibelleDetailsException = "Plus de d&eacute;tails" ;
	public $AliasDetailsException = "exception_more_details" ;
	public $UtiliserJournalRequetesEnvoyees = 0 ;
	public $JournalRequetesEnvoyees ;
	public $UtiliserJournalExceptions = 0 ;
	public $JournalExceptions ;
	public $NomClasseJournalRequetesEnvoyees = "PvJournalRequetesEnvoyeesBase" ;
	public $NomClasseJournalExceptions = "PvJournalExceptions" ;
	public $NomClasseRemplisseurConfigMembership = "PvRemplisseurConfigMembershipSimple" ;
	public $RemplisseurConfigMembership ;
	public $MessageScriptMalRefere = "<p>Ce script n'est pas bien refere. Il ne peut etre affiche.</p>" ;
	public $AnnulDetectMemberCnx = 0 ;
	public function NatureZone()
	{
		return "base" ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
	}
	public function CaptureExceptionBaseDonnees(& $basedonnees)
	{
		if($basedonnees->ConnectionException == "")
		{
			return ;
		}
	}
	public function RenduException($exception)
	{
		$this->Exceptions[] = $exception ;
		if(! $this->ExceptionsVisible())
		{
			return "" ;
		}
		$ctn = '' ;
		if($this->RenduEnCours == 0)
		{
			$this->ExceptionsAvantRendu[] = $exception ;
		}
		else
		{
			$ctn = $this->RenduContenuException($exception) ;
		}
		return $ctn ;
	}
	protected function RenduContenuException($exception)
	{
		$nomFonctJS = 'BasculeDetailsException_'.count($this->Exceptions) ;
		$nomBlocDetailsException = 'detailsException_'.count($this->Exceptions) ;
		$ctn = '' ;
		$ctn .= '<div class="exception">'.htmlentities($exception->Message).' <a href="javascript:'.$nomFonctJS.'()">'.$this->LibelleDetailsException.'</a></div>'.PHP_EOL ;
		$ctn .= '<div class="detailsException" id="'.$nomBlocDetailsException.'" style="display:none ;">'.PHP_EOL ;
		$ctn .= '<table width="100%" cellspacing="0" cellpadding="3">' ;
		$ctn .= '<tr><th valign="top" align="left">Parametres : </th>' ;
		$ctn .= '<td valign="top"><div style="overflow:scroll; height:120px; width:700px"><pre>'.var_export($exception->Parametres, true).'</pre></div></td></tr>'.PHP_EOL ;
		$ctn .= '<tr><th valign="top" align="left">Fichier : </th>' ;
		$ctn .= '<td valign="top">'.$exception->CheminFichier.'</td></tr>'.PHP_EOL ;
		$ctn .= '<tr><th valign="top" align="left">Ligne : </th>' ;
		$ctn .= '<td valign="top">'.$exception->NumeroLigne.'</td></tr>'.PHP_EOL ;
		$ctn .= '</table>' ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '<script type="text/javascript">
function '.$nomFonctJS.'()
{
var bloc = document.getElementById("'.$nomBlocDetailsException.'") ;
if(bloc.style.display == "none")
{
	bloc.style.display = "block" ;
}
else
{
	bloc.style.display = "none" ;
}
}
</script>'.PHP_EOL ;
		return $ctn ;
	}
	public function ExceptionsVisible()
	{
		if($this->ExceptionsToujoursVisibles)
		{
			return 1 ;
		}
		if(! $this->PossedeMembreConnecte())
		{
			return 0 ;
		}
		$ok = 0 ;
		if(count($this->PrivilegesExceptions) > 0)
		{
			$ok = $this->ZoneParent->PossedePrivileges($this->PrivilegesExceptions) ;
		}
		if($this->ExceptionsVisiblesPourSuperAdmin && $this->MembreSuperAdminConnecte())
		{
			$ok = 1 ;
		}
		return $ok ;
	}
	protected function ChargeScriptsMembership()
	{
		if(! $this->InclureScriptsMembership || $this->EstNul($this->Membership))
			return ;
		if(! $this->PossedeMembreConnecte())
		{
			$this->ChargeScriptsMSNonConnecte() ;
		}
		else
		{
			$this->ChargeScriptsMSConnecte() ;
		}
	}
	protected function ChargeScriptsMSNonConnecte()
	{
		if(class_exists($this->NomClasseScriptConnexion))
		{
			$nomClasse = $this->NomClasseScriptConnexion ;
			$this->ScriptConnexion = new $nomClasse() ;
			$this->InscritScript($this->NomScriptConnexion, $this->ScriptConnexion) ;
		}
		if($this->AutoriserInscription && class_exists($this->NomClasseScriptInscription))
		{
			$nomClasse = $this->NomClasseScriptInscription ;
			$this->ScriptInscription = new $nomClasse() ;
			$this->InscritScript($this->NomScriptInscription, $this->ScriptInscription) ;
		}
		if(class_exists($this->NomClasseScriptRecouvreMP))
		{
			$nomClasse = $this->NomClasseScriptRecouvreMP ;
			$this->ScriptRecouvreMP = new $nomClasse() ;
			$this->InscritScript($this->NomScriptRecouvreMP, $this->ScriptRecouvreMP) ;
		}
	}
	public function MembreADActive()
	{
		return ($this->EstPasNul($this->Membership->MemberLogged) && $this->Membership->MemberLogged->ADActivated != $this->Membership->ADActivatedMemberTrueValue) ;
	}
	public function MembreDoitChangerMP()
	{
		return ($this->MembreAuthentifieParAD() == 0 && $this->EstPasNul($this->Membership->MemberLogged) && $this->Membership->MemberLogged->MustChangePassword == $this->Membership->MustChangePasswordMemberTrueValue) ;
	}
	public function MembreAuthentifieParAD()
	{
		return ($this->AttrMembreConnecte("MEMBER_AD_ACTIVATED") == 1) ;
	}
	protected function ChargeScriptsMSConnecte()
	{
		$privilegesEditMembres = $this->PrivilegesEditMembres ;
		array_splice($privilegesEditMembres, count($privilegesEditMembres) - 1, 0, $this->PrivilegesEditMembership) ;
		if(class_exists($this->NomClasseScriptDeconnexion))
		{
			$nomClasse = $this->NomClasseScriptDeconnexion ;
			$this->ScriptDeconnexion = new $nomClasse() ;
			$this->InscritScript($this->NomScriptDeconnexion, $this->ScriptDeconnexion) ;
		}
		if($this->MembreADActive())
		{
			if(class_exists($this->NomClasseScriptChangeMotPasse))
			{
				$nomClasse = $this->NomClasseScriptChangeMotPasse ;
				$this->ScriptChangeMotPasse = new $nomClasse() ;
				$this->InscritScript($this->NomScriptChangeMotPasse, $this->ScriptChangeMotPasse) ;
			}
			if($this->MembreDoitChangerMP() && class_exists($this->NomClasseScriptDoitChangerMotPasse))
			{
				$nomClasse = $this->NomClasseScriptDoitChangerMotPasse ;
				$this->ScriptDoitChangerMotPasse = new $nomClasse() ;
				$this->InscritScript($this->NomScriptDoitChangerMotPasse, $this->ScriptDoitChangerMotPasse) ;
			}
		}
		if(class_exists($this->NomClasseScriptChangeMPMembre))
		{
			$nomClasse = $this->NomClasseScriptChangeMPMembre ;
			$this->ScriptChangeMPMembre = new $nomClasse() ;
			$this->InscritScript($this->NomScriptChangeMPMembre, $this->ScriptChangeMPMembre) ;
		}
		if($this->MembreAuthentifieParAD() == 0)
		{
			if(class_exists($this->NomClasseScriptChangeMotPasse))
			{
				$nomClasse = $this->NomClasseScriptChangeMotPasse ;
				$this->ScriptChangeMotPasse = new $nomClasse() ;
				$this->InscritScript($this->NomScriptChangeMotPasse, $this->ScriptChangeMotPasse) ;
			}
		}
		if(class_exists($this->NomClasseScriptAjoutMembre))
		{
			$nomClasse = $this->NomClasseScriptAjoutMembre ;
			$this->ScriptAjoutMembre = new $nomClasse() ;
			$this->ScriptAjoutMembre->DeclarePrivileges($privilegesEditMembres) ;
			$this->InscritScript($this->NomScriptAjoutMembre, $this->ScriptAjoutMembre) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptAjoutMembre ;
		}
		if(class_exists($this->NomClasseScriptImportMembre))
		{
			$nomClasse = $this->NomClasseScriptImportMembre ;
			$this->ScriptImportMembre = new $nomClasse() ;
			$this->ScriptImportMembre->DeclarePrivileges($privilegesEditMembres) ;
			$this->InscritScript($this->NomScriptImportMembre, $this->ScriptImportMembre) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptImportMembre ;
		}
		if(class_exists($this->NomClasseScriptModifMembre))
		{
			$nomClasse = $this->NomClasseScriptModifMembre ;
			$this->ScriptModifMembre = new $nomClasse() ;
			$this->ScriptModifMembre->DeclarePrivileges($privilegesEditMembres) ;
			$this->InscritScript($this->NomScriptModifMembre, $this->ScriptModifMembre) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptModifMembre ;
		}
		if($this->AutoriserModifPrefs && class_exists($this->NomClasseScriptModifPrefs))
		{
			$nomClasse = $this->NomClasseScriptModifPrefs ;
			$this->ScriptModifPrefs = new $nomClasse() ;
			$this->InscritScript($this->NomScriptModifPrefs, $this->ScriptModifPrefs) ;
		}
		if(class_exists($this->NomClasseScriptSupprMembre))
		{
			$nomClasse = $this->NomClasseScriptSupprMembre ;
			$this->ScriptSupprMembre = new $nomClasse() ;
			$this->ScriptSupprMembre->DeclarePrivileges($privilegesEditMembres) ;
			$this->InscritScript($this->NomScriptSupprMembre, $this->ScriptSupprMembre) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptSupprMembre ;
		}
		if(class_exists($this->NomClasseScriptListeMembres))
		{
			$nomClasse = $this->NomClasseScriptListeMembres ;
			$this->ScriptListeMembres = new $nomClasse() ;
			$this->ScriptListeMembres->DeclarePrivileges($privilegesEditMembres) ;
			$this->InscritScript($this->NomScriptListeMembres, $this->ScriptListeMembres) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptListeMembres ;
		}
		if(class_exists($this->NomClasseScriptAjoutProfil))
		{
			$nomClasse = $this->NomClasseScriptAjoutProfil ;
			$this->ScriptAjoutProfil = new $nomClasse() ;
			$this->ScriptAjoutProfil->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptAjoutProfil, $this->ScriptAjoutProfil) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptAjoutProfil ;
		}
		if(class_exists($this->NomClasseScriptModifProfil))
		{
			$nomClasse = $this->NomClasseScriptModifProfil ;
			$this->ScriptModifProfil = new $nomClasse() ;
			$this->ScriptModifProfil->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptModifProfil, $this->ScriptModifProfil) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptModifProfil ;
		}
		if(class_exists($this->NomClasseScriptSupprProfil))
		{
			$nomClasse = $this->NomClasseScriptSupprProfil ;
			$this->ScriptSupprProfil = new $nomClasse() ;
			$this->ScriptSupprProfil->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptSupprProfil, $this->ScriptSupprProfil) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptSupprProfil ;
		}
		if(class_exists($this->NomClasseScriptListeProfils))
		{
			$nomClasse = $this->NomClasseScriptListeProfils ;
			$this->ScriptListeProfils = new $nomClasse() ;
			$this->ScriptListeProfils->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptListeProfils, $this->ScriptListeProfils) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptListeProfils ;
		}
		if(class_exists($this->NomClasseScriptAjoutRole))
		{
			$nomClasse = $this->NomClasseScriptAjoutRole ;
			$this->ScriptAjoutRole = new $nomClasse() ;
			$this->ScriptAjoutRole->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptAjoutRole, $this->ScriptAjoutRole) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptAjoutRole ;
		}
		if(class_exists($this->NomClasseScriptModifRole))
		{
			$nomClasse = $this->NomClasseScriptModifRole ;
			$this->ScriptModifRole = new $nomClasse() ;
			$this->ScriptModifRole->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptModifRole, $this->ScriptModifRole) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptModifRole ;
		}
		if(class_exists($this->NomClasseScriptSupprRole))
		{
			$nomClasse = $this->NomClasseScriptSupprRole ;
			$this->ScriptSupprRole = new $nomClasse() ;
			$this->ScriptSupprRole->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptSupprRole, $this->ScriptSupprRole) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptSupprRole ;
		}
		if(class_exists($this->NomClasseScriptListeRoles))
		{
			$nomClasse = $this->NomClasseScriptListeRoles ;
			$this->ScriptListeRoles = new $nomClasse() ;
			$this->ScriptListeRoles->DeclarePrivileges($this->PrivilegesEditMembership) ;
			$this->InscritScript($this->NomScriptListeRoles, $this->ScriptListeRoles) ;
			$this->NomScriptsEditMembership[] = $this->NomScriptListeRoles ;
		}
		if($this->Membership->ADServerMemberColumn != '')
		{
			if(class_exists($this->NomClasseScriptAjoutServeurAD))
			{
				$nomClasse = $this->NomClasseScriptAjoutServeurAD ;
				$this->ScriptAjoutServeurAD = new $nomClasse() ;
				$this->ScriptAjoutServeurAD->DeclarePrivileges($this->PrivilegesEditMembership) ;
				$this->InscritScript($this->NomScriptAjoutServeurAD, $this->ScriptAjoutServeurAD) ;
				$this->NomScriptsEditMembership[] = $this->NomScriptAjoutServeurAD ;
			}
			if(class_exists($this->NomClasseScriptModifServeurAD))
			{
				$nomClasse = $this->NomClasseScriptModifServeurAD ;
				$this->ScriptModifServeurAD = new $nomClasse() ;
				$this->ScriptModifServeurAD->DeclarePrivileges($this->PrivilegesEditMembership) ;
				$this->InscritScript($this->NomScriptModifServeurAD, $this->ScriptModifServeurAD) ;
				$this->NomScriptsEditMembership[] = $this->NomScriptModifServeurAD ;
			}
			if(class_exists($this->NomClasseScriptSupprServeurAD))
			{
				$nomClasse = $this->NomClasseScriptSupprServeurAD ;
				$this->ScriptSupprServeurAD = new $nomClasse() ;
				$this->ScriptSupprServeurAD->DeclarePrivileges($this->PrivilegesEditMembership) ;
				$this->InscritScript($this->NomScriptSupprServeurAD, $this->ScriptSupprServeurAD) ;
				$this->NomScriptsEditMembership[] = $this->NomScriptSupprServeurAD ;
			}
			if(class_exists($this->NomClasseScriptListeServeursAD))
			{
				$nomClasse = $this->NomClasseScriptListeServeursAD ;
				$this->ScriptListeServeursAD = new $nomClasse() ;
				$this->ScriptListeServeursAD->DeclarePrivileges($this->PrivilegesEditMembership) ;
				$this->InscritScript($this->NomScriptListeServeursAD, $this->ScriptListeServeursAD) ;
				$this->NomScriptsEditMembership[] = $this->NomScriptListeServeursAD ;
			}
		}
	}
	public function ChargeConfig()
	{
		$this->ChargeScripts() ;
		$this->ChargeScriptParDefaut() ;
		$this->ChargeScriptNonTrouve() ;
		$this->ChargeMembership() ;
		$this->ChargeJournalExceptions() ;
		$this->ChargeJournalRequetesEnvoyees() ;
	}
	protected function ChargeMembership()
	{
		$nomClasseMembership = $this->NomClasseMembership ;
		if($nomClasseMembership != "")
		{
			if(class_exists($nomClasseMembership))
			{
				$this->Membership = new $nomClasseMembership($this) ;
			}
			else
			{
				die("La classe ".$nomClasseMembership." n'existe pas") ;
			}
			if($this->SecuriserMembership == true)
			{
				$this->Membership->SessionMemberKey = $this->IDInstanceCalc."_MemberId" ;
				$this->Membership->CryptSessionValues = true ;
			}
		}
	}
	protected function ChargeScripts()
	{
	}
	protected function ChargeScriptParDefaut()
	{
		$this->ScriptParDefaut = $this->ValeurNulle() ;
		if(isset($this->Scripts[$this->NomScriptParDefaut]))
		{
			$this->ScriptParDefaut = & $this->Scripts[$this->NomScriptParDefaut] ;
		}
	}
	protected function ChargeScriptNonTrouve()
	{
	}
	public function & InsereScriptParDefaut($script)
	{
		$this->InscritScriptParDefaut($script) ;
		return $script ;
	}
	public function & InsereScript($nom, $script)
	{
		$this->InscritScript($nom, $script) ;
		return $script ;
	}
	public function InscritScriptParDefaut(& $script)
	{
		$this->InscritScript($this->NomScriptParDefaut, $script) ;
	}
	public function InscritScript($nom, & $script)
	{
		$this->Scripts[$nom] = & $script ;
		$script->AdopteZone($nom, $this) ;
	}
	protected function ExecuteScriptIndisponible(& $script)
	{
		$msgIndisponible = ($script->MessageIndisponible != '') ? $script->MessageIndisponible : $this->MessageScriptIndisponible ;
		$this->AfficheRenduIndisponible($script, $msgIndisponible) ;
	}
	protected function AfficheRenduIndisponible(& $script, $msg)
	{
		$ctn = '' ;
		$this->ScriptPourRendu = & $script ;
		$ctn .= $this->RenduEnteteDocument() ;
		$ctn .= '<div style="text-color:red">'.$msg.'</div>' ;
		$ctn .= $this->RenduPiedDocument() ;
		echo $ctn ;
		exit ;
	}
	public function ScriptAccessible($nomScript)
	{
		if(! isset($this->Scripts[$nomScript]))
		{
			return 0 ;
		}
		return $this->Scripts[$nomScript]->EstAccessible() ;
	}
	public function TypeZone()
	{
		return "BASE" ;
	}
	protected function DetecteScriptAppele()
	{
		$this->DetecteParamScriptAppele() ;
		$nomScripts = array_keys($this->Scripts) ;
		$this->ScriptAppele = & $this->ScriptParDefaut ;
		foreach($nomScripts as $i => $nom)
		{
			$script = & $this->Scripts[$nom] ;
			if($script->AccepteAppel($this->ValeurParamScriptAppele))
			{
				$this->ScriptAppele = & $script ;
				break ;
			}
		}
		// print get_class($this->ScriptAppele)." hdfhdh" ;
	}
	protected function ExecuteScriptAppele()
	{
		if($this->EstPasNul($this->ScriptAppele))
		{
			$this->ScriptAppele->ChargeConfig() ;
			$this->ExecuteScript($this->ScriptAppele) ;
		}
		else
		{
			if($this->EstPasNul($this->ScriptNonTrouve))
			{
				$this->ScriptNonTrouve->ChargeConfig() ;
				$this->ExecuteScript($this->ScriptNonTrouve) ;
			}
			else
			{
				$this->AfficheRenduNonTrouve() ;
			}
		}
	}
	protected function PrepareScript(& $script)
	{
	}
	protected function TermineScript(& $script)
	{
	}
	public function ExecuteScript(& $script)
	{
		$this->PrepareScript($script) ;
		$this->VerifieValiditeMotPasse($script) ;
		if($script->EstAccessible())
		{
			$this->DetermineEnvironnement($script) ;
			$script->Execute() ;
		}
		else
		{
			$this->ExecuteScriptInaccessible($script) ;
		}
		$this->TermineScript($script) ;
	}
	protected function AfficheRenduNonTrouve()
	{
		Header("HTTP/1.0 404 Not Found") ;
		exit ;
	}
	protected function AfficheRenduInacessible()
	{
		header('HTTP/1.1 401 Unauthorized');
		echo "Vous n'avez pas le droit d'acc&eacute;der &agrave; ce script !!!" ;
		exit ;
	}
	protected function ExecuteScriptInaccessible(& $script)
	{
		$this->AfficheRenduInacessible() ;
	}
	protected function ExecuteScriptMalRefere(& $script)
	{
		echo $this->MessageScriptMalRefere ;
		exit ;
	}
	protected function DetecteParamScriptAppele()
	{
		$this->ValeurBruteParamScriptAppele = "" ;
		$this->ValeurParamScriptAppele = $this->NomScriptParDefaut ;
		if($this->AutoDetectParamScriptAppele == 0)
		{
			if($this->ValeurParamScriptAppeleFixe != "")
			{
				$this->ValeurBruteParamScriptAppele = $this->ValeurParamScriptAppeleFixe ;
				$this->ValeurParamScriptAppele = $this->ValeurBruteParamScriptAppele ;
			}
		}
		else
		{
			if(isset($_GET[$this->NomParamScriptAppele]))
			{
				$this->ValeurBruteParamScriptAppele = $_GET[$this->NomParamScriptAppele] ;
				$this->ValeurParamScriptAppele = $this->ValeurBruteParamScriptAppele ;
			}
		}
	}
	public function DeclareScript($nom, $nomClasseScript)
	{
		if(! class_exists($nomClasseScript))
		{
			return ;
		}
		$nomPropriete = $nom.'Script' ;
		$this->$nomPropriete = new $nomClasseScript() ;
		$this->InscritScript($nom, $this->$nomPropriete) ;
	}
	protected function DetermineEnvironnement(& $script)
	{
		$script->DetermineEnvironnement() ;
	}
	protected function DetecteScriptsMembership()
	{
		$this->DetecteMembreConnecte() ;
		$this->ChargeScriptsMembership() ;
	}
	public function Execute()
	{
		$this->DemarreExecution() ;
		$this->DetecteScriptsMembership() ;
		$this->DetecteScriptAppele() ;
		$this->ExecuteScriptAppele() ;
		$this->TermineExecution() ;
	}
	public function MembershipActive()
	{
		return class_exists($this->NomClasseMembership) ? 1 : 0;
	}
	protected function DetecteMembreConnecte()
	{
		if($this->Membership == null || $this->AnnulDetectMemberCnx == 1)
		{
			return ;
		}
		$this->Membership->Run() ;
		// print_r($this->Membership->MemberLogged) ;
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
	public function SurScriptParDefaut()
	{
		return ($this->ValeurParamScriptAppele == $this->NomScriptParDefaut) ;
	}
	public function SurScriptConnecte()
	{
		return ($this->InclureScriptsMembership == 0 || ($this->PossedeMembreConnecte() && $this->NomScriptDeconnexion != $this->ValeurBruteParamScriptAppele)) ;
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
	public function EditMembresPossible()
	{
		if($this->PossedeMembreConnecte() && count($this->PrivilegesEditMembres) == 0)
			return 1 ;
		return $this->PossedePrivileges($this->PrivilegesEditMembres) ;
	}
	public function EditMembershipPossible()
	{
		if($this->PossedeMembreConnecte() && count($this->PrivilegesEditMembership) == 0)
			return 1 ;
		return $this->PossedePrivileges($this->PrivilegesEditMembership) ;
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
			return "" ;
		}
		return $this->Membership->MemberLogged->Login ;
	}
	public function NomCompletMembreConnecte($inverse=0)
	{
		if(! $this->PossedeMembreConnecte())
		{
			return "" ;
		}
		return ($inverse == 0) ? $this->AttrMembreConnecte("MEMBER_FIRST_NAME")." ".$this->AttrMembreConnecte("MEMBER_LAST_NAME") : $this->AttrMembreConnecte("MEMBER_LAST_NAME")." ".$this->AttrMembreConnecte("MEMBER_FIRST_NAME") ;
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
	public function DoitChangerMotPasse(& $script)
	{
		// if($this->PossedeMembreConnecte() == 0 || ! $script->EstAccessible())
		if($this->PossedeMembreConnecte() == 0)
		{
			return 0 ;
		}
		$membership = $this->Membership ;
		$membre = $membership->MemberLogged ;
		$ok = 0 ;
		if($membre->MustChangePassword == $membership->MustChangePasswordMemberTrueValue)
		{
			$ok = 1 ;
		}
		return $ok ;
	}
	public function PeutChangerMotPasse()
	{
		if($this->PossedeMembreConnecte() == 0 || $this->EstNul($this->ScriptChangeMotPasse))
		{
			return 0 ;
		}
		$membership = & $this->Membership ;
		return ($membership->ADServerMemberColumn == "" || $membership->MemberLogged->ADActivated == $membership->ADActivatedMemberTrueValue) ? 0 : 1 ;
	}
	protected function VerifieValiditeMotPasse($script)
	{
		if($script->NomElementZone != $this->NomScriptDoitChangerMotPasse && $this->DoitChangerMotPasse($script))
		{
			$this->RedirigeVersScript($this->ScriptDoitChangerMotPasse) ;
		}
	}
	protected function ChargeJournalRequetesEnvoyees()
	{
		if(! $this->UtiliserJournalRequetesEnvoyees)
		{
			return ;
		}
		$this->JournalRequetesEnvoyees = null ;
		$nomClasse = $this->NomClasseJournalRequetesEnvoyees ;
		if(class_exists($nomClasse))
		{
			$this->JournalRequetesEnvoyees = new $nomClasse() ;
			$this->JournalRequetesEnvoyees->ChargeConfig() ;
		}
	}
	protected function ChargeJournalExceptions()
	{
		if(! $this->UtiliserJournalExceptions)
		{
			return ;
		}
		$this->JournalExceptions = null ;
		$nomClasse = $this->NomClasseJournalExceptions ;
		if(class_exists($nomClasse))
		{
			$this->JournalExceptions = new $nomClasse() ;
			$this->JournalExceptions->ChargeConfig() ;
		}
	}
	public function RapporteException($exception)
	{
		
	}
	public function RapporteRequeteEnvoyee()
	{
		if(! $this->UtiliserJournalRequetesEnvoyees || $this->EstNul($this->JournalRequetesEnvoyees))
		{
			return ;
		}
		$this->JournalRequetesEnvoyees->Inscrit() ;
	}
	public function RedirigeVersScript(& $script, $params=array())
	{
	}
	public function InvoqueScript($nomScript, $params=array(), $valeurPost=array(), $async=1)
	{
		return $this->InvoqueScriptSpec($nomScript, $params, $valeurPost, $async) ;
	}
	protected function InvoqueScriptSpec($nomScript, $params=array(), $valeurPost=array(), $async=1)
	{
	}
}

