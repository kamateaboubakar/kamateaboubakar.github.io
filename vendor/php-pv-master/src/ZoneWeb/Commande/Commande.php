<?php

namespace Pv\ZoneWeb\Commande ;

class Commande extends \Pv\ZoneWeb\ElementRendu\ElementRendu
{
	public $Visible = true ;
	public $NecessiteFormulaireDonnees = false ;
	public $NecessiteTableauDonnees = false ;
	public $UtiliserRenduDispositif = false ;
	public $ContenuJsSurClick = "" ;
	public $FormulaireDonneesParent ;
	public $TableauDonneesParent ;
	public $ScriptParent ;
	public $ZoneParent ;
	public $ApplicationParent ;
	public $NomElementFormulaireDonnees = "" ;
	public $NomElementSousComposantRendu = "" ;
	public $CheminIcone ;
	public $InclureLibelle = true ;
	public $Libelle = "" ;
	public $NomClsCSS = "" ;
	public $ContenuAvantRendu = "" ;
	public $ContenuApresRendu = "" ;
	public $InfoBulle = "" ;
	public $MessageErreurExecution = "La commande a &eacute;t&eacute; ex&eacute;cut&eacute;e avec des erreurs" ;
	public $MessageSuccesExecution = "La commande a &eacute;t&eacute; ex&eacute;cut&eacute;e avec succ&egrave;s" ;
	public $MessageExecution ;
	public $StatutExecution = 0 ;
	public $Criteres = array() ;
	public $Actions = array() ;
	public $SeparateurCriteresNonRespectes = "; " ;
	public $Liens = array() ;
	public $InscrireLienAnnuler = false ;
	public $InscrireLienReprendre = false ;
	public $UrlLienAnnuler = "" ;
	public $UrlLienReprendre = "" ;
	public $ParamsExecution = array() ;
	public function ExtraitParamsExecution()
	{
		$params = $this->ParamsExecution ;
		if($this->NecessiteFormulaireDonnees)
		{
			$params = array_merge($params, $this->FormulaireDonneesParent->ExtraitValeursParametre($this->FormulaireDonneesParent->FiltresEdition)) ;
		}
		if($this->NecessiteTableauDonnees)
		{
			$params = array_merge($params, $this->TableauDonneesParent->ExtraitValeursParametre($this->TableauDonneesParent->FiltresSelection)) ;
		}
		return $params ;
	}
	public function EstSucces()
	{
		return $this->StatutExecution == 1 ;
	}
	public function ErreurNonRenseignee()
	{
		return $this->MessageErreur == "" ;
	}
	public function & InsereLien($url, $titre)
	{
		$lien = new \Pv\ZoneWeb\FormulaireDonnees\Lien($url, $titre) ;
		$this->Liens[] = & $lien ;
		return $lien ;
	}
	public function ObtientLiens()
	{
		$liens = $this->Liens ;
		$form = & $this->FormulaireDonneesParent ;
		if($form->InscrireCommandeAnnuler == 1 && $this->InscrireLienAnnuler == 1 && $this->UrlLienAnnuler != '')
		{
			$lienAnnul = new \Pv\ZoneWeb\FormulaireDonnees\Lien(
				$this->UrlLienAnnuler,
				$this->LibelleLienAnnuler
			) ;
			$liens[] = $lienAnnul ;
		}
		if($this->InscrireLienReprendre == 1)
		{
			$lienReprendre = new \Pv\ZoneWeb\FormulaireDonnees\Lien(
				$form->ObtientUrlInitiale(),
				$this->LibelleLienReprendre
			) ;
			$liens[] = $lienReprendre ;
		}
		return $liens ;
	}
	public function PrepareRendu(& $composant)
	{
	}
	protected function AdopteComposantRendu($nom, &$composant)
	{
		$this->NomElementSousComposantRendu = $nom ;
		$this->ScriptParent = & $composant->ScriptParent ;
		$this->ZoneParent = & $composant->ZoneParent ;
		$this->ApplicationParent = & $composant->ApplicationParent ;
	}
	public function AdopteFormulaireDonnees($nom, & $formulaireDonnees)
	{
		$this->NomElementFormulaireDonnees = $nom ;
		$this->FormulaireDonneesParent = & $formulaireDonnees ;
		$this->AdopteComposantRendu($nom, $formulaireDonnees) ;
	}
	public function AdopteTableauDonnees($nom, & $tableauDonnees)
	{
		$this->NomElementTableauDonnees = $nom ;
		$this->TableauDonneesParent = & $tableauDonnees ;
		$this->AdopteComposantRendu($nom, $tableauDonnees) ;
	}
	public function InscritCritere(& $critere)
	{
		$this->Criteres[] = & $critere ;
		$critere->AdopteCommande(count($this->Criteres), $this) ;
	}
	public function InscritCritr(& $critere)
	{
		$this->InscritCritere($critere) ;
	}
	public function & InsereCritereScriptParent()
	{
		$critere = new \Pv\ZoneWeb\Critere\ValideScriptParent() ;
		$this->InscritCritere($critere) ;
		return $critere ;
	}
	public function & InsereCritereZoneParent()
	{
		$critere = new \Pv\ZoneWeb\Critere\ValideZoneParent() ;
		$this->InscritCritere($critere) ;
		return $critere ;
	}
	public function & InsereCritereFormatUrl($nomFiltres = array())
	{
		$critere = new \Pv\ZoneWeb\Critere\FormatUrl() ;
		$this->InscritCritere($critere) ;
		call_user_func_array(array(& $critere, 'CibleFiltres'), $nomFiltres) ;
		return $critere ;
	}
	public function & InsereCritereFormatMotPasse($nomFiltres = array())
	{
		$critere = new \Pv\ZoneWeb\Critere\FormatMotPasse() ;
		$this->InscritCritere($critere) ;
		call_user_func_array(array(& $critere, 'CibleFiltres'), $nomFiltres) ;
		return $critere ;
	}
	public function & InsereCritereFormatLogin($nomFiltres = array())
	{
		$critere = new \Pv\ZoneWeb\Critere\FormatLogin() ;
		$this->InscritCritere($critere) ;
		call_user_func_array(array(& $critere, 'CibleFiltres'), $nomFiltres) ;
		return $critere ;
	}
	public function & InsereCritereFormatEmail($nomFiltres = array())
	{
		$critere = new \Pv\ZoneWeb\Critere\FormatEmail() ;
		$this->InscritCritere($critere) ;
		call_user_func_array(array(& $critere, 'CibleFiltres'), $nomFiltres) ;
		return $critere ;
	}
	public function & InsereCritereNonVide($nomFiltres = array())
	{
		$critere = new \Pv\ZoneWeb\Critere\NonVide() ;
		$this->InscritCritere($critere) ;
		call_user_func_array(array(& $critere, 'CibleFiltres'), $nomFiltres) ;
		return $critere ;
	}
	public function & InsereCritrNonVide($nomFiltres = array())
	{
		$critere = $this->InsereCritereNonVide($nomFiltres) ;
		return $critere ;
	}
	public function & InscritNouvActCmd($actCmd, $nomFiltresCibles=array())
	{
		return $this->InscritActCmd($actCmd, $nomFiltresCibles) ;
	}
	public function & InscritNouvAction($actCmd)
	{
		return $this->InscritActCmd($actCmd) ;
	}
	public function & InscritActCmd(& $actCmd, $nomFiltresCibles=array())
	{
		$this->Actions[] = & $actCmd ;
		$actCmd->AdopteCommande(count($this->Actions), $this) ;
		call_user_func_array(array($actCmd, 'CibleFiltres'), $nomFiltresCibles) ;
		return $actCmd ;
	}
	public function InscritAction(& $actCmd)
	{
		$this->InscritActCmd($actCmd) ;
	}
	protected function VideStatutExecution()
	{
		$this->MessageExecution = "" ;
		$this->StatutExecution = 1 ;
	}
	public function RenseigneErreur($messageErreur="")
	{
		$this->MessageExecution = $messageErreur ;
		$this->StatutExecution = 0 ;
	}
	public function ConfirmeSucces($msgSucces = '')
	{
		$this->StatutExecution = 1 ;
		$paramsSucces = $this->ExtraitParamsExecution() ;
		// print_r($paramsSucces) ;
		if(count($paramsSucces) == 0)
		{
			$this->MessageExecution = ($msgSucces == '') ? $this->MessageSuccesExecution : $msgSucces ;
		}
		else
		{
			$this->MessageExecution = \Pv\Misc::_parse_pattern(($msgSucces == '') ? $this->MessageSuccesExecution : $msgSucces, array_map('htmlentities', $paramsSucces)) ;
		}
	}
	protected function ExecuteInstructions()
	{
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
	}
	public function & InsereClasseCritere($nomClasse, $nomFiltresCibles=array())
	{
		if(! class_exists($nomClasse))
		{
			die("La classe '$nomClasse' n'existe pas") ;
		}
		$critere = new $nomClasse() ;
		$this->InsereNouvCritere($critere, $nomFiltresCibles) ;
		return $critere ;
	}
	public function & InsereClasseActCmd($nomClasse, $nomFiltresCibles=array())
	{
		if(! class_exists($nomClasse))
		{
			die("La classe '$nomClasse' n'existe pas") ;
		}
		$actCmd = new $nomClasse() ;
		$this->InscritNouvActCmd($actCmd, $nomFiltresCibles) ;
		return $actCmd ;
	}
	public function & InsereClasseAction($nomClasse, $nomFiltresCibles=array())
	{
		$action = $this->InsereClasseActCmd($nomClasse, $nomFiltresCibles) ;
		return $action ;
	}
	public function & InsereCritere($critere, $nomFiltresCibles=array())
	{
		$this->InscritCritere($critere) ;
		call_user_func_array(array($critere, 'CibleFiltres'), $nomFiltresCibles) ;
		return $critere ;
	}
	public function & InsereActCmd($actCmd, $nomFiltresCibles=array())
	{
		$this->InscritAction($actCmd) ;
		call_user_func_array(array($actCmd, 'CibleFiltres'), $nomFiltresCibles) ;
		return $actCmd ;
	}
	public function & InsereActCmdScriptParent()
	{
		return $this->InsereAction(new \Pv\ZoneWeb\ActionCommande\AppliqueScriptParent()) ;
	}
	public function & InsereActCmdNotifScript($nomScript, $params=array())
	{
		$actCmd = $this->InsereAction(new \Pv\ZoneWeb\ActionCommande\NotifieScript()) ;
		$actCmd->NomScript = $nomScript ;
		$actCmd->Parametres = $params ;
		return $actCmd ;
	}
	public function & InsereActCmdZoneParent()
	{
		return $this->InsereAction(new \Pv\ZoneWeb\ActionCommande\AppliqueZoneParent()) ;
	}
	public function & InsereAction($action, $nomFiltresCibles=array())
	{
		$action = $this->InsereActCmd($action, $nomFiltresCibles) ;
		return $action ;
	}
	public function & InsereNouvCritere($critere, $nomFiltresCibles=array())
	{
		$this->InscritCritere($critere) ;
		call_user_func_array(array($critere, 'CibleFiltres'), $nomFiltresCibles) ;
		return $critere ;
	}
	public function & InsereNouvActCmd($actCmd, $nomFiltresCibles=array())
	{
		$this->InscritAction($actCmd) ;
		call_user_func_array(array($actCmd, 'CibleFiltres'), $nomFiltresCibles) ;
		return $actCmd ;
	}
	public function & InsereNouvAction($action, $nomFiltresCibles=array())
	{
		$action = $this->InsereActCmd($nomClasse, $nomFiltresCibles) ;
		return $action ;
	}
	public function Execute()
	{
		if(($this->NecessiteFormulaireDonnees && $this->EstNul($this->FormulaireDonneesParent)) || ($this->NecessiteTableauDonnees && $this->EstNul($this->TableauDonneesParent)))
		{
			return ;
		}
		$this->VideStatutExecution() ;
		if(! $this->RespecteCriteres())
		{
			return ;
		}
		// echo $this->MessageExecution ;
		$this->VerifiePreRequis() ;
		if($this->StatutExecution == 0)
		{
			return ;
		}
		$this->ExecuteInstructions() ;
		if($this->StatutExecution == 0)
		{
			return ;
		}
		$this->ExecuteActions() ;
	}
	protected function VerifiePreRequis()
	{
		
	}
	protected function VerifieFichiersUpload(& $filtres)
	{
		foreach($filtres as $n => & $flt)
		{
			if($flt->Role == "http_upload" && $flt->ToujoursRenseignerFichier == 1 && $flt->Lie() == '')
			{
				$this->RenseigneErreur($flt->LibelleErreurTelecharg) ;
			}
		}
	}
	protected function RespecteCriteres()
	{
		$indCriteres = array_keys($this->Criteres) ;
		$messageErreurs = array() ;
		foreach($indCriteres as $i => $indCritere)
		{
			$critere = & $this->Criteres[$indCritere] ;
			if($critere->EstRespecte() == 0)
			{
				$messageErreurs[] = $critere->MessageErreur ;
			}
		}
		$ok = 1 ;
		if(count($messageErreurs) > 0)
		{
			$this->RenseigneErreur(join($this->SeparateurCriteresNonRespectes, $messageErreurs)) ;
			$ok = 0 ;
		}
		return $ok ;
	}
	protected function ExecuteActions()
	{
		$nomActions = array_keys($this->Actions) ;
		// print_r($this->NomElementFormulaireDonnees." : ".$nomActions) ;
		if(count($nomActions) > 0)
		{
			if($this->MessageExecution == '')
			{
				$this->MessageExecution = $this->MessageSuccesExecution ;
			}
			foreach($nomActions as $i => $nomAction)
			{
				$action = & $this->Actions[$nomAction] ;
				$action->Execute() ;
			}
		}
	}
	public function RenduDispositif()
	{
		if($this->Visible == 0)
		{
			return '' ;
		}
		if(! $this->EstBienRefere())
		{
			return $this->RenduMalRefere() ;
		}
		if(! $this->EstAccessible())
		{
			return $this->RenduInaccessible() ;
		}
		$ctn .= $this->RenduDispositifBrut() ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		return "" ;
	}
	public function InclureEnvoiFiltres()
	{
		return 1 ;
	}
}
