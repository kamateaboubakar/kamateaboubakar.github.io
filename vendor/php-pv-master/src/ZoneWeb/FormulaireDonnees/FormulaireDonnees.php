<?php

namespace Pv\ZoneWeb\FormulaireDonnees ;

class FormulaireDonnees extends \Pv\ZoneWeb\ComposantRendu\Parametrable
{
	public $TypeComposant = "FormulaireDonneesHTML" ;
	public $Largeur = 0 ;
	public $UtiliserLargeur = true ;
	public $IdTagForm = "" ;
	public $InclureElementEnCours = true ;
	public $InclureTotalElements = true ;
	public $RequeteSelection = "" ;
	public $FiltresSelectionObligatoires = true ;
	public $FiltresGlobauxSelection = array() ;
	public $FiltresLigneSelection = array() ;
	public $FiltresEdition = array() ;
	public $Commandes = array() ;
	public $ElementsEnCours = array() ;
	public $ElementEnCours = array() ;
	public $ElementEnCoursTrouve = 0 ;
	public $CommandeSelectionnee = null ;
	public $AnnulerCommandeSelectionnee = 0 ;
	public $AnnulerRenduFiltres = 0 ;
	public $ClasseCSSSucces = "Succes" ;
	public $ClasseCSSErreur = "Erreur" ;
	public $Titre = "" ;
	public $AlignTitre = "left" ;
	public $NomClasseCSS = "" ;
	public $ClasseCSSTitre = "titre" ;
	public $ClasseCSSDescription = "description" ;
	public $ClasseCSSDispositif = "" ;
	public $ClasseCSSBlocCommandes ;
	public $ClasseCSSFormulaireFiltres ;
	public $Description = "" ;
	public $MessageException ;
	public $InscrireCommandeExecuter = 1 ;
	public $InscrireCommandeAnnuler = 1 ;
	public $EncoderCaracteresZone = 1 ;
	public $NomClasseCommandeExecuter = "\Pv\ZoneWeb\Commande\Executer" ;
	public $NomClasseCommandeAnnuler = "\Pv\ZoneWeb\Commande\Annuler" ;
	public $NomCommandeExecuter = "executer" ;
	public $NomCommandeAnnuler = "annuler" ;
	public $LibelleCommandeExecuter = "Executer" ;
	public $LibelleCommandeAnnuler = "Annuler" ;
	public $NomCommandeEntree = "executer" ;
	public $CommandeAnnuler = null ;
	public $CommandeExecuter = null ;
	public $DessinateurFiltresEdition = null ;
	public $DessinateurBlocCommandes = null ;
	public $CacherBlocCommandes = false ;
	public $AfficherCommandesAucunElement = false ;
	public $AnnulerLiaisonParametre = false ;
	public $DispositionComposants = array(4, 3, 1, 2) ;
	public $MessageResultatCalculElements = "" ;
	public $MessageAucunElement = "Aucun &eacute;l&eacute;ment trouv&eacute;" ;
	public $CacherFormulaireFiltres = false ;
	public $CacherFormulaireFiltresApresCmd = false ;
	public $MaxFiltresEditionParLigne = 0 ;
	public $InclureRenduLibelleFiltresEdition = 1 ;
	public $CommandeSelectionneeExec = 0 ;
	public $MsgExecSuccesCommandeExecuter = "" ;
	public $MsgExecEchecCommandeExecuter = "" ;
	public $NomScriptExecSuccesCommandeExecuter = "" ;
	public $ParamsScriptExecSuccesCommandeExecuter = array() ;
	public $AlignBlocCommandes ;
	public $ActCmdsCommandeExecuter = array() ;
	public $CriteresCommandeExecuter = array() ;
	public $ClasseCSSCommandeExecuter = "" ;
	public $ClasseBoutonCommandeExecuter = "" ;
	public $MsgExecSuccesCommandeAnnuler = "" ;
	public $MsgExecEchecCommandeAnnuler = "" ;
	public $ClasseCSSCommandeAnnuler = "" ;
	public $ClasseBoutonCommandeAnnuler = "" ;
	public $ActCmdsCommandeAnnuler = array() ;
	public $CriteresCommandeAnnuler = array() ;
	public $PopupMessageExecution = 0 ;
	public $CacherMessageExecution = 0 ;
	public $ElementsEnCoursEditables = 0 ;
	public $TotalElementsEditables = 1 ;
	public $ActCmdTailleImage ;
	public function PrepareZone()
	{
		$this->ExecuteCommandeSelectionnee() ;
	}
	public function & InsereFltEditRef($nom, & $filtreRef, $colLiee='', $nomComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditFixe($nom, $valeur, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditCookie($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditSession($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditMembreConnecte($nom, $nomParamLie='', $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpUpload($nom, $cheminDossierDest="", $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpGet($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpPost($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpRequest($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditCaptcha($nom, $nomCmd="", $nomClsComp="\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneCaptcha")
	{
		$flt = $this->InsereFltEditHttpPost($nom) ;
		$comp = $flt->DeclareComposant($nomClsComp) ;
		if($nomClsCmd == '' && $this->InscrireCommandeExecuter == 1)
		{
			$critr = $this->CommandeExecuter->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideCaptcha()) ;
			$critr->FltCaptchaParent = & $flt ;
		}
		elseif(isset($this->Commandes[$nomCmd]))
		{
			$critr = $this->Commandes[$nomCmd]->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideCaptcha()) ;
			$critr->FltCaptchaParent = & $flt ;
		}
		return $flt ;
	}
	public function & InsereFltEditRecaptcha($nom, $nomCmd="")
	{
		return $this->InsereFltEditCaptcha($nom, $nomCmd, "\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha2") ;
	}
	public function & InsereFltLgSelectRef($nom, & $filtreRef, $exprDonnees='', $nomComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectFixe($nom, $valeur, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectCookie($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectSession($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectMembreConnecte($nom, $nomParamLie='', $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectHttpUpload($nom, $cheminDossierDest="", $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectHttpGet($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectHttpPost($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltLgSelectHttpRequest($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectRef($nom, & $filtreRef, $exprDonnees='', $nomComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectFixe($nom, $valeur, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectCookie($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectSession($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectMembreConnecte($nom, $nomParamLie='', $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpUpload($nom, $cheminDossierDest="", $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpGet($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpPost($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpRequest($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresLigneSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalRef($nom, & $filtreRef, $exprDonnees='', $nomComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalFixe($nom, $valeur, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalCookie($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalSession($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalMembreConnecte($nom, $nomParamLie='', $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalHttpUpload($nom, $cheminDossierDest="", $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalHttpGet($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalHttpPost($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectGlobalHttpRequest($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresGlobauxSelection[] = & $flt ;
		return $flt ;
	}
	protected function LieColDonnees(& $flt, $nomParamDonnees='', $nomColLiee='')
	{
		$flt->NomColonneLiee = $nomColLiee ;
		$flt->NomParametreDonnees = $nomParamDonnees ;
	}
	protected function DetecteParametresLocalisation()
	{
	}
	public function PrepareRendu()
	{
		parent::PrepareRendu() ;
		$this->DetecteParametresLocalisation() ;
		$this->CalculeElementsRendu() ;
		$this->PrepareRenduPourCmds() ;
		$this->PrepareLiaisonParametres() ;
	}
	public function ReinitParametres()
	{
		foreach($this->FiltresEdition as $i => & $filtre)
		{
			$filtre->DejaLie = 0 ;
		}
	}
	public function AnnuleLiaisonParametres()
	{
		foreach($this->FiltresEdition as $i => & $filtre)
		{
			$filtre->DejaLie = 0 ;
			$filtre->NePasLierParametre = 1 ;
		}
	}
	protected function PrepareLiaisonParametres()
	{
		if($this->AnnulerLiaisonParametre)
		{
			$this->AnnuleLiaisonParametres() ;
		}
	}
	protected function PrepareRenduPourCmds()
	{
		$nomCmds = array_keys($this->Commandes) ;
		foreach($nomCmds as $i => $nomCmd)
		{
			$nomCritrs = array_keys($this->Commandes[$nomCmd]->Criteres) ;
			$this->Commandes[$nomCmd]->PrepareRendu($this) ;
			foreach($nomCritrs as $j => $nomCritr)
			{
				$critr = & $this->Commandes[$nomCmd]->Criteres[$nomCritr] ;
				$critr->PrepareRendu($this) ;
			}
		}
	}
	protected function CalculeTotalElements()
	{
		$this->TotalElements = $this->FournisseurDonnees->CompteElements(array(), $this->FiltresGlobauxSelection) ;
		if($this->FournisseurDonnees->ExceptionTrouvee())
		{
			$this->MessageExecution = $this->FournisseurDonnees->DerniereException->Message ;
		}
		// print_r($this->FournisseurDonnees) ;
		// $this->AfficheExceptionFournisseurDonnees() ;
	}
	protected function CalculeElementsEnCours()
	{
		$filtresSelection = $this->FiltresGlobauxSelection ;
		array_splice($filtresSelection, count($filtresSelection), 0, $this->FiltresLigneSelection) ;
		$this->ElementsEnCours = $this->FournisseurDonnees->SelectElements($this->ExtraitColonnesDonnees($this->FiltresEdition), $filtresSelection) ;
		if($this->FournisseurDonnees->ExceptionTrouvee())
		{
			$this->MessageException = $this->FournisseurDonnees->MessageException() ;
		}
		else
		{
			foreach($this->ElementsEnCours as $ix => $lgn)
			{
				foreach($lgn as $n => $v)
				{
					if($v === null)
					{
						$this->ElementsEnCours[$ix][$n] = "" ;
					}
				}
			}
		}
		// print_r($this->FournisseurDonnees->BaseDonnees) ;
		// $this->ElementsEnCours = $this->FournisseurDonnees->SelectElements($this->ExtraitColonnesDonnees($filtresSelection), $filtresSelection) ;
		// $this->AfficheExceptionFournisseurDonnees() ;
		// print_r($this->ElementsEnCours) ;
	}
	protected function ExtraitColonnesDonnees(& $filtres)
	{
		$cols = array() ;
		foreach($filtres as $i => & $filtre)
		{
			if($filtre->NePasLireColonne == 1)
			{
				continue ;
			}
			$cols[$i] = new \Pv\ZoneWeb\TableauDonnees\Colonne() ;
			$cols[$i]->NomDonnees = $filtre->NomColonneLiee ;
			$cols[$i]->AliasDonnees = $filtre->AliasParametreDonnees ;
		}
		// print_r($cols) ;
		return $cols ;
	}
	public function DoitInclureElement()
	{
		return $this->InclureTotalElements && $this->InclureElementEnCours ;
	}
	public function CalculeElementsRendu()
	{
		$this->ElementsEnCours = array() ;
		$this->ElementEnCours = array() ;
		$this->ElementEnCoursTrouve = 0 ;
		$this->TotalElements = 0 ;
		if($this->InclureTotalElements)
		{
			$this->CalculeTotalElements() ;
		}
		if($this->InclureElementEnCours)
		{
			$this->CalculeElementsEnCours() ;
			// echo "Err : ".$this->FournisseurDonnees->BaseDonnees->ConnectionException ;
			// print_r($this->FournisseurDonnees->BaseDonnees) ;
			// print_r($this->ElementsEnCours) ;
			if(is_array($this->ElementsEnCours) && count($this->ElementsEnCours) > 0)
			{
				$this->ElementEnCours = $this->ElementsEnCours[0] ;
				$this->AssigneValeursFiltresEdition() ;
				$this->ElementEnCoursTrouve = 1 ;
			}
		}
		else
		{
			$this->ElementEnCoursTrouve = 1 ;
		}
	}
	protected function AssigneValeursFiltresEdition()
	{
		$nomFiltres = array_keys($this->FiltresEdition) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FiltresEdition[$nomFiltre] ;
			if($filtre->NomParametreDonnees != "" && isset($this->ElementEnCours[$filtre->NomParametreDonnees]))
			{
				$filtre->DejaLie = 0 ;
				$filtre->ValeurParDefaut = $this->ElementEnCours[$filtre->NomParametreDonnees] ;
			}
		}
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeFiltresSelection() ;
		$this->ChargeFiltresEdition() ;
		$this->ChargeCommandeAnnuler() ;
		$this->ChargeCommandeExecuter() ;
		$this->ChargeConfigAuto() ;
	}
	protected function ChargeConfigAuto()
	{
	}
	public function & InsereTailleFiltreImageRef(& $filtre, $largeurMax=0, $hauteurMax=0, $operation="")
	{
		if($this->InscrireCommandeExecuter == 0 || $this->EstNul($this->CommandeExecuter))
		{
			return false ;
		}
		return $this->ActCmdTailleImage->InsereTailleFiltre($filtre->NomElementScript, $largeurMax, $hauteurMax, $operation);
	}
	public function & InsereTailleFiltreImage($nomFiltre, $largeurMax=0, $hauteurMax=0, $operation="")
	{
		if($this->InscrireCommandeExecuter == 0 || $this->EstNul($this->CommandeExecuter))
		{
			return false ;
		}
		return $this->ActCmdTailleImage->InsereTailleFiltre($nomFiltre, $largeurMax, $hauteurMax, $operation);
	}
	protected function ChargeFiltresSelection()
	{
	}
	protected function ChargeFiltresEdition()
	{
	}
	public function ParametresRendu()
	{
		$parametres = array() ;
		$nomFiltres = array_keys($this->FiltresSelection) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FiltresSelection[$nomFiltre] ;
			if($filtre->TypeLiaisonParametre == "get")
			{
				$valeur = $filtre->Lie() ;
				if($filtre->NePasInclure())
					continue ;
				$parametres[$filtre->NomParametreLie] = $valeur ;
			}
		}
		return $parametres ;
	}
	public function NomCommandeSoumise()
	{
		if($this->CacherBlocCommandes)
		{
			return '' ;
		}
		$nomParam = $this->IDInstanceCalc."_".$this->NomParamIdCommande ;
		if(isset($_POST[$nomParam]))
		{
			return $_POST[$nomParam] ;
		}
		return '' ;
	}
	public function PossedeCommandeSelectionnee()
	{
		return ($this->ValeurParamIdCommande != '') ? 1 : 0 ;
	}
	public function NomCommandeSelectionnee()
	{
		return $this->ValeurParamIdCommande ;
	}
	public function CommandeExecuterSelectionnee()
	{
		return $this->ValeurParamIdCommande == $this->NomCommandeExecuter ;
	}
	public function CommandeAnnulerSelectionnee()
	{
		return $this->ValeurParamIdCommande == $this->NomCommandeAnnuler ;
	}
	public function SuccesCommandeSelectionnee()
	{
		return $this->PossedeCommandeSelectionnee() && $this->CommandeSelectionnee->EstSucces() ;
	}
	protected function DetecteCommandeSelectionnee()
	{
		if($this->CacherBlocCommandes)
		{
			return ;
		}
		$nomParam = $this->IDInstanceCalc."_".$this->NomParamIdCommande ;
		$this->ValeurParamIdCommande = (isset($_POST[$nomParam])) ? $_POST[$nomParam] : "" ;
		if(! in_array($this->ValeurParamIdCommande, array_keys($this->Commandes)))
		{
			$this->ValeurParamIdCommande = "" ;
		}
	}
	protected function MAJConfigFiltresSelection()
	{
		if($this->FiltresSelectionObligatoires == 0)
			return ;
		$this->FixeFiltresSelectionObligatoires($this->FiltresGlobauxSelection) ;
		$this->FixeFiltresSelectionObligatoires($this->FiltresLigneSelection) ;
	}
	public function AppliqueCommandeSelectionnee()
	{
		$this->MAJConfigFiltresSelection() ;
		$this->ExecuteCommandeSelectionnee() ;
	}
	protected function FixeFiltresSelectionObligatoires(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtres[$nomFiltre]->Obligatoire = 1 ;
		}
	}
	protected function ExecuteCommandeSelectionnee()
	{
		$this->DetecteCommandeSelectionnee() ;
		$this->CommandeSelectionnee = null ;
		$this->CommandeSelectionneeExec = 0 ;
		if($this->ValeurParamIdCommande != "" && isset($this->Commandes[$this->ValeurParamIdCommande]))
		{
			$this->CommandeSelectionnee = & $this->Commandes[$this->ValeurParamIdCommande] ;
		}
		if(! $this->EstNul($this->CommandeSelectionnee))
		{
			$this->AnnulerCommandeSelectionnee = 0 ;
			$this->ValideCommandeSelectionnee() ;
			if($this->AnnulerCommandeSelectionnee == 0)
			{
				$this->CommandeSelectionnee->Execute() ;
				$this->CommandeSelectionneeExec = 1 ;
				if($this->CacherFormulaireFiltresApresCmd == 1)
				{
					$this->CacherFormulaireFiltres = 1 ;
				}
			}
		}
	}
	protected function ValideCommandeSelectionnee()
	{
	}
	protected function RenduDispositifBrut()
	{
		if(! $this->EstBienRefere())
		{
			return $this->RenduMalRefere() ;
		}
		$this->MAJConfigFiltresSelection() ;
		if($this->ZoneParent->PreparerComposants == 0)
		{
			$this->ExecuteCommandeSelectionnee() ;
		}
		$this->PrepareRendu() ;
		$ctn = '<div id="'.$this->IDInstanceCalc.'"'.(($this->ClasseCSSDispositif != '') ? ' class="'.$this->ClasseCSSDispositif.'"' : '').'>'.PHP_EOL ;
		if($this->MessageException == null)
		{
			$ctn .= $this->ContenuAvantRendu ;
			$ctn .= $this->RenduComposants().PHP_EOL ;
			$ctn .= $this->ContenuApresRendu ;
		}
		else
		{
			$ctn .= $this->RenduMessageException() ;
		}
		$ctn .= '</div>' ;
		return $ctn ;
	}
	public function ObtientFiltresSelection()
	{
		$filtres = $this->FiltresGlobauxSelection ;
		if(count($this->FiltresLigneSelection) > 0)
		{
			array_splice($filtres, count($filtres), 0, $this->FiltresLigneSelection) ;
		}
		return $filtres ;
	}
	public function LieFiltres(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtres[$nomFiltre]->Lie() ;
		}
	}
	public function LieFiltresEdition()
	{
		$this->LieFiltres($this->FiltresEdition) ;
	}
	public function LieFiltresSelection()
	{
		$this->LieFiltres($this->FiltresGlobauxSelection) ;
		$this->LieFiltres($this->FiltresLigneSelection) ;
	}
	public function LieTousLesFiltres()
	{
		$this->LieFiltresSelection() ;
		$this->LieFiltresEdition() ;
	}
	public function NeLiePasParamFiltresEdition()
	{
		$nomFiltres = array_keys($this->FiltresEdition) ;
		foreach($nomFiltres as $i => $nom)
		{
			$this->FiltresEdition[$nom]->NePasLierParametre = 1 ;
		}
	}
	public function NeLiePasParamFltsEdit()
	{
		$this->NeLiePasParamFiltresEdition() ;
	}
	public function FigeFltsEdit()
	{
		$this->FigeFiltresEdition() ;
	}
	public function FigeFiltresEdition()
	{
		$nomFiltres = array_keys($this->FiltresEdition) ;
		foreach($nomFiltres as $i => $nom)
		{
			$this->FiltresEdition[$nom]->LectureSeule = 1 ;
		}
	}
	public function CacheFltsEdit()
	{
		$this->CacheFiltresEdition() ;
	}
	public function CacheFiltresEdition()
	{
		$nomFiltres = array_keys($this->FiltresEdition) ;
		foreach($nomFiltres as $i => $nom)
		{
			$this->FiltresEdition[$nom]->Visible = 1 ;
		}
	}
	protected function RenduComposants()
	{
		$ctn = '' ;
		if(count($this->DispositionComposants))
		{
			$ctn .= '<form class="FormulaireDonnees'.(($this->NomClasseCSS != '') ? ' '.$this->NomClasseCSS : '').'" method="post" enctype="multipart/form-data" onsubmit="return SoumetFormulaire'.$this->IDInstanceCalc.'(this)" accept-charset="'.$this->ZoneParent->EncodageDocument.'">'.PHP_EOL ;
			foreach($this->DispositionComposants as $i => $id)
			{
				if($i > 0)
				{
					$ctn .= PHP_EOL ;
				}
				switch($id)
				{
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::BlocEntete :
					{
						$ctn .= $this->RenduBlocEntete() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::FormulaireFiltresEdition :
					{
						$ctn .= $this->RenduFormulaireFiltres() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::ResultatCommandeExecutee :
					{
						$ctn .= $this->RenduResultatCommandeExecutee() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::BlocCommandes :
					{
						$ctn .= $this->RenduBlocCommandes() ;
					}
					break ;
					default :
					{
						$ctn .= $this->RenduAutreComposantSupport($id) ;
					}
					break ;
				}
			}
			$ctn .= '</form>' ;
		}
		return $ctn ;
	}
	protected function RenduMessageException()
	{
		return '<div class="'.$this->ClasseCSSErreur.'">'.$this->MessageException.'</div>' ;
	}
	protected function RenduAutreComposantSupport($id)
	{
	}
	protected function RenduBlocEntete()
	{
		$ctn = '' ;
		if($this->Titre != '')
		{
			$titre = \Pv\Misc::_parse_pattern($this->Titre, array_map('htmlentities', $this->ElementEnCours)) ;
			$ctn .= '<div align="'.$this->AlignTitre.'" class="'.$this->ClasseCSSTitre.'">'.$titre.'</div>'.PHP_EOL ;
		}
		if($this->Description != '')
		{
			$desc = \Pv\Misc::_parse_pattern($this->Description, array_map('htmlentities', $this->ElementEnCours)) ;
			$ctn .= '<div class="'.$this->ClasseCSSDescription.'">'.$desc.'</div>' ;
		}
		return $ctn ;
	}
	protected function RenduFormulaireFiltres()
	{
		$ctn = "" ;
		// echo "Cacher form : ".$this->CacherFormulaireFiltres."<br/>" ;
		if(! $this->CacherFormulaireFiltres && ! $this->AnnulerRenduFiltres)
		{
			if($this->ElementEnCoursTrouve)
			{
				if($this->EstNul($this->DessinateurFiltresEdition))
				{
					$this->InitDessinateurFiltresEdition() ;
				}
				if($this->EstNul($this->DessinateurFiltresEdition))
				{
					return "<p>Le dessinateur de filtres n'est pas dÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©fini</p>" ;
				}
				if($this->MaxFiltresEditionParLigne > 0)
				{
					$this->DessinateurFiltresEdition->MaxFiltresParLigne = $this->MaxFiltresEditionParLigne ;
				}
				$this->DessinateurFiltresEdition->InclureRenduLibelle = $this->InclureRenduLibelleFiltresEdition ;
				$ctn .= '<div class="FormulaireFiltres">'.PHP_EOL ;
				if($this->UtiliserLargeur == 1)
				{
					$ctn .= '<table' ;
					$ctn .= ' cellpadding="2"' ;
					if($this->Largeur != "")
					{
						$ctn .= ' width="'.$this->Largeur.'"' ;
					}
					$ctn .= ' cellspacing="0"' ;
					$ctn .= '>'.PHP_EOL ;
					$ctn .= '<tr>'.PHP_EOL ;
					$ctn .= '<td>'.PHP_EOL ;
				}
				$ctn .= $this->RenduFormulaireFiltreElemEnCours() ;
				if($this->UtiliserLargeur == 1)
				{
					$ctn .= '</td>'.PHP_EOL ;
					$ctn .= '</tr>'.PHP_EOL ;
					$ctn .= '</table>'.PHP_EOL ;
				}
				$ctn .= '</div>' ;
				$ctn .= $this->DeclarationSoumetFormulaireFiltres($this->FiltresEdition).PHP_EOL ;
			}
			else
			{
				if(! $this->EstNul($this->FournisseurDonnees))
				{
					// echo 'Err Sql : '.$this->FournisseurDonnees->BaseDonnees->LastSqlText ;
				}
				// print_r($this->FournisseurDonnees->BaseDonnees->LastSqlParams) ;
				$ctn .= $this->MessageAucunElement ;
			}
		}
		return $ctn ;
	}
	public function ObtientUrlInitiale()
	{
		$filtresSelect = $this->FiltresGlobauxSelection ;
		if(count($this->FiltresLigneSelection) > 0)
		{
			array_splice($filtresSelect, count($filtresSelect), 0, $this->FiltresLigneSelection) ;
		}
		$nomFiltres = array_keys($filtresSelect) ;
		$filtresGets = array() ;
		if($this->ZoneParent->ActiverRoutes == 0)
		{
			$filtresGets[] = $this->ZoneParent->NomParamScriptAppele ;
		}
		$nomFiltresGets = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			if($filtres[$nomFiltre]->TypeLiaisonParametre == "get")
			{
				$filtresGets[] = $filtres[$nomFiltre]->ObtientIDElementHtmlComposant() ;
				$nomFiltresGets[] = $filtres[$nomFiltre]->NomParametreLie ;
			}
		}
		foreach($this->ChampsGetSoumetFormulaire as $n => $v)
		{
			if(! in_array($v, $filtresGet))
			{
				$filtresGets[] = $v ;
			}
		}
		foreach($this->ParamsGetSoumetFormulaire as $n => $v)
		{
			if(! in_array($v, $filtresGet))
			{
				$filtresGets[] = $v ;
			}
		}
		$params = \Pv\Misc::extract_array_without_keys($_GET, $nomFiltresGets) ;
		$indexMinUrl = (count($params) > 0) ? 0 : 1 ;
		$urlFormulaire = \Pv\Misc::remove_url_params(\Pv\Misc::get_current_url()) ;
		$urlFormulaire .= '?'.\Pv\Misc::http_build_query_string($params) ;
		if($this->ForcerDesactCache)
		{
			$urlFormulaire .= '&'.urlencode($this->NomParamIdAleat()).'='.htmlspecialchars(rand(0, 999999)) ;
		}
		return $urlFormulaire ;
	}
	protected function RenduFormulaireFiltreElemEnCours()
	{
		$ctn = '' ;
		$ctn .= $this->DessinateurFiltresEdition->Execute($this->ScriptParent, $this, $this->FiltresEdition) ;
		return $ctn ;
	}
	protected function RenduBlocCommandes()
	{
		$ctn = '' ;
		if(! $this->CacherBlocCommandes && ! $this->ImpressionEnCours())
		{
			if($this->ElementEnCoursTrouve || $this->AfficherCommandesAucunElement)
			{
				if($this->EstNul($this->DessinateurBlocCommandes))
				{
					$this->InitDessinateurBlocCommandes() ;
				}
				if($this->EstNul($this->DessinateurBlocCommandes))
				{
					return "<p>Le dessinateur de filtres n'est pas défini</p>" ;
				}
				$ctn .= '<div class="BlocCommandes'.(($this->ClasseCSSBlocCommandes)).'"'.(($this->AlignBlocCommandes != '') ? ' align="'.$this->AlignBlocCommandes.'"' : '').'>'.PHP_EOL ;
				$ctn .= $this->DessinateurBlocCommandes->Execute($this->ScriptParent, $this, $this->Commandes) ;
				$ctn .= $this->DeclarationJsActiveCommande().PHP_EOL ;
				$ctn .= '</div>' ;
			}
		}
		return $ctn ;
	}
	protected function RenduResultatCommandeExecutee()
	{
		$ctn = '' ;
		if($this->EstNul($this->CommandeSelectionnee) || $this->CacherMessageExecution == 1)
		{
			return $ctn ;
		}
		$msgExecution = html_entity_decode($this->CommandeSelectionnee->MessageExecution) ;
		if($this->PopupMessageExecution)
		{
			if(! $this->ZoneParent->InclureJQueryUi)
			{
				$ctn .= '<script language="javascript">'.PHP_EOL ;
				$ctn .= 'alert('.@svc_json_encode($msgExecution).') ;' ;
				$ctn .= '</script>'.PHP_EOL ;
			}
			else
			{
				$ctn .= '<div id="DialogMsg'.$this->IDInstanceCalc.'" class="ui-dialog" align="center">'.htmlentities($msgExecution).''.$this->RenduLiensCommandeExecutee().'</div>' ;
				$ctn .= '<script language="javascript">'.PHP_EOL ;
				$ctn .= 'jQuery(function() {
jQuery("#DialogMsg'.$this->IDInstanceCalc.'").dialog({
autoOpen : true,
resizable : false,
modal : true
}) ;
})'.PHP_EOL ;
				$ctn .= '</script>'.PHP_EOL ;
			}
		}
		else
		{
			$ctn .= '<div' ;
			$classeCSS = ($this->CommandeSelectionnee->StatutExecution == 1) ? $this->ClasseCSSSucces : $this->ClasseCSSErreur ;
			$ctn .= ' class="'.$classeCSS.'"' ;
			$ctn .= '>' ;
			$ctn .= htmlentities($msgExecution) ;
			$ctn .= $this->RenduLiensCommandeExecutee() ;
			$ctn .= '</div>' ;
		}
		return $ctn ;
	}
	protected function RenduLiensCommandeExecutee()
	{
		$msgExecution = '' ;
		$liensCmd = $this->CommandeSelectionnee->ObtientLiens() ;
		if(count($liensCmd) > 0)
		{
			foreach($liensCmd as $i => $lienCmd)
			{
				$msgExecution .= ' ' ;
				$msgExecution .= $lienCmd->RenduDispositif($this, $i) ;
			}
		}
		return $msgExecution ;
	}
	protected function CtnJsActualiseFormulaireFiltres()
	{
		$ctn = '' ;
		$ctn .= 'var elem = document.getElementById("'.$this->IDInstanceCalc.'") ;
if(elem !== null) {
var form = elem.getElementsByTagName("form")[0] ;
SoumetFormulaire'.$this->IDInstanceCalc.'(form) ;
form.submit() ;
}' ;
		return $ctn ;
	}
	protected function CtnJsSoumetSurEntree()
	{
		$ctn = '' ;
		if($this->NomCommandeEntree == "" || ! isset($this->Commandes[$this->NomCommandeEntree]))
		{
			return '' ;
		}
		$cmd = & $this->Commandes[$this->NomCommandeEntree] ;
		$contenuJsSurClick = ($cmd->ContenuJsSurClick == '') ? $this->IDInstanceCalc.'_ActiveCommande(document.getElementById('.json_encode($cmd->IDInstanceCalc).')) ; formTemp.submit() ;' : $cmd->ContenuJsSurClick.' ;' ;
		$ctn .= 'var comp = document.getElementById("'.$this->IDInstanceCalc.'") ;
if(comp !== null)
{
var formTemp = comp.getElementsByTagName("form")[0] ;
for(var i=0; i<formTemp.elements.length; i++)
{
	var elem = formTemp.elements[i] ;
	elem.addEventListener(\'keypress\', function(event) {
	if (event.keyCode == 13) {
		'.$contenuJsSurClick.'
		event.preventDefault() ;
	}
});
}
}' ;
		return $ctn ;
	}
	public function RemplaceCommandeAnnuler($nomClasse)
	{
		$this->NomClasseCommandeAnnuler = $nomClasse ;
		if($this->EstNul($this->CommandeAnnuler))
		{
			return ;
		}
		$this->ChargeCommandeAnnuler() ;
	}
	public function RemplaceCommandeExecuter($nomClasse)
	{
		$this->NomClasseCommandeExecuter = $nomClasse ;
		if($this->EstNul($this->CommandeExecuter))
		{
			return ;
		}
		$this->ChargeCommandeExecuter() ;
	}
	public function & InsereCommande($nomCommande, $commande)
	{
		$this->InscritCommande($nomCommande, $commande) ;
		return $commande ;
	}
	public function InscritCommande($nomCommande, & $commande)
	{
		$this->Commandes[$nomCommande] = & $commande ;
		$commande->AdopteFormulaireDonnees($nomCommande, $this) ;
	}
	public function & DeclareCommande($nomCommande, $nomClasseCommande, $libelleCommande="")
	{
		if(! class_exists($nomClasseCommande))
		{
			die("Impossible de creer une commande a partir de la classe ".$nomClasseCommande." inexistante") ;
		}
		$commande = new $nomClasseCommande() ;
		$commande->Libelle = $libelleCommande ;
		$commande->ChargeConfig() ;
		$commande->AdopteFormulaireDonnees($nomCommande, $this) ;
		$this->Commandes[$nomCommande] = & $commande ;
		return $commande ;
	}
	protected function ChargeCommandeExecuter()
	{
		if(! $this->InscrireCommandeExecuter)
		{
			return 0 ;
		}
		$this->CommandeExecuter = $this->DeclareCommande($this->NomCommandeExecuter, $this->NomClasseCommandeExecuter, $this->LibelleCommandeExecuter) ;
		if($this->EstNul($this->CommandeExecuter))
		{
			return 0 ;
		}
		if($this->MsgExecEchecCommandeExecuter != '')
		{
			$this->CommandeExecuter->MessageEchecExecution = $this->MsgExecEchecCommandeExecuter ;
		}
		if($this->MsgExecSuccesCommandeExecuter != '')
		{
			$this->CommandeExecuter->MessageSuccesExecution = $this->MsgExecSuccesCommandeExecuter ;
		}
		if($this->NomScriptExecSuccesCommandeExecuter != '')
		{
			$this->CommandeExecuter->NomScriptExecutionSucces = $this->NomScriptExecSuccesCommandeExecuter ;
			$this->CommandeExecuter->ParamsScriptExecutionSucces = $this->ParamsScriptExecutionSucces ;
		}
		if(count($this->ActCmdsCommandeExecuter) > 0)
		{
			foreach($this->ActCmdsCommandeExecuter as $i => $actCmd)
			{
				$this->CommandeExecuter->InscritNouvActCmd($this->ActCmdsCommandeExecuter[$i]) ;
			}
		}
		$this->ActCmdTailleImage = $this->CommandeExecuter->InsereNouvActCmd(new \Pv\ZoneWeb\ActionCommande\TailleImageGd()) ;
		if(count($this->CriteresCommandeExecuter) > 0)
		{
			foreach($this->CriteresCommandeExecuter as $i => $actCmd)
			{
				$this->CommandeExecuter->InscritNouvCritere($this->CriteresCommandeExecuter[$i]) ;
			}
		}
		if($this->ClasseBoutonCommandeExecuter != '')
		{
			$this->CommandeExecuter->AffecteAttrSuppl('classe-btn', $this->ClasseBoutonCommandeExecuter) ; 
		}
		if($this->ClasseCSSCommandeExecuter != '')
		{
			$this->CommandeExecuter->NomClsCSS = $this->ClasseCSSCommandeExecuter ; 
		}
		return 1 ;
	}
	protected function ChargeCommandeAnnuler()
	{
		if(! $this->InscrireCommandeAnnuler)
		{
			return 0 ;
		}
		$this->CommandeAnnuler = $this->DeclareCommande($this->NomCommandeAnnuler, $this->NomClasseCommandeAnnuler, $this->LibelleCommandeAnnuler) ;
		if($this->MsgExecEchecCommandeAnnuler != '')
		{
			$this->CommandeAnnuler->MessageEchecExecution = $this->MsgExecEchecCommandeAnnuler ;
		}
		if($this->MsgExecSuccesCommandeAnnuler != '')
		{
			$this->CommandeAnnuler->MessageSuccesExecution = $this->MsgExecSuccesCommandeAnnuler ;
		}
		if(count($this->ActCmdsCommandeAnnuler) > 0)
		{
			foreach($this->ActCmdsCommandeAnnuler as $i => $actCmd)
			{
				$this->CommandeAnnuler->InscritNouvActCmd($this->ActCmdsCommandeAnnuler[$i]) ;
			}
		}
		if(count($this->CriteresCommandeAnnuler) > 0)
		{
			foreach($this->CriteresCommandeAnnuler as $i => $actCmd)
			{
				$this->CommandeAnnuler->InscritNouvCritere($this->CriteresCommandeAnnuler[$i]) ;
			}
		}
		if($this->ClasseBoutonCommandeAnnuler != '')
		{
			$this->CommandeAnnuler->AffecteAttrSuppl('classe-btn', $this->ClasseBoutonCommandeAnnuler) ; 
		}
		if($this->ClasseCSSCommandeAnnuler != '')
		{
			$this->CommandeAnnuler->NomClsCSS = $this->ClasseCSSCommandeAnnuler ; 
		}
		return 1 ;
	}
	protected function InitDessinateurBlocCommandes()
	{
		$this->DessinateurBlocCommandes = new \Pv\ZoneWeb\DessinCommandes\DessinCommandes() ;
	}
	protected function InitDessinateurFiltresEdition()
	{
		$this->DessinateurFiltresEdition = new \Pv\ZoneWeb\DessinFiltres\Html() ;
	}
	public function DessineFiltresScriptParent()
	{
		$this->DessinateurFiltresEdition = new \Pv\ZoneWeb\DessinFiltres\AppliqueScriptParent() ;
	}
	public function DessineCommandesScriptParent()
	{
		$this->DessinateurBlocCommandes = new \Pv\ZoneWeb\DessinCommandes\AppliqueScriptParent() ;
	}
	public function NotifieParMail($de, $a, $cc='', $cci='')
	{
		if($this->EstNul($this->CommandeExecuter))
		{
			throw new Exception("La commande 'Executer' n'a pas ete initialisee pour les envois de mail") ;
			return ;
		}
		$actCmd = $this->CommandeExecuter->InsereActCmd("\Pv\ZoneWeb\ActionCommande\FormMail", array()) ;
		$actCmd->A = $a ;
		$actCmd->De = $de ;
		$actCmd->Cc = $cc ;
		$actCmd->Cci = $cci ;
	}
	public function RedirigeCmdAnnulerVersUrl($url)
	{
		return $this->RedirigeAnnulerVersUrl($url) ;
	}
	public function RedirigeCmdAnnulerVersScript($nomScript, $parametres=array())
	{
		return $this->RedirigeAnnulerVersScript($nomScript, $parametres) ;
	}
	public function RedirigeAnnulerVersUrl($url)
	{
		if($this->EstNul($this->CommandeAnnuler))
		{
			throw new Exception("La commande 'Annuler' n'a pas ete initialisee avant d'assigner une redirection") ;
			return ;
		}
		$actCmd = $this->CommandeAnnuler->InsereClasseActCmd("\Pv\ZoneWeb\ActionCommande\RedirectionHttp", array()) ;
		$actCmd->Url = $url ;
		return $actCmd ;
	}
	public function RedirigeAnnulerVersScript($nomScript, $parametres=array())
	{
		if(! $this->InscrireCommandeAnnuler)
		{
			return ;
		}
		if($this->EstNul($this->CommandeAnnuler))
		{
			throw new Exception("La commande 'Annuler' n'a pas ete initialisee avant d'assigner une redirection") ;
			return ;
		}
		$actCmd = $this->CommandeAnnuler->InsereClasseActCmd("\Pv\ZoneWeb\ActionCommande\RedirectionHttp", array()) ;
		$actCmd->NomScript = $nomScript ;
		$actCmd->Parametres = $parametres ;
		return $actCmd ;
	}
	public function RedirigeCmdExecuterVersUrl($url)
	{
		return $this->RedirigeExecuterVersUrl($url) ;
	}
	public function RedirigeCmdExecuterVersScript($nomScript, $parametres=array())
	{
		return $this->RedirigeExecuterVersScript($nomScript, $parametres) ;
	}
	public function RedirigeExecuterVersUrl($url)
	{
		if($this->EstNul($this->CommandeExecuter))
		{
			throw new Exception("La commande 'Executer' n'a pas ete initialisee avant d'assigner une redirection") ;
			return ;
		}
		$actCmd = $this->CommandeExecuter->InsereClasseActCmd("\Pv\ZoneWeb\ActionCommande\RedirectionHttp", array()) ;
		$actCmd->Url = $url ;
		return $actCmd ;
	}
	public function RedirigeExecuterVersScript($nomScript, $parametres=array())
	{
		if(! $this->InscrireCommandeExecuter)
		{
			return ;
		}
		if($this->EstNul($this->CommandeExecuter))
		{
			throw new Exception("La commande 'Executer' n'a pas ete initialisee avant d'assigner une redirection") ;
			return ;
		}
		$actCmd = $this->CommandeExecuter->InsereClasseActCmd("\Pv\ZoneWeb\ActionCommande\RedirectionHttp", array()) ;
		$actCmd->NomScript = $nomScript ;
		$actCmd->Parametres = $parametres ;
		return $actCmd ;
	}
	public function AppliqueScriptParentCmdExec()
	{
		if(! $this->InscrireCommandeExecuter)
		{
			return ;
		}
		$this->RemplaceCommandeExecuter('\Pv\ZoneWeb\Commande\AppliqueScriptParent') ;
	}
	public function AppliqueScriptParentCmdAnnul()
	{
		if(! $this->InscrireCommandeAnnuler)
		{
			return ;
		}
		$this->RemplaceCommandeAnnuler('\Pv\ZoneWeb\Commande\AppliqueScriptParent') ;
	}
	public function AppliqueZoneParentCmdExec()
	{
		if(! $this->InscrireCommandeExecuter)
		{
			return ;
		}
		$this->RemplaceCommandeExecuter('\Pv\ZoneWeb\Commande\AppliqueZoneParent') ;
	}
	public function AppliqueZoneParentCmdAnnul()
	{
		if(! $this->InscrireCommandeAnnuler)
		{
			return ;
		}
		$this->RemplaceCommandeAnnuler('\Pv\ZoneWeb\Commande\AppliqueZoneParent') ;
	}
	public function NotifieExecuterVersScript($nomScript, $parametres=array())
	{
		if(! $this->InscrireCommandeExecuter)
		{
			return ;
		}
		if($this->EstNul($this->CommandeExecuter))
		{
			throw new Exception("La commande 'Executer' n'a pas ete initialisee avant d'assigner une redirection") ;
			return ;
		}
		$actCmd = $this->CommandeExecuter->InsereClasseActCmd('\Pv\ZoneWeb\ActionCommande\NotifieScript', array()) ;
		$actCmd->NomScript = $nomScript ;
		$actCmd->Parametres = $parametres ;
		return $actCmd ;
	}
}
