<?php

namespace Pv\ZoneWeb\Script ;

class Script extends \Pv\IHM\Zone\Script
{
	public $EstScriptSession = false ;
	public $Titre ;
	public $NomDocumentWeb ;
	public $TitreDocument ;
	public $MotsCleMeta ;
	public $DescriptionMeta ;
	public $ViewportMeta ;
	public $AuteurMeta ;
	public $Chemin = array("") ;
	public $Description = "" ;
	public $ComposantSpecifique = null ;
	public $Composant1 = null ;
	public $Composant2 = null ;
	public $Composant3 = null ;
	public $DetectIconeCorresp = 0 ;
	public $CheminIcone = null ;
	public $UrlsReferantsSurs = array() ;
	public $HotesReferantsSurs = array() ;
	public $RefererHoteLocal = 0 ;
	public $RefererUrlLocale = 0 ;
	public $ScriptsReferantsSurs = array() ;
	public $RefererScriptLocal = 0 ;
	public $UtiliserCorpsDocZone = 1 ;
	public $InclureRenduTitre = 1 ;
	public $InclureRenduDescription = 1 ;
	public $InclureRenduMessageExecution = 1 ;
	public $InclureRenduIcone = 1 ;
	public $InclureRenduChemin = 1 ;
	public $ActiverAutoRafraich = 0 ;
	public $DelaiAutoRafraich = 0 ;
	public $TagTitre ;
	public $ParamsAutoRafraich = array() ;
	public $Imprimable = 0 ;
	public $NomActionImprime = "imprimeScript" ;
	public $ActionImprime ;
	public $MessageExecution ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->MessageExecution = new \Pv\IHM\Zone\MessageExecution() ;
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeActionsAuto() ;
	}
	protected function CreeActionImprime()
	{
		return new \Pv\ZoneWeb\Action\ImprimeScript() ;
	}
	protected function ChargeActionsAuto()
	{
		if($this->Imprimable)
		{
			$this->ActionImprime = $this->InsereActionAvantRendu($this->NomActionImprime, $this->CreeActionImprime()) ;
		}
	}
	public function DoitAutoRafraich()
	{
		return $this->ActiverAutoRafraich && $this->DelaiAutoRafraich > 0;
	}
	public function EstBienRefere()
	{
		return 1 ;
	}
	public function & InsereComposantRendu($nomComp, $comp)
	{
		return $this->InsereComposant($nomComp, $comp) ;
	}
	public function & InsereComposant($nomComp, $comp)
	{
		$compResult = $this->ZoneParent->InsereComposantRendu($this->NomElementZone."_".$nomComp, $comp) ;
		$compResult->NomElementScript = $nomComp ;
		$compResult->ScriptParent = & $this ;
		return $compResult ;
	}
	public function & InsereActionPrinc($nomAction, $action)
	{
		$actionResult = $this->ZoneParent->InsereActionPrinc($this->NomElementZone."_".$nomAction, $action) ;
		$actionResult->NomElementScript = $nomAction ;
		$actionResult->ScriptParent = & $this ;
		return $actionResult ;
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
	public function InscritActionAvantRendu($nomAction, & $action)
	{
		$this->ZoneParent->ActionsAvantRendu[$nomAction] = & $action ;
		$action->AdopteScript($nomAction, $this) ;
	}
	public function InscritActionApresRendu($nomAction, & $action)
	{
		$this->ZoneParent->ActionsApresRendu[$nomAction] = & $action ;
		$action->AdopteScript($nomAction, $this) ;
	}
	public function InvoqueAction($valeurAction, $params=array(), $valeurPost=array(), $async=1)
	{
		return $this->ZoneParent->InvoqueAction($valeurAction, $params, $valeurPost, $async) ;
	}
	public function & CreeFiltreRef($nom, & $filtreRef)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Ref() ;
		$filtre->Source = & $filtreRef ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreFixe($nom, $valeur)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Fixe() ;
		$filtre->ValeurParDefaut = $valeur ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreCookie($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Cookie() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreSession($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Session() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreMembreConnecte($nom, $nomParamLie='')
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\MembreConnecte() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->NomParametreLie = $nomParamLie ;
		return $filtre ;
	}
	public function & CreeFiltreHttpUpload($nom, $cheminDossierDest="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpUpload() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->CheminDossier = $cheminDossierDest ;
		return $filtre ;
	}
	public function & CreeFiltreHttpGet($nom, $exprDonnees="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpGet() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ExpressionDonnees = $exprDonnees ;
		return $filtre ;
	}
	public function & CreeFiltreHttpPost($nom, $exprDonnees="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpPost() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ExpressionDonnees = $exprDonnees ;
		return $filtre ;
	}
	public function & CreeFiltreHttpRequest($nom, $exprDonnees="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpRequest() ;
		$filtre->AdopteScript($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ExpressionDonnees = $exprDonnees ;
		return $filtre ;
	}
	public function PrepareRendu()
	{
	}
	public function RenduIcone()
	{
		$ctn = '' ;
		if($this->ZoneParent->InclureRenduIcone && $this->InclureRenduTitre)
		{
			$cheminIcone = ($this->CheminIcone != '' && file_exists($this->CheminIcone)) ? $this->CheminIcone : $this->ZoneParent->CheminIconeScript ;
			if($cheminIcone != '')
			{
				$ctn .= '<img src="'.$cheminIcone.'" height="22" />' ;
			}
		}
		return $ctn ;
	}
	public function RenduTitre()
	{
		return $this->ZoneParent->RenduTitre() ;
	}
	public function ObtientTitreDocument()
	{
		return $this->TitreDocument ;
	}
	public function RenduChemin()
	{
		if(! $this->ZoneParent->InclureRenduChemin || ! $this->InclureRenduChemin)
		{
			return '' ;
		}
	}
	public function DefinitMessageExecution($statut, $contenu)
	{
		$this->MessageExecution->Statut = $statut ;
		$this->MessageExecution->Contenu = $contenu ;
	}
	public function ObtientMessageExecution()
	{
		$msg = $this->MessageExecution ;
		if($msg->NonRenseigne() || $msg->EstVide())
		{
			$msg = $this->ZoneParent->RestaureMessageExecutionSession() ;
		}
		return $msg ;
	}
	public function RenduMessageExecution()
	{
		if(! $this->ZoneParent->InclureRenduMessageExecution || ! $this->InclureRenduMessageExecution)
		{
			return '' ;
		}
		$msg = $this->ObtientMessageExecution() ;
		if($msg->NonRenseigne() || $msg->EstVide())
		{
			return '' ;
		}
		$classeCSSMsgExecSucces = $this->ZoneParent->ClasseCSSMsgExecSucces ;
		$classeCSSMsgExecErreur = $this->ZoneParent->ClasseCSSMsgExecErreur ;
		$ctn = '<div class="'.(($msg->Succes()) ? $classeCSSMsgExecSucces : $classeCSSMsgExecErreur).'">'.$msg->Contenu.'</div>' ;
		return $ctn ;
	}
	public function RenduDescription()
	{
		if(! $this->ZoneParent->InclureRenduDescription || ! $this->InclureRenduDescription)
		{
			return '' ;
		}
	}
	public function RenduSpecifique()
	{
	}
	public function RenduComposant1()
	{
	}
	public function RenduComposant2()
	{
	}
	public function RenduComposant3()
	{
	}
	public function RenduDispositif()
	{
		return $this->RenduDispositifBrut() ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduChemin().PHP_EOL ;
		$ctn .= $this->RenduTitre().PHP_EOL ;
		$ctn .= $this->RenduMessageExecution().PHP_EOL ;
		$ctn .= $this->RenduDescription().PHP_EOL ;
		$ctn .= $this->RenduSpecifique() ;
		return $ctn ;
	}
	public function ObtientUrl()
	{
		return $this->ZoneParent->ObtientUrlScript($this->NomElementZone) ;
	}
	public function ObtientUrlParam($params=array())
	{
		return $this->ZoneParent->ObtientUrlScript($this->NomElementZone, $params) ;
	}
	public function ObtientUrlFmt($params=array(), $autresParams=array())
	{
		$url = $this->ZoneParent->ObtientUrlScript($this->NomElementZone, $autresParams) ;
		foreach($params as $nom => $val)
		{
			$url .= '&'.urlencode($nom).'='.$val ;
		}
		return $url ;
	}
	public function AdopteZone($nom, & $zone)
	{
		parent::AdopteZone($nom, $zone) ;
		if($this->DetectIconeCorresp || $this->ZoneParent->DetectIconeCorresp)
		{
			$cheminIcone = $this->ZoneParent->CheminDossierIconeCorresp.'/'.$this->NomElementZone.'.'.$this->ZoneParent->ExtIconeCorresp ;
			if($this->CheminIcone == '' && file_exists($cheminIcone))
			{
				$this->CheminIcone = $cheminIcone ;
			}
		}
	}
	public function ImpressionEnCours()
	{
		return $this->EstPasNul($this->ZoneParent) && $this->ZoneParent->ImpressionEnCours() ;
	}
	public function AppliqueCommande(& $cmd)
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
	public function ValideCritere(& $critere)
	{
		return true ;
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		return $ligneDonnees ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		return '' ;
	}
	public function DessineCommandes(& $dessin, & $composant, $parametres)
	{
		return '' ;
	}
	// Incrire un fichier CSS
	public function InscritContenuCSS($contenu, $media="")
	{
		return $this->ZoneParent->InscritContenuCSS($contenu, $media) ;
	}
	public function InscritLienCSS($href, $media="")
	{
		return $this->ZoneParent->InscritLienCSS($href, $media) ;
	}
	public function InscritContenuJs($contenu)
	{
		return $this->ZoneParent->InscritContenuJs($contenu) ;
	}
	public function InscritContenuJsCmpIE($contenu, $versionMin=9)
	{
		return $this->ZoneParent->InscritContenuJsCmpIE($contenu, $versionMin) ;
	}
	public function InscritLienJs($src)
	{
		return $this->ZoneParent->InscritLienJs($src) ;
	}
	public function InscritLienJsCmpIE($src, $versionMin=9)
	{
		return $this->ZoneParent->InscritLienJsCmpIE($src, $versionMin) ;
	}
	public function InscritContenuJsPied($contenu)
	{
		return $this->ZoneParent->InscritContenuJsPied($contenu) ;
	}
	public function InscritContenuJsPiedCmpIE($contenu, $versionMin=9)
	{
		return $this->ZoneParent->InscritContenuJsPiedCmpIE($contenu, $versionMin) ;
	}
	public function InscritLienJsPied($src)
	{
		return $this->ZoneParent->InscritLienJsPied($src) ;
	}
	public function InscritLienJsPiedCmpIE($src, $versionMin=9)
	{
		return $this->ZoneParent->InscritLienJsPiedCmpIE($src, $versionMin) ;
	}
	public function RenduLienCSS($href)
	{
		return $this->ZoneParent->RenduLienCSS($href) ;
	}
	public function RenduContenuCSS($contenu)
	{
		return $this->ZoneParent->RenduContenuCSS($contenu) ;
	}
	public function RenduContenuJsInclus($contenu)
	{
		return $this->ZoneParent->RenduContenuJsInclus($contenu) ;
	}
	public function RenduContenuJsCmpIEInclus($contenu, $versionMin=9)
	{
		return $this->ZoneParent->RenduContenuJsCmpIEInclus($contenu, $versionMin) ;
	}
	public function RenduLienJsInclus($src)
	{
		return $this->ZoneParent->RenduLienJsInclus($src) ;
	}
	public function RenduLienJsCmpIEInclus($src, $versionMin=9)
	{
		return $this->ZoneParent->RenduLienJsCmpIEInclus($src, $versionMin) ;
	}
	public function CreeFormulaireDonneesPrinc()
	{
		return $this->ZoneParent->CreeFormulaireDonneesPrinc() ;
	}
	public function CreeFormPrinc()
	{
		return $this->ZoneParent->CreeFormulaireDonneesPrinc() ;
	}
	public function CreeTableauDonneesPrinc()
	{
		return $this->ZoneParent->CreeTableauDonneesPrinc() ;
	}
	public function CreeGrillePrinc()
	{
		return $this->ZoneParent->CreeGrillePrinc() ;
	}
	public function CreeRepetPrinc()
	{
		return $this->ZoneParent->CreeRepetPrinc() ;
	}
	public function CreeTablPrinc()
	{
		return $this->ZoneParent->CreeTableauDonneesPrinc() ;
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

}
