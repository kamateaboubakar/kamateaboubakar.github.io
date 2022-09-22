<?php

namespace Pv\ZoneWeb ;

class ZoneWeb extends \Pv\IHM\Zone
{
	public $TagTitre = "h2" ;
	public $TypeDocument ;
	public $HoteRecaptcha ;
	public $CleSiteRecaptcha ;
	public $CleSecreteRecaptcha ;
	public $AdrScriptSession ;
	public $DocumentsWeb = array() ;
	public $GestTachesWeb ;
	public $UtiliserDocumentWeb = false ;
	public $DocumentWebSelect ;
	public $ActiverRoutes = false ;
	public $CorrigerCheminsRoute = true ;
	public $PreparerComposants = true ;
	public $ArgsRouteAppelee = array() ;
	public $DefinitionTypeDocument ;
	public $CheminFavicon ;
	public $LangueDocument = "en" ;
	public $EncodageDocument = "utf-8" ;
	public $TitreDocument ;
	public $MotsCleMeta ;
	public $DescriptionMeta ;
	public $ViewportMeta ;
	public $AuteurMeta ;
	public $UrlBase = "" ;
	public $ModeCache ;
	public $ScriptPourRendu ;
	public $InclureCtnJsEntete = true ;
	public $RenduExtraHead = '' ;
	public $InclureJQuery = false ;
	public $CheminJQuery = "js/jquery.min.js" ;
	public $InclureBootstrap = false ;
	public $CheminJsBootstrap = "js/bootstrap.min.js" ;
	public $CheminCSSBootstrap = "css/bootstrap.css" ;
	public $InclureBootstrapTheme = false ;
	public $CheminCSSBootstrapTheme = "css/bootstrap-theme.min.css" ;
	public $InclureFontAwesome = false ;
	public $CheminFontAwesome = "css/font-awesome.min.css" ;
	public $InclureJQueryMigrate = false ;
	public $CheminJQueryMigrate = "js/jquery-migrate.min.js" ;
	public $InclureJQueryMigrate3 = false ;
	public $CheminJQueryMigrate3 = "js/jquery-migrate3.min.js" ;
	public $InclureJQueryUi = false ;
	public $CheminJsJQueryUi = "js/jquery-ui.min.js" ;
	public $CheminCSSJQueryUi = "css/jquery-ui.css" ;
	public $InclureNormalize = false ;
	public $CheminNormalize = "css/normalize.css" ;
	public $ContenusCSS = array() ;
	public $ContenusJs = array() ;
	public $ContenusJsPied = array() ;
	public $CheminIconeScript = "" ;
	public $InclureRenduTitre = true ;
	public $InclureRenduIcone = true ;
	public $InclureRenduMessageExecution = true ;
	public $DetectIconeCorresp = false ;
	public $CheminDossierIconeCorresp = "images/icones" ;
	public $ExtIconeCorresp = "png" ;
	public $InclureRenduChemin = true ;
	public $InclureRenduDescription = true ;
	public $ActionsPrinc = array() ;
	public $ActionsAvantRendu = array() ;
	public $ActionsApresRendu = array() ;
	public $ComposantsRendu = array() ;
	public $NomParamActionAppelee = "appelleAction" ;
	public $NomParamTacheAppelee = "appelleTache" ;
	public $ValeurParamActionAppelee = false ;
	public $ValeurParamTacheAppelee = false ;
	public $ActionsAppelees = array() ;
	public $AnnulerRendu = 0 ;
	public $RenduEnCours = 0 ;
	public $RedirigerVersConnexion = false ;
	public $ActiverRafraichScript = true ;
	public $LibelleEspaceReserveFiltres = true ;
	public $InclureScriptsMembership = true ;
	public $NomDocumentWebEditMembership = "" ;
	public $ModeRecouvrMP = "directe" ;
	public $NomClasseScriptRecouvreMP = '\Pv\ZoneWeb\ScriptMembership\RecouvreMotPasse' ;
	public $NomClasseScriptInscription = '\Pv\ZoneWeb\ScriptMembership\Inscription' ;
	public $NomClasseScriptDeconnexion = '\Pv\ZoneWeb\ScriptMembership\Deconnexion' ;
	public $NomClasseScriptConnexion = '\Pv\ZoneWeb\ScriptMembership\Connexion' ;
	public $NomClasseScriptChangeMotPasse = '\Pv\ZoneWeb\ScriptMembership\ChangeMotPasse' ;
	public $NomClasseScriptDoitChangerMotPasse = '\Pv\ZoneWeb\ScriptMembership\DoitChangerMotPasse' ;
	public $NomClasseScriptChangeMPMembre = '\Pv\ZoneWeb\ScriptMembership\ChangeMotPasseMembre' ;
	public $NomClasseScriptAjoutMembre = '\Pv\ZoneWeb\ScriptMembership\AjoutMembre' ;
	public $NomClasseScriptModifMembre = '\Pv\ZoneWeb\ScriptMembership\ModifMembre' ;
	public $NomClasseScriptImportMembre = '\Pv\ZoneWeb\ScriptMembership\ImportMembre' ;
	public $NomClasseScriptModifPrefs = '\Pv\ZoneWeb\ScriptMembership\ModifInfosPerso' ;
	public $NomClasseScriptSupprMembre = '\Pv\ZoneWeb\ScriptMembership\SupprMembre' ;
	public $NomClasseScriptListeMembres = '\Pv\ZoneWeb\ScriptMembership\ListeMembres' ;
	public $NomClasseScriptAjoutProfil = '\Pv\ZoneWeb\ScriptMembership\AjoutProfil' ;
	public $NomClasseScriptModifProfil = '\Pv\ZoneWeb\ScriptMembership\ModifProfil' ;
	public $NomClasseScriptSupprProfil = '\Pv\ZoneWeb\ScriptMembership\SupprProfil' ;
	public $NomClasseScriptListeProfils = '\Pv\ZoneWeb\ScriptMembership\ListeProfils' ;
	public $NomClasseScriptAjoutRole = '\Pv\ZoneWeb\ScriptMembership\AjoutRole' ;
	public $NomClasseScriptModifRole = '\Pv\ZoneWeb\ScriptMembership\ModifRole' ;
	public $NomClasseScriptSupprRole = '\Pv\ZoneWeb\ScriptMembership\SupprRole' ;
	public $NomClasseScriptListeRoles = '\Pv\ZoneWeb\ScriptMembership\ListeRoles' ;
	public $NomClasseScriptAjoutServeurAD = '\Pv\ZoneWeb\ScriptMembership\AjoutServeurAD' ;
	public $NomClasseScriptModifServeurAD = '\Pv\ZoneWeb\ScriptMembership\ModifServeurAD' ;
	public $NomClasseScriptSupprServeurAD = '\Pv\ZoneWeb\ScriptMembership\SupprServeurAD' ;
	public $NomClasseScriptListeServeursAD = '\Pv\ZoneWeb\ScriptMembership\ListeServeursAD' ;
	protected $TacheAppelee ;
	protected $ScriptExecuteAccessible = false ;
	public $CleMessageExecutionSession = "PvMessageExecution" ;
	public $ClasseCSSMsgExecSucces = "Succes" ;
	public $ClasseCSSMsgExecErreur = "Erreur" ;
	public $InscrireActRedirectScriptSession = 1 ;
	public $InscrireTacheWebCtrlTransacts = 1 ;
	public $Metas = array() ;
	public $ActionRedirScriptSession ;
	public $NomDossierModelesEval ;
	public $UtiliserModelesEval = 0 ;
	public $UtiliserModelesEvalAuto = 1 ;
	public $CheminRacineRoute ;
	public $CheminBanniere ;
	public $NomSessionTraducteur = "traducteur" ;
	public $NomParamTraducteur = "traducteur" ;
	public $ReglesHtmlSur = array() ;
	protected $PourImpression = 0 ;
	public $EnteteActionsTablPrinc = "Actions" ;
	public $LibelleAjout = "Ajouter" ;
	public $LibelleModif = "Modifier" ;
	public $LibelleSuppr = "Supprimer" ;
	public $LibelleListe = "Lister" ;
	public $LibelleInfos = "Infos" ;
	public $LibelleRech = "Rechercher" ;
	public $LibelleDetails = "Details" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->GestTachesWeb = new \Pv\ZoneWeb\Tache\GestTaches() ;
		$this->GestTachesWeb->AdopteZone("gestTaches", $this) ;
		$this->AdrScriptSession = new AdrScriptSession() ;
		$this->InitReglesHtmlSur() ;
	}
	public function AdopteApplication($nom, & $app)
	{
		parent::AdopteApplication($nom, $app) ;
		if(\Pv\Application\Application::$InclureAliasesCompsFltsDonnees == true)
		{
			$this->DefinitAliasCompsFltsDonnees() ;
		}
	}
	protected function DefinitAliasCompsFltsDonnees()
	{
		\Pv\Application\Application::$AliasesCompsFltsDonnees = array_merge(
			\Pv\Application\Application::$AliasesCompsFltsDonnees,
			array(
				'PvZoneSelectHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect',
				'PvJsColor' => '\Pv\ZoneWeb\FiltreDonnees\Composant\JsColor',
				'PvZoneSelectBool' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool',
				'PvZoneCaptcha' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCaptcha',
				'PvNoteBloc' => '\Pv\ZoneWeb\FiltreDonnees\Composant\NoteBloc',
				'PvTimeInput' => '\Pv\ZoneWeb\FiltreDonnees\Composant\TimeInput',
				'PvCalendarDateInput' => '\Pv\ZoneWeb\FiltreDonnees\Composant\CalendarDateInput',
				'PvDatePick' => '\Pv\ZoneWeb\FiltreDonnees\Composant\DatePick',
				'PvVideoJs' => '\Pv\ZoneWeb\FiltreDonnees\Composant\VideoJs',
				'PvRecaptcha' => '\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha',
				'PvRecaptcha2' => '\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha2',
				'PvRecaptcha3' => '\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha3',
				'PvZoneEtiquetteHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEtiquette',
				'PvZoneCorrespHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCorresp',
				'PvZoneEntreeHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEntree',
				'PvZoneTexteHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte',
				'PvZoneDateHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDate',
				'PvZoneDateTimeHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneDateTime',
				'PvZoneInvisibleHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneInvisible',
				'PvZoneMotPasseHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMotPasse',
				'PvZoneMultiligneHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne',
				'PvZoneUploadHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneUpload',
				'PvZoneCocherHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCocher',
				'PvZoneCocherBoolHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCocherBool',
				'PvZoneBoiteSelectHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteSelect',
				'PvZoneBoiteChoixHtml' => '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteChoix',
			)
		) ;
	}
	public function NatureZone()
	{
		return "web" ;
	}
	protected function InitReglesHtmlSur()
	{
	}
	public function ObtientUrl()
	{
		if($this->ApplicationParent->EnModeConsole())
		{
			if($this->ApplicationParent->UrlRacine != '')
			{
				return $this->ApplicationParent->UrlRacine."/".$this->CheminFichierRelatif ;
			}
			elseif($this->ApplicationParent->NomElementActif == $this->NomElementApplication)
			{
				return $_SERVER["argv"][0] ;
			}
			else
			{
				return "" ;
			}
		}
		$url = \Pv\Misc::remove_url_params(\Pv\Misc::get_current_url()) ;
		if($this->ApplicationParent->NomElementActif == $this->NomElementApplication)
		{
			return $url ;
		}
		$url = ((isset($_SERVER["HTTPS"])) ? 'https' : 'http').'://'.$_SERVER["SERVER_NAME"].(($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':'.$_SERVER["SERVER_PORT"] : '').'/'.$this->CheminFichierRelatif ;
		return $url ;
	}
	public function ObtientUrlParam($params=array())
	{
		return $this->ObtientUrl()."?".\Pv\Misc::http_build_query_string($params) ;
	}
	public function RedirigeVersScript(& $script, $params=array())
	{
		$url = $this->ObtientUrlScript($script->NomElementZone, $params) ;
		if($url == false)
		{
			exit ;
		}
		Header("Location:".$url) ;
		exit ;
	}
	public function ObtientScriptParDefaut()
	{
		$script = null ;
		if($this->EstPasNul($this->Scripts[$this->NomScriptParDefaut]))
		{
			$script = & $this->Scripts[$this->NomScriptParDefaut] ;
		}
		return $script ;
	}
	public function ChargeTraducteurActif()
	{
		$nomSession = $this->NomElementApplication.'_'.$this->NomSessionTraducteur ;
		$nomParam = $this->NomParamTraducteur ;
		$nomTrad = '' ;
		if(isset($_GET[$nomParam]))
		{
			$nomTrad = $_GET[$nomParam] ;
		}
		elseif(isset($_SESSION[$nomSession]))
		{
			$nomTrad = $_SESSION[$nomSession] ;
		}
		if($nomTrad != "")
		{
			$this->ActiveTraducteur($nomTrad) ;
		}
		$_SESSION[$nomSession] = $this->ApplicationParent->SystTrad->NomTraducteurActif ;
	}
	public function HtmlSur($ctn)
	{
		$result = $ctn ;
		return $result ;
	}
	public function DemarreRenduImpression()
	{
		$this->PourImpression = 1 ;
	}
	public function TermineRenduImpression()
	{
		$this->PourImpression = 1 ;
	}
	public function ImpressionEnCours()
	{
		return $this->PourImpression ;
	}
	public function InvoqueScriptSpec($nomScript, $params=array(), $valeurPost=array(), $async=1)
	{
		return PvApplication::TelechargeUrl($this->ObtientUrlScript($nomScript, $params, 0), $valeurPost, $async) ;
	}
	public function Execute()
	{
		if(! session_id())
		{
			session_start() ;
		}
		$this->ExecuteGestTachesWeb() ;
		$this->DetecteActionAppelee() ;
		$this->ExecuteActionPrinc() ;
		$this->DemarreExecution() ;
		$this->DetecteScriptsMembership() ;
		$this->DetecteScriptAppele() ;
		$this->ExecuteScriptAppele() ;
		$this->TermineExecution() ;
	}
	protected function DetecteScriptAppele()
	{
		if($this->ActiverRoutes == 1)
		{
			$attrs = explode("?", $_SERVER["REQUEST_URI"], 2) ;
			$this->ValeurParamRoute = $attrs[0] ;
			$nomScripts = array_keys($this->Scripts) ;
			$this->ScriptAppele = & $this->ScriptParDefaut ;
			foreach($nomScripts as $i => $nom)
			{
				$script = & $this->Scripts[$nom] ;
				$cheminRouteScript = $script->CheminRoute ;
				if($cheminRouteScript == '')
				{
					$cheminRouteScript = $script->NomElementZone ;
				}
				$cheminRegexRoute = preg_quote($this->CheminRacineRoute, '/')
					.preg_replace("/\\\\{[a-zA-Z0-9\_]+\\\\}/", '([^\/]+)', preg_quote($cheminRouteScript, '/')) ;
				if($this->CorrigerCheminsRoute == 1 && ($this->ValeurParamRoute[strlen($this->ValeurParamRoute) - 1] == '/' && $cheminRegexRoute[strlen($cheminRegexRoute) - 1] != '/'))
				{
					$cheminRegexRoute .= '\/' ;
				}
				if(preg_match('/^'.$cheminRegexRoute.'$/', $this->ValeurParamRoute, $valeursArgsRoute))
				{
					$this->ValeurParamScriptAppele = $nom ;
					foreach($valeursArgsRoute as $i => $valeur)
					{
						$this->ArgsRouteAppelee[$nomsArgsRoute[1][0]] = $valeur ;
					}
					$this->ScriptAppele = & $script ;
					break ;
				}
			}
		}
		else
		{
			parent::DetecteScriptAppele() ;
		}
	}
	public function ObtientUrlScript($nomScript, $params=array(), $strict=1)
	{
		if(! isset($this->Scripts[$nomScript]) && $strict == 1)
			return false ;
		$url = '' ;
		if($this->ActiverRoutes == 1)
		{
			$script = & $this->Scripts[$nomScript] ;
			$cheminRouteScript = ($script->CheminRoute != '') ? $script->CheminRoute : $script->NomElementZone ;
			$url = $this->CheminRacineRoute.$cheminRouteScript ;
			if(isset($params["_route"]))
			{
				foreach($params["_route"] as $nom => $val)
				{
					$cheminRouteScript = str_ireplace('{'.$nom.'}', $val, $cheminRouteScript) ;
				}
				unset($params["_route"]) ;
			}
			$chaineParams = \Pv\Misc::http_build_query_string($params) ;
			if($chaineParams != '')
			{
				$url .= '?'.$chaineParams ;
			}
		}
		else
		{
			$chaineParams = \Pv\Misc::http_build_query_string($params) ;
			$url = $this->ObtientUrl()."?".urlencode($this->NomParamScriptAppele).'='.urlencode($nomScript) ;
			if($chaineParams != '')
			{
				$url .= '&'.$chaineParams ;
			}
		}
		return $url ;
	}
	protected function CreeTacheWebCtrlTransacts()
	{
		return new \Pv\ZoneWeb\Tache\CtrlTransacts() ;
	}
	public function RestaureMessageExecutionSession()
	{
		$msg = new \Pv\IHM\Zone\MessageExecution() ;
		if(isset($_SESSION[$this->CleMessageExecutionSession]))
		{
			$msg = unserialize($_SESSION[$this->CleMessageExecutionSession]) ;
			unset($_SESSION[$this->CleMessageExecutionSession]) ;
		}
		return $msg ;
	}
	public function SauveMessageExecutionSession($statut, $contenu, $nomScript='')
	{
		$msg = new \Pv\IHM\Zone\MessageExecution() ;
		$msg->Statut = $statut ;
		$msg->Contenu = $contenu ;
		$msg->NomScriptSource = $nomScript ;
		$_SESSION[$this->CleMessageExecutionSession] = serialize($msg) ;
	}
	public function DefinitMessageExecution($statut, $contenu, $nomScript='')
	{
		$this->SauveMessageExecutionSession($statut, $contenu, $nomScript) ;
	}
	public function ConfirmeSuccesExecution($contenu, $nomScript='')
	{
		$this->SauveMessageExecutionSession(1, $contenu, $nomScript) ;
	}
	protected function ExecuteActionPrinc()
	{
		if($this->ValeurParamActionAppelee !== false)
		{
			$this->ExecuteActionAppelee($this->ActionsPrinc) ;
		}
	}
	protected function ExecuteGestTachesWeb()
	{
		$this->DetecteTacheAppelee() ;
		if($this->EstNul($this->TacheAppelee))
		{
			$this->GestTachesWeb->Execute() ;
		}
		else
		{
			$this->TacheAppelee->Demarre() ;
		}
	}
	protected function DetecteTacheAppelee()
	{
		$this->TacheAppelee = null ;
		if(isset($_GET[$this->NomParamTacheAppelee]))
		{
			$this->ValeurParamTacheAppelee = $_GET[$this->NomParamTacheAppelee] ;
		}
		$taches = $this->GestTachesWeb->ObtientTaches() ;
		if(isset($taches[$this->ValeurParamTacheAppelee]))
		{
			$this->TacheAppelee = & $taches[$this->ValeurParamTacheAppelee] ;
		}
		else
		{
			$this->ValeurParamTacheAppelee = "" ;
		}
	}
	public function PossedeTacheAppelee()
	{
		return ($this->ValeurParamTacheAppelee != "") ? 1 : 0 ;
	}
	public function & InscritRoute($nomScript, $cheminRoute, & $script)
	{
		$script->CheminRoute = $cheminRoute ;
		return $this->InscritScript($nomScript, $script) ;
	}
	public function & InsereRoute($nomScript, $cheminRoute, $script)
	{
		$script->CheminRoute = $cheminRoute ;
		return $this->InsereScript($nomScript, $script) ;
	}
	public function & InsereTacheWeb($nom, $tache)
	{
		$this->GestTachesWeb->InsereTache($nom, $tache) ;
		return $tache ;
	}
	public function InscritTacheWeb($nom, & $tache)
	{
		$this->GestTachesWeb->InscritTache($nom, $tache) ;
	}
	public function & InsereTache($nom, $tache)
	{
		$this->GestTachesWeb->InsereTache($nom, $tache) ;
		return $tache ;
	}
	public function InscritTache($nom, & $tache)
	{
		$this->GestTachesWeb->InscritTache($nom, $tache) ;
	}
	public function ObtientUrlTache($nomTache)
	{
		$taches = $this->GestTachesWeb->ObtientTaches() ;
		if(! isset($taches[$nomTache]))
		{
			return ;
		}
		return $this->ObtientUrl()."?".urlencode($this->NomParamTacheAppelee)."=".urlencode($nomTache) ;
	}
	public function ObtientUrlAction($nomAction)
	{
		return $this->ObtientUrlActionAvantRendu($nomAction) ;
	}
	public function ObtientUrlActionAvantRendu($nomAction)
	{
		return $this->ObtientUrlActionDansListe($nomAction, $this->ActionsAvantRendu) ;
	}
	public function ObtientUrlActionApresRendu($nomAction)
	{
		return $this->ObtientUrlActionDansListe($nomAction, $this->ActionsApresRendu) ;
	}
	protected function ObtientUrlActionDansListe($nomAction, & $actions)
	{
		$url = false ;
		if(isset($actions[$nomAction]))
		{
			$url = $actions[$nomAction]->ObtientUrl() ;
		}
		return $url ;
	}
	protected function ChargeScripts()
	{
		$this->ChargeActionsAvantRendu() ;
		$this->ChargeActionsApresRendu() ;
		parent::ChargeScripts() ;
	}
	protected function ChargeDocumentsWeb()
	{
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeTraducteurActif() ;
		$this->ChargeGestTachesWeb() ;
		$this->ChargeDocumentsWeb() ;
		$this->ChargeActionsPrinc() ;
		if($this->InscrireActRedirectScriptSession == 1)
		{
			$this->ActionRedirScriptSession = $this->InsereActionAvantRendu("redirectScriptSession", new \Pv\ZoneWeb\Action\RedirScriptSession()) ;
		}
		if($this->InscrireTacheWebCtrlTransacts == 1)
		{
			$interfsPaiement = $this->ApplicationParent->InterfsPaiement() ;
			if(count($interfsPaiement) > 0)
			{
				$this->InsereTacheWeb('ctrlTransacts', $this->CreeTacheWebCtrlTransacts()) ;
			}
		}
	}
	protected function ChargeActionsPrinc()
	{
	}
	protected function ChargeGestTachesWeb()
	{
	}
	public function & ObtientActionsAvantRendu()
	{
		$actions = $this->ActionsAvantRendu ;
		return $actions ;
	}
	public function & ObtientActionsApresRendu()
	{
		$actions = $this->ActionsApresRendu ;
		return $actions ;
	}
	protected function ChargeActionsAvantRendu()
	{
	}
	protected function ChargeActionsApresRendu()
	{
	}
	protected function DetecteActionAppelee()
	{
		$this->ValeurParamActionAppelee = false ;
		if(isset($_GET[$this->NomParamActionAppelee]))
			$this->ValeurParamActionAppelee = $_GET[$this->NomParamActionAppelee] ;
	}
	protected function ExecuteActionAppelee(& $actions)
	{
		return $this->ExecuteAction($actions, $this->ValeurParamActionAppelee) ;
	}
	protected function ExecuteAction(& $actions, $valeurAction)
	{
		$nomActions = array_keys($actions) ;
		foreach($nomActions as $i => $nomAction)
		{
			$action = & $actions[$nomAction] ;
			if($action->Accepte($valeurAction))
			{
				if(! $action->EstAccessible())
				{
					$this->AfficheRenduInacessible() ;
				}
				$this->ActionsAppelees[] = & $action ;
				$action->Execute() ;
			}
		}
	}
	public function ActionAccessible($nomAction)
	{
		$actions = $this->ObtientActionsAvantRendu() ;
		if(! isset($actions[$nomAction]))
		{
			return 0 ;
		}
		return $actions[$nomAction]->EstAccessible() ;
	}
	public function InvoqueAction($valeurAction, $params=array(), $valeurPost=array(), $async=1)
	{
		$nomActions = array_keys($this->ActionsAvantRendu) ;
		foreach($nomActions as $i => $nomAction)
		{
			$action = & $this->ActionsAvantRendu[$nomAction] ;
			if($action->Accepte($valeurAction))
			{
				$action->Invoque($params, $valeurPost, $async) ;
			}
		}
	}
	protected function & CreeAction($nomClasseAction)
	{
		if(! class_exists($nomClasseAction))
		{
			die("La classe $nomClasseAction n'existe pas !!!") ;
		}
		$action = new $nomClasseAction() ;
		return $action ;
	}
	public function PossedeActionAppelee()
	{
		return ($this->ValeurParamActionAppelee != "") ? 1 : 0 ;
	}
	public function & InsereDocument($nomDoc, $document)
	{
		return $this->InscritDocument($nomDoc, $document) ;
	}
	public function & InsereDocumentWeb($nomDoc, $document)
	{
		return $this->InscritDocument($nomDoc, $document) ;
	}
	public function & InsereComposant($nomComposant, $composant)
	{
		$this->InscritComposantRendu($nomComposant, $composant) ;
		return $composant ;
	}
	public function & InsereComposantRendu($nomComposant, $composant)
	{
		$this->InscritComposantRendu($nomComposant, $composant) ;
		return $composant ;
	}
	public function & InsereActionPrinc($nomAction, $action)
	{
		$this->InscritActionPrinc($nomAction, $action) ;
		return $action ;
	}
	public function & InsereActionAvantRendu($nomAction, $action)
	{
		$this->InscritActionAvantRendu($nomAction, $action) ;
		return $action ;
	}
	public function & InsereActionApresRendu($nomAction, $action)
	{
		$this->InscritActionApresRendu($nomAction, $action) ;
		return $action ;
	}
	public function InscritComposantRendu($nomComposant, & $composant)
	{
		$this->ComposantsRendu[$nomComposant] = & $composant ;
		$composant->AdopteZone($nomComposant, $this) ;
	}
	public function InscritActionPrinc($nomAction, & $action)
	{
		$this->ActionsPrinc[$nomAction] = & $action ;
		$action->AdopteZone($nomAction, $this) ;
	}
	public function InscritActionAvantRendu($nomAction, & $action)
	{
		$this->ActionsAvantRendu[$nomAction] = & $action ;
		$action->AdopteZone($nomAction, $this) ;
	}
	public function InscritActionApresRendu($nomAction, & $action)
	{
		$this->ActionsApresRendu[$nomAction] = & $action ;
		$action->AdopteZone($nomAction, $this) ;
	}
	public function & InscritDocument($nomDoc, & $document)
	{
		$this->DocumentsWeb[$nomDoc] = & $document ;
	}
	public function CreeScript($nomClasse, $titre='')
	{
		if(! class_exists($nomClasse))
			return new \Pv\Objet\ObjetNul() ;
		$script = new $nomClasse() ;
		if($titre == '')
		{
			$titre = ucfirst($nomClasse) ;
		}
		$script->Titre = $titre ;
		$script->TitreDocument = $titre ;
		return $script ;
	}
	protected function ExecuteScriptInaccessible(& $script)
	{
		if($this->RedirigerVersConnexion == true)
		{
			if($this->InclureScriptsMembership == true && ! $this->PossedeMembreConnecte() && $this->ValeurParamScriptAppele != $this->NomScriptConnexion)
			{
				$params = array() ;
				if($this->ScriptConnexion->AutoriserUrlsRetour == 1)
				{
					$params[$this->ScriptConnexion->NomParamUrlRetour] = get_current_url() ;
				}
				\Pv\Misc::redirect_to($this->ScriptConnexion->ObtientUrlParam($params)) ;
			}
		}
		parent::ExecuteScriptInaccessible($script) ;
	}
	protected function ChargeScriptsMSConnecte()
	{
		parent::ChargeScriptsMSConnecte() ;
		foreach($this->NomScriptsEditMembership as $i => $nomScript)
		{
			$this->Scripts[$nomScript]->NomDocumentWeb = $this->NomDocumentWebEditMembership ;
		}
	}
	protected function DetecteDocumentWebSelect()
	{
		$nomDocWeb = $this->ScriptPourRendu->NomDocumentWeb ;
		if($nomDocWeb == '' || ! isset($this->DocumentsWeb[$nomDocWeb]))
		{
			$nomDocsWeb = array_keys($this->DocumentsWeb) ;
			$nomDocWeb = $nomDocsWeb[0] ;
		}
		$this->DocumentWebSelect = $this->DocumentsWeb[$nomDocWeb] ;
	}
	public function RenduDocumentWebActive()
	{
		return ($this->UtiliserDocumentWeb && count($this->DocumentsWeb) > 0) ;
	}
	public function RenduMetasDocument()
	{
		$ctn = '' ;
		if($this->EncodageDocument != '')
			$ctn .= '<meta charset="'.$this->EncodageDocument.'" />'.PHP_EOL ;
		$viewport = $this->ObtientViewportMetaDocument() ;
		if($viewport != '')
		{
			$ctn .= '<meta name="viewport" content="'.htmlspecialchars(html_entity_decode($viewport)).'">'.PHP_EOL ;
		}
		$auteur = $this->ObtientAuteurMetaDocument() ;
		if($auteur != '')
		{
			$ctn .= '<meta name="author" content="'.htmlspecialchars(html_entity_decode($auteur)).'">'.PHP_EOL ;
		}
		$metaDesc = $this->ObtientDescMetaDocument() ;
		$metaKeywords = $this->ObtientMotsCleMetaDocument() ;
		if($metaDesc != "")
		{
			$ctn .= '<meta name="description" content="'.htmlspecialchars(html_entity_decode($this->ObtientDescMetaDocument())).'">'.PHP_EOL ;
		}
		if($metaKeywords != "")
		{
			$ctn .= '<meta name="keywords" content="'.htmlspecialchars(html_entity_decode($this->ObtientMotsCleMetaDocument())).'">'.PHP_EOL ;
		}
		foreach($this->Metas as $nom => $contenu)
		{
			$ctn .= '<meta name="'.$nom.'" content="'.htmlspecialchars(html_entity_decode($contenu)).'">'.PHP_EOL ;
		}
		return $ctn ;
	}
	public function RenduDocument()
	{
		$ctn = '' ;
		if($this->RenduDocumentWebActive())
		{
			$this->InclutLibrairiesExternes() ;
			$this->DetecteDocumentWebSelect() ;
			$this->DocumentWebSelect->PrepareRendu($this) ;
			$ctn .= $this->DocumentWebSelect->RenduEntete($this) ;
			$ctn .= $this->RenduContenuCorpsDocument() ;
			$ctn .= $this->DocumentWebSelect->RenduPied($this) ;
		}
		else
		{
			$ctn .= $this->RenduDefinitionTypeDocument().PHP_EOL ;
			$ctn .= '<html lang="'.$this->LangueDocument.'">'.PHP_EOL ;
			$ctn .= $this->RenduEnteteDocument().PHP_EOL ;
			if($this->ScriptPourRendu->UtiliserCorpsDocZone)
			{
				$ctn .= $this->RenduCorpsDocument().PHP_EOL ;
			}
			else
			{
				$ctn .= $this->RenduDebutCorpsDocument().PHP_EOL ;
				$ctn .= $this->RenduContenuCorpsDocument().PHP_EOL ;
				$ctn .= $this->RenduFinCorpsDocument().PHP_EOL ;
			}
			$ctn .= $this->RenduPiedDocument().PHP_EOL ;
			$ctn .= '</html>' ;
		}
		$ctn .= $this->RenduAutoRafraich() ;
		$ctn .= $this->RenduBoiteImpression() ;
		return $ctn ;
	}
	public function RenduAutoRafraich()
	{
		$ctn = '' ;
		if($this->PourImpression == 1)
		{
			return '' ;
		}
		if($this->ActiverRafraichScript && ($this->ScriptPourRendu->DoitAutoRafraich()))
		{
			$ctn .= '<script type="text/javascript">
var idAutoRafraich = 0 ;
function execAutoRafraich() {
window.location = '.json_encode($this->ScriptPourRendu->ObtientUrlParam($this->ScriptPourRendu->ParamsAutoRafraich)).' ;
}
function annulAutoRafraich() {
clearTimeout(idAutoRafraich) ;
idAutoRafraich = 0 ;
}
function demarreAutoRafraich() {
idAutoRafraich = window.setTimeout("execAutoRafraich()", '.intval($this->ScriptPourRendu->DelaiAutoRafraich).' * 1000) ;
}
demarreAutoRafraich() ;
if(window.onblur) {
oldWindowBlur = window.onblur ;
window.onblur = function() {
	if(oldWindowBlur)
	{
		oldWindowBlur() ;
	}
	annulAutoRafraich() ;
}
}
if(window.onfocus) {
oldWindowFocus = window.onfocus ;
window.onfocus = function() {
	if(oldWindowFocus)
	{
		oldWindowFocus() ;
	}
	execAutoRafraich() ;
}
}
</script>'.PHP_EOL ;
		}
		return $ctn ;
	}
	protected function RenduBoiteImpression()
	{
		$ctn = '' ;
		if($this->PourImpression == 0)
		{
			return '' ;
		}
			$ctn .= '<script type="text/javascript">
window.print() ;
</script>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduDebutCorpsDocument()
	{
		return '<body>' ;
	}
	protected function RenduFinCorpsDocument()
	{
		return '</body>' ;
	}
	protected function RenduDefinitionTypeDocument()
	{
		return '<!DOCTYPE html>' ;
	}
	public function InclutLibrairiesExternes()
	{
		if($this->InclureNormalize)
		{
			$this->InscritLienCSS($this->CheminNormalize) ;
		}
		if($this->InclureBootstrap)
		{
			$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
			$ctnJs->Src = $this->CheminJsBootstrap ;
			array_splice($this->ContenusJs, 0, 0, array($ctnJs)) ;
			$this->InscritLienCSS($this->CheminCSSBootstrap) ;
			if($this->InclureBootstrapTheme)
			{
				$this->InscritLienCSS($this->CheminCSSBootstrapTheme) ;
			}
		}
		if($this->InclureFontAwesome)
		{
			$this->InscritLienCSS($this->CheminFontAwesome) ;
		}
		if($this->InclureJQueryUi)
		{
			$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
			$ctnJs->Src = $this->CheminJsJQueryUi ;
			array_splice($this->ContenusJs, 0, 0, array($ctnJs)) ;
			$this->InscritLienCSS($this->CheminCSSJQueryUi) ;
		}
		if($this->InclureJQueryUi || $this->InclureJQuery)
		{
			$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
			$ctnJs->Src = $this->CheminJQuery ;
			$lstCtnJs = array($ctnJs) ;
			if($this->InclureJQueryMigrate)
			{
				$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
				$ctnJs->Src = $this->CheminJQueryMigrate ;
				$lstCtnJs[] = $ctnJs ;
				if($this->InclureJQueryMigrate3 == 1 && $this->CheminJQueryMigrate3 != '')
				{
					$this->InscritLienJs($this->CheminJQueryMigrate3) ;
				}
			}
			array_splice($this->ContenusJs, 0, 0, $lstCtnJs) ;
		}
	}
	public function ObtientTitreDocument()
	{
		$titreDocument = $this->ScriptPourRendu->ObtientTitreDocument() ;
		return (($titreDocument != "") ? $titreDocument : $this->TitreDocument) ;
	}
	public function ObtientMotsCleMetaDocument()
	{
		return ($this->ScriptPourRendu->MotsCleMeta != "") ? $this->ScriptPourRendu->MotsCleMeta : $this->MotsCleMeta ;
	}
	public function ObtientViewportMetaDocument()
	{
		return ($this->ScriptPourRendu->ViewportMeta != "") ? $this->ScriptPourRendu->ViewportMeta : $this->ViewportMeta ;
	}
	public function ObtientAuteurMetaDocument()
	{
		return ($this->ScriptPourRendu->AuteurMeta != "") ? $this->ScriptPourRendu->AuteurMeta : $this->AuteurMeta ;
	}
	public function ObtientDescMetaDocument()
	{
		return ($this->ScriptPourRendu->DescriptionMeta != "") ? $this->ScriptPourRendu->DescriptionMeta : $this->DescriptionMeta ;
	}
	public function RenduLienFavicon()
	{
		$ctn = '' ;
		if($this->CheminFavicon == '')
		{
			return '' ;
		}
		$infosFich = pathinfo($this->CheminFavicon) ;
		if($infosFich["extension"] != "ico")
		{
			$extMime = ($infosFich["extension"] == 'jpg') ? 'jpeg' : $infosFich["extension"] ;
			$ctn .= '<link rel="icon" type="image/'.$extMime.'" href="'.$this->CheminFavicon.'">' ;
		}
		else
		{
			$ctn .= '<link rel="icon" href="'.$this->CheminFavicon.'">' ;
		}
		$ctn .= PHP_EOL ;
		return $ctn ;
	}
	public function RenduLienBase()
	{
		$ctn = '' ;
		if($this->UrlBase == '')
		{
			return '' ;
		}
		$ctn = '<base href="'.$this->UrlBase.'">' ;
		return $ctn ;
	}
	public function RenduTitre()
	{
		$script = & $this->ScriptPourRendu ;
		$tagTitre = $this->TagTitre ;
		if($script->TagTitre != '')
		{
			$tagTitre = $script->TagTitre ;
		}
		if($tagTitre == '')
		{
			$tagTitre = 'div' ;
		}
		if(! $this->InclureRenduTitre || ! $script->InclureRenduTitre)
		{
			return '' ;
		}
		$ctn = '' ;
		$ctn .= '<'.$tagTitre.' class="titre">' ;
		$ctnIcone = $script->RenduIcone() ;
		if($ctnIcone != '')
		{
			$ctn .= $ctnIcone.'&nbsp;&nbsp;' ;
		}
		$ctn .= $script->Titre ;
		$ctn .= '</'.$tagTitre.'>' ;
		return $ctn ;
	}
	public function RenduEnteteDocument()
	{
		$this->InclutLibrairiesExternes() ;
		$ctn = '' ;
		$ctn .= '<head>'.PHP_EOL ;
		$ctn .= $this->RenduLienBase() ;
		$ctn .= $this->RenduLienFavicon() ;
		$ctn .= $this->RenduMetasDocument() ;
		$ctn .= '<title>'.$this->ObtientTitreDocument().'</title>'.PHP_EOL ;
		for($i=0; $i<count($this->ContenusCSS); $i++)
		{
			$ctnCSS = $this->ContenusCSS[$i] ;
			$ctn .= $ctnCSS->RenduDispositif().PHP_EOL ;
		}
		if($this->InclureCtnJsEntete)
		{
			$ctn .= $this->RenduCtnJs().PHP_EOL ;
		}
		$ctn .= ($this->RenduExtraHead != '') ? $this->RenduExtraHead. PHP_EOL : '' ;
		$ctn .= '</head>' ;
		return $ctn ;
	}
	public function UrlRedirScriptSession($urlDefaut='')
	{
		if($this->InscrireActRedirectScriptSession == 0)
		{
			return '?' ;
		}
		if($this->AdrScriptSession->ChaineGet == '' && $urlDefaut != '')
		{
			$partsUrl = explode('?', $urlDefaut, 2) ;
			if(count($partsUrl) == 2)
			{
				$this->AdrScriptSession->ChaineGet = '?'.$partsUrl[1] ;
				$this->AdrScriptSession->Sauvegarde($this) ;
			}
		}
		return $this->ActionRedirScriptSession->ObtientUrl() ;
	}
	protected function RenduCtnJs()
	{
		$ctn = '' ;
		// print_r($this->ContenusJs) ;
		for($i=0; $i<count($this->ContenusJs); $i++)
		{
			$ctnJs = $this->ContenusJs[$i] ;
			$ctn .= $ctnJs->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	public function RenduCtnJsPied()
	{
		$ctn = '' ;
		for($i=0; $i<count($this->ContenusJsPied); $i++)
		{
			$ctnJs = $this->ContenusJsPied[$i] ;
			$ctn .= $ctnJs->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	public function RenduPiedDocument()
	{
	}
	protected function RenduEnteteCorpsDocument()
	{
		$ctn = '' ;
		$ctn .= '<body>' ;
		return $ctn ;
	}
	protected function RenduContenuCorpsDocument()
	{
		$this->ScriptPourRendu->PrepareRendu() ;
		return $this->ScriptPourRendu->RenduDispositif() ;
	}
	protected function RenduPiedCorpsDocument()
	{
		$ctn = '' ;
		if($this->InclureCtnJsEntete == 0)
		{
			$ctn .= $this->RenduCtnJs() ;
		}
		$ctn .= $this->RenduCtnJsPied() ;
		$ctn .= '</body>' ;
		return $ctn ;
	}
	protected function RenduCorpsDocument()
	{
		$ctn = '' ;
		$ctn .= $this->RenduEnteteCorpsDocument().PHP_EOL ;
		$ctn .= $this->RenduContenuCorpsDocument().PHP_EOL ;
		$ctn .= $this->RenduPiedCorpsDocument() ;
		return $ctn ;
	}
	public function DemarreTachesWeb()
	{
		$this->GestTachesWeb->Execute() ;
	}
	protected function PrepareComposantsRendu()
	{
		$nomsComp = array_keys($this->ComposantsRendu) ;
		foreach($nomsComp as $i => $nomComp)
		{
			$this->ComposantsRendu[$nomComp]->PrepareZone() ;
		}
	}
	public function ExecuteScript(& $script)
	{
		$this->RapporteRequeteEnvoyee() ;
		if($script->EstBienRefere() == 0)
		{
			$this->ExecuteScriptMalRefere($script) ;
			return ;
		}
		$this->VerifieValiditeMotPasse($script) ;
		if($script->EstDisponible() == 0)
		{
			$this->ExecuteScriptIndisponible($script) ;
			return ;
		}
		if($script->EstAccessible() == 0)
		{
			// print_r(get_class($this->Membership->MemberLogged)) ;
			// print_r(get_class($script)) ;
			$this->ExecuteScriptInaccessible($script) ;
			return ;
		}
		$this->ChargeScriptSession() ;
		$this->DetermineEnvironnement($script) ;
		$this->ExecuteRequeteSoumise($script) ;
		// $script->PrepareRendu() ;
		$this->PrepareComposantsRendu() ;
		$this->ScriptPourRendu = $script ;
		$this->RenduEnCours = 1 ;
		if($this->ValeurParamActionAppelee !== false)
		{
			$this->ExecuteActionAppelee($this->ActionsAvantRendu) ;
		}
		if($this->AnnulerRendu)
		{
			$this->RenduEnCours = 0 ;
			$this->ScriptPourRendu = null ;
			return ;
		}
		$ctn = $this->RenduDocument() ;
		/*
		if($this->ValeurParamActionAppelee !== false)
		{
			$this->ExecuteActionAppelee($this->ActionsApresRendu) ;
		}
		*/
		$this->RenduEnCours = 0 ;
		$this->ScriptPourRendu = null ;
		if(! $this->PossedeActionAppelee())
		{
			$this->FixeAdrScriptSession($script) ;
		}
		echo $ctn ;
	}
	protected function ChargeScriptSession()
	{
		$adr = $this->AdrScriptSession->ExporteZone($this) ;
		if($adr != null)
		{
			$this->AdrScriptSession = $adr ;
		}
	}
	protected function FixeAdrScriptSession(& $script)
	{
		if($script->EstScriptSession)
		{
			$this->AdrScriptSession->ImporteRequeteHttp($this) ;
		}
	}
	protected function ExecuteRequeteSoumise(& $script)
	{
	}
	// Incrire un fichier CSS
	public function InscritContenuCSS($contenu, $media="")
	{
		$ctnCSS = new \Pv\ZoneWeb\BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		$ctnCSS->Media = $media ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritLienCSS($href, $media="")
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		$ctnCSS->Media = $media ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritContenuJs($contenu)
	{
		$ctnJs = new BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritContenuJsCmpIE($contenu, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJs($src)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJsCmpIE($src, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritContenuJsPied($contenu)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		$this->ContenusJsPied[] = $ctnJs ;
	}
	public function InscritContenuJsPiedCmpIE($contenu, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJsPied[] = $ctnJs ;
	}
	public function InscritLienJsPied($src)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		$this->ContenusJsPied[] = $ctnJs ;
	}
	public function InscritLienJsPiedCmpIE($src, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJsPied[] = $ctnJs ;
	}
	public function RenduLienCSS($href)
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuCSS($contenu)
	{
		$ctnCSS = new BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuJsInclus($contenu)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduContenuJsCmpIEInclus($contenu, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsInclus($src)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsCmpIEInclus($src, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function TailleImageAjustee($cheminImage, $largeurMax, $hauteurMax)
	{
		return $GLOBALS["CommonGDManipulator"]->getAdjustedDimsFromFile($cheminImage, $largeurMax, $hauteurMax) ;
	}
	public function RenduImageAjustee($cheminImage, $largeurMax, $hauteurMax, $autresAttrsHtml='')
	{
		$dims = $GLOBALS["CommonGDManipulator"]->getAdjustedDimsFromFile($cheminImage, $largeurMax, $hauteurMax) ;
		return '<img src="'.htmlspecialchars($cheminImage).'" width="'.$largeurMax.'"'.(($autresAttrsHtml != '') ? ' '.$autresAttrsHtml : '').' />' ;
	}
	public function RenduRedirectScriptSession($urlDefaut = '')
	{
		$adr = & $this->AdrScriptSession ;
		$ctn = '' ;
		if($adr->ChaineGet != '')
		{
			$ctn .= '<!doctype html>
<html>
<head><title>Redirection en cours...</title></head>
<body>
<form style="display:none" id="FormRetour" action="'.htmlspecialchars($adr->ChaineGet).'" method="post">' ;
			foreach($adr->DonneesPost as $nom => $valeur)
			{
				if(is_array($valeur))
				{
					$valeur = join(",", $valeur) ;
				}
				$ctn .= '<input type="hidden" name="'.htmlspecialchars($nom).'" value="'.htmlspecialchars($valeur).'" />' ;
				
			}
			$ctn .= '<input type="submit" value="envoyer" /></form>
<script language="javascript">
document.getElementById("FormRetour").submit() ;
</script>
</body>
</html>' ;
			echo $ctn ;
			exit ;
		}
		elseif($urlDefaut != '')
		{
			\Pv\Misc::redirect_to($urlDefaut) ;
		}
	}
	public function ObtientCheminDossierTaches()
	{
		if($this->NomDossierModelesEval === null)
		{
			return null ;
		}
		return dirname($this->ObtientCheminFichierRelatif()).DIRECTORY_SEPARATOR.$this->NomDossierModelesEval ;
	}
	public function ModelesEvalActive()
	{
		return ($this->UtiliserModelesEval == 1 && $this->NomDossierModelesEval !== null) ;
	}
	public function RenduModeleEval($cheminModele)
	{
		ob_start() ;
		$zone = & $this ;
		if($this->EstPasNul($this->ScriptPourRendu))
		{
			$script = & $this->ScriptPourRendu ;
		}
		include $cheminModele ;
		$ctn = ob_get_clean() ;
		return $ctn ;
	}
	public function CreeTablPrinc()
	{
		return new \Pv\ZoneWeb\TableauDonnees\TableauDonnees() ;
	}
	public function CreeGrillePrinc()
	{
		return new \Pv\ZoneWeb\TableauDonnees\GrilleDonnees() ;
	}
	public function CreeRepetPrinc()
	{
		return new \Pv\ZoneWeb\TableauDonnees\RepeteurDonnees() ;
	}
	public function CreeTableauDonneesPrinc()
	{
		return $this->CreeTablPrinc() ;
	}
	public function CreeFormPrinc()
	{
		return new \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees() ;
	}
	public function CreeFormulaireDonneesPrinc()
	{
		return $this->CreeFormPrinc() ;
	}
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
	public function & InsereFormPrinc($nom='formPrinc')
	{
		$comp = $this->InsereComposant($nom, $this->CreeFormPrinc()) ;
		return $comp ;
	}
	public function & InsereTablPrinc($nom='tablPrinc')
	{
		$comp = $this->InsereComposant($nom, $this->CreeTablPrinc()) ;
		return $comp ;
	}
	public function & InsereGrillePrinc($nom='grillePrinc')
	{
		$comp = $this->InsereComposant($nom, $this->CreeGrillePrinc()) ;
		return $comp ;
	}
	public function & InsereRepetPrinc($nom='repetPrinc')
	{
		$comp = $this->InsereComposant($nom, $this->CreeRepetPrinc()) ;
		return $comp ;
	}
	public function AppliqueCommande(& $cmd, & $script)
	{
		$cmd->ConfirmeSucces() ;
	}
	public function AppliqueActionCommande(& $actCmd)
	{
		$this->AppliqueActCmd($actCmd) ;
	}
	public function AppliqueActCmd(& $actCmd)
	{
	}
	public function ValideCritere(& $critere, & $script)
	{
		return true ;
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		return $ligneDonnees ;
	}
	public function DessineFiltres(& $composant, $parametres)
	{
		return '' ;
	}
	protected function AfficheRenduIndisponible(& $script, $msg)
	{
		$ctn = '' ;
		$this->ScriptPourRendu = & $script ;
		$ctn .= $this->RenduDefinitionTypeDocument().PHP_EOL ;
		$ctn .= $this->RenduEnteteDocument() ;
		$ctn .= '<body>'.PHP_EOL ;
		$ctn .= '<div style="color:red">'.$msg.'</div>'.PHP_EOL ;
		$ctn .= $this->RenduPiedDocument().PHP_EOL ;
		$ctn .= '</body>'.PHP_EOL ;
		$ctn .= '</html>' ;
		echo $ctn ;
		exit ;
	}
}
