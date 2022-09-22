<?php

namespace Pv\ZoneWeb\TableauDonnees ;

class TableauDonnees extends \Pv\ZoneWeb\ComposantRendu\Parametrable
{
	public $Titre = "" ;
	public $TypeComposant = "TableauDonneesHTML" ;
	public $Largeur = "100%" ;
	public $LargeurFormulaireFiltres = "" ;
	public $AlignFormulaireFiltres = "" ;
	public $Hauteur = "" ;
	public $EspacementCell = "4" ;
	public $MargesCell = "0" ;
	public $LargeurBordure = "1" ;
	public $CouleurBordure = "black" ;
	public $DefinitionsColonnes = array() ;
	public $Lignes = array() ;
	public $FiltresSelection = array() ;
	public $MaxElementsPossibles = array(20) ;
	public $ToujoursAfficher = false ;
	public $CacherFormulaireFiltres = false ;
	public $CacherBlocCommandes = false ;
	public $SuffixeParamFiltresSoumis = "filtre" ;
	public $SuffixeParamMaxElements = "max" ;
	public $SuffixeParamIndiceDebut = "debut" ;
	public $SuffixeParamIndiceColonneTri = "indice_tri" ;
	public $SuffixeParamSensColonneTri = "sens_tri" ;
	public $ForcerDesactCache = false ;
	public $CacherNavigateurRangees = false ;
	public $CacherNavigateurRangeesAuto = false ;
	public $IndiceDebut = 0 ;
	public $IndiceFin = 0 ;
	public $MaxElements = 0 ;
	public $TotalElements = 0 ;
	public $TotalRangees = 0 ;
	public $IndiceColonneTriSelect = -1 ;
	public $MaxFiltresSelectionParLigne = 2 ;
	public $IndiceColonneTri = 0 ;
	public $NePasTrier = false ;
	public $SensColonneTri = "" ;
	public $TitreFormulaireFiltres = "Rechercher" ;
	public $AlignTitreFormulaireFiltres = "left" ;
	public $TitreBoutonSoumettreFormulaireFiltres = "GO" ;
	public $AlignBoutonSoumettreFormulaireFiltres = "left" ;
	public $TitreBoutonRAZFormulaireFiltres = "Effacer" ;
	public $LibelleTriAsc = "asc" ;
	public $LibelleTriDesc = "desc" ;
	public $LibelleTriAscSelectionne = "asc" ;
	public $LibelleTriDescSelectionne = "desc" ;
	public $ElementsEnCours = array() ;
	public $ElementsEnCoursBruts = array() ;
	public $DispositionComposants = array(1, 2, 3, 4) ;
	public $TriPossible = true ;
	public $RangeeEnCours = -1 ;
	public $LibellePremiereRangee = "|&lt;" ;
	public $LibelleRangeePrecedente = "&lt;&lt;" ;
	public $LibelleRangeeSuivante = "&gt;&gt;" ;
	public $LibelleDerniereRangee = "&gt;|" ;
	public $TitrePremiereRangee = "Premi&egrave;re rang&eacute;e" ;
	public $TitreRangeePrecedente = "Rang&eacute;e pr&eacute;c&eacute;dente" ;
	public $TitreRangeeSuivante = "Rang&eacute;e suivante" ;
	public $TitreDerniereRangee = "Derni&egrave;re rang&eacute;e" ;
	public $SeparateurLiensRangee = "&nbsp;&nbsp;&nbsp;&nbsp;" ;
	public $FormatInfosRangee = 'Affichage de ${NoDebut} - ${NoFin} sur ${TotalElements}' ;
	public $MessageAucunElement = "Aucun element n'a &eacute;t&eacute; trouv&eacute;" ;
	public $AlerterAucunElement = 1 ;
	public $UtiliserIconesTri = 1 ;
	public $AccepterTriColonneInvisible = false ;
	public $CheminRelativeIconesTri = "images" ;
	public $NomIconeTriAsc = "IconAsc.png" ;
	public $NomIconeTriDesc = "IconDesc.png" ;
	public $NomIconeTriAscSelectionne = "IconAscSelect.png" ;
	public $NomIconeTriDescSelectionne = "IconDescSelect.png" ;
	public $DessinateurFiltresSelection ;
	public $MessageFiltresNonRenseignes  = "Veuillez renseigner tous les param&egrave;tres." ;
	public $Commandes = array() ;
	public $CommandeSelectionnee ;
	public $SuffixeParamCommandeSelectionnee = "Commande" ;
	public $ValeurParamCommandeSelectionnee = "" ;
	public $DessinateurBlocCommandes ;
	public $SurvolerLigneFocus = true ;
	public $ExtraireValeursElements = true ;
	public $SautLigneSansCommande = true ;
	public $NavigateurRangees = null ;
	public $NomCommandeEntree = "" ;
	public $RangeeDonneesEditable = true ;
	public $SourceValeursSuppl ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->SourceValeursSuppl = new \Pv\ZoneWeb\Donnees\SrcValsSuppl\SrcValsSuppl() ;
	}
	public function InscritExtractValsIndex(& $extractVals, $indexCol)
	{
		if(! isset($this->DefinitionsColonnes[$indexCol]))
			return ;
		$this->DefinitionsColonnes[$indexCol]->ExtracteurValeur = & $extractVals ;
	}
	public function InscritExtractVals(& $extractVals, & $col)
	{
		$col->ExtracteurValeur = & $extractVals ;
	}
	protected function DeclarationJsActiveCommande()
	{
		$ctn = '' ;
		$ctn .= '<script type="text/javascript">
if(typeof '.$this->IDInstanceCalc.'_ActiveCommande != "function")
{
function '.$this->IDInstanceCalc.'_ActiveCommande(btn)
{
	var nomCommande = (btn.rel == undefined) ? btn.getAttribute("rel") : btn.rel ;
	SoumetEnvoiFiltres'.$this->IDInstanceCalc.'({'.svc_json_encode($this->NomParamCommandeSelectionnee()).': nomCommande}) ;
}
}
</script>' ;
		return $ctn ;
	}
	public function InscritCommande($nom, & $commande)
	{
		$this->Commandes[$nom] = & $commande ;
		$commande->AdopteTableauDonnees($nom, $this) ;
	}
	public function InscritNouvCommande($nom, $commande)
	{
		$this->InscritCommande($nom, $commande) ;
	}
	public function InscritCmd($nom, & $commande)
	{
		$this->InscritCommande($nom, $commande) ;
	}
	public function InscritNouvCmd($nom, $commande)
	{
		$this->InscritCommande($nom, $commande) ;
	}
	public function & InsereCommande($nom, $commande)
	{
		$this->InscritCommande($nom, $commande) ;
		return $commande ;
	}
	public function & InsereCmd($nom, $commande)
	{
		$this->InscritCommande($nom, $commande) ;
		return $commande ;
	}
	protected function CreeCmdRafraich()
	{
		return new \Pv\ZoneWeb\TableauDonnees\Commande\SoumetFiltres() ;
	}
	public function InscritCmdRafraich($libelle='Actualiser', $cheminIcone='')
	{
		$cmd = $this->CreeCmdRafraich() ;
		$cmd->Libelle = $libelle ;
		$cmd->CheminIcone = $cheminIcone ;
		$this->InscritCmd('rafraich', $cmd) ;
		return $cmd ;
	}
	public function DetecteCommandeSelectionnee()
	{
		$this->ValeurParamCommandeSelectionnee = (isset($_GET[$this->NomParamCommandeSelectionnee()])) ? $_GET[$this->NomParamCommandeSelectionnee()] : "" ;
		$valeurNulle = null ;
		$this->CommandeSelectionnee = & $valeurNulle ;
		if($this->ValeurParamCommandeSelectionnee != "")
		{
			foreach($this->Commandes as $i => $commande)
			{
				if($commande->NomElementTableauDonnees == $this->ValeurParamCommandeSelectionnee)
				{
					$this->CommandeSelectionnee = & $this->Commandes[$i] ;
					break ;
				}
			}
			if($this->CommandeSelectionnee == null)
			{
				$this->ValeurParamCommandeSelectionnee = "" ;
			}
		}
	}
	public function ExecuteCommandeSelectionnee()
	{
		$this->DetecteCommandeSelectionnee() ;
		if($this->CommandeSelectionnee != null)
		{
			$this->CommandeSelectionnee->Execute() ;
		}
	}
	protected function DetecteParametresLocalisation()
	{
		$nomParamMaxElements = $this->NomParamMaxElements() ;
		$nomParamIndiceDebut = $this->NomParamIndiceDebut() ;
		$nomParamIndiceColonneTri = $this->NomParamIndiceColonneTri() ;
		$nomParamSensColonneTri = $this->NomParamSensColonneTri() ;
		$this->MaxElements = (isset($_GET[$nomParamMaxElements])) ? $nomParamMaxElements : 0 ;
		if(! in_array($this->MaxElements, $this->MaxElementsPossibles))
			$this->MaxElements = $this->MaxElementsPossibles[0] ;
		$this->IndiceDebut = (isset($_GET[$nomParamIndiceDebut])) ? intval($_GET[$nomParamIndiceDebut]) : 0 ;
		if($this->NePasTrier == 0)
		{
			$this->IndiceColonneTri = (isset($_GET[$nomParamIndiceColonneTri])) ? intval($_GET[$nomParamIndiceColonneTri]) : $this->IndiceColonneTri ;
			if($this->IndiceColonneTri >= count($this->DefinitionsColonnes) || $this->IndiceColonneTri < 0)
				$this->IndiceColonneTri = 0 ;
			// Gerer les tri sur des colonnes invisibles...
			if(count($this->DefinitionsColonnes) > 0)
			{
				if(! $this->AccepterTriColonneInvisible && $this->DefinitionsColonnes[$this->IndiceColonneTri]->Visible == 0)
				{
					for($i=$this->IndiceColonneTri+1; $i<count($this->DefinitionsColonnes); $i++)
					{
						if($this->DefinitionsColonnes[$i]->Visible == 1 && $this->DefinitionsColonnes[$i]->NomDonnees != '')
						{
							$this->IndiceColonneTri = $i ;
							break ;
						}
					}
				}
			}
			$this->SensColonneTri = strtolower((isset($_GET[$nomParamSensColonneTri])) ? $_GET[$nomParamSensColonneTri] : $this->SensColonneTri) ;
			// echo $this->SensColonneTri.' jjj' ;
			if($this->SensColonneTri != "desc")
				$this->SensColonneTri = "asc" ;
		}
	}
	public function NomParamFiltresSoumis()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamFiltresSoumis ;
	}
	public function NomParamMaxElements()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamMaxElements ;
	}
	public function NomParamIndiceDebut()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamIndiceDebut ;
	}
	public function NomParamIndiceColonneTri()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamIndiceColonneTri ;
	}
	public function NomParamSensColonneTri()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamSensColonneTri ;
	}
	public function NomParamCommandeSelectionnee()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamCommandeSelectionnee ;
	}
	public function & InsereFltSelectRef($nom, & $filtreRef, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectFixe($nom, $valeur, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->NePasInclureSiVide = true ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectCookie($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectSession($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectMembreConnecte($nom, $nomParamLie='', $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpUpload($nom, $cheminDossierDest="", $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpGet($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpPost($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpRequest($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$flt->NePasInclureSiVide = true ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function InsereTablDefsCol($cols=array())
	{
		foreach($cols as $i => $nom)
		{
			$this->InsereDefCol($nom) ;
		}
	}
	public function InsereDefsColCachee()
	{
		$noms = func_get_args() ;
		foreach($noms as $i => $nom)
		{
			$this->InsereDefColCachee($nom) ;
		}
	}
	public function & InsereDefColCachee($nomDonnees, $aliasDonnees="")
	{
		$defCol = $this->InsereDefColInvisible($nomDonnees, $aliasDonnees) ;
		return $defCol ;
	}
	public function & InsereDefColInvisible($nomDonnees, $aliasDonnees="")
	{
		$defCol = new \Pv\ZoneWeb\TableauDonnees\Colonne() ;
		$defCol->NomDonnees = $nomDonnees ;
		$defCol->AliasDonnees = $aliasDonnees ;
		$defCol->Visible = 0 ;
		$this->DefinitionsColonnes[] = & $defCol ;
		return $defCol ;
	}
	public function & InsereDefCol($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = new \Pv\ZoneWeb\TableauDonnees\Colonne() ;
		if(is_array($nomDonnees))
		{
			$aliasDonnees = (isset($nomDonnees[1])) ? $nomDonnees[1] : '' ;
			$nomDonnees = $nomDonnees[0] ;
		}
		$defCol->NomDonnees = $nomDonnees ;
		$defCol->Libelle = $libelle ;
		$defCol->AliasDonnees = $aliasDonnees ;
		$this->DefinitionsColonnes[] = & $defCol ;
		return $defCol ;
	}
	public function & InsereDefColBool($nomDonnees, $libelle="", $aliasDonnees="", $valPositive="", $valNegative="")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Booleen() ;
		if($valPositive != "")
			$defCol->ValeurPositive = $valPositive ;
		if($valNegative != "")
			$defCol->ValeurNegative = $valNegative ;
		return $defCol ;
	}
	public function & InsereDefColChoix($nomDonnees, $libelle="", $aliasDonnees="", $valsChoix=array())
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Choix() ;
		$defCol->Formatteur->ValeursChoix = $valsChoix ;
		return $defCol ;
	}
	public function & InsereDefColEditable($nomDonnees, $libelle="", $aliasDonnees="", $nomClsComp="\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Editable() ;
		if($nomClsComp != '')
		{
			$defCol->Formatteur->DeclareComposant($nomClsComp) ;
		}
		return $defCol ;
	}
	public function & InsereDefColMonnaie($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = $this->InsereDefColMoney($nomDonnees, $libelle, $aliasDonnees) ;
		return $defCol ;
	}
	public function & InsereDefColMoney($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Monnaie() ;
		return $defCol ;
	}
	public function & InsereDefColDateFr($nomDonnees, $libelle="", $inclureHeure=0, $aliasDonnees='')
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\DateFr() ;
		$defCol->Formatteur->InclureHeure = $inclureHeure ;
		return $defCol ;
	}
	public function & InsereDefColDateTimeFr($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\DateFr() ;
		$defCol->Formatteur->InclureHeure = 1 ;
		return $defCol ;
	}
	public function & InsereDefColDetail($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\PlusDetail() ;
		return $defCol ;
	}
	public function & InsereDefColFixe($valeur, $libelle="")
	{
		$defCol = $this->InsereDefCol("", $libelle, "") ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Fixe() ;
		$defCol->Formatteur->ValeurParDefaut = $valeur ;
		return $defCol ;
	}
	public function & InsereDefColHtml($modeleHtml="", $libelle="")
	{
		$defCol = $this->InsereDefCol("", $libelle, "") ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\ModeleHtml() ;
		$defCol->Formatteur->ModeleHtml = $modeleHtml ;
		return $defCol ;
	}
	public function & InsereDefColTimestamp($nomDonnees, $libelle="", $formatDate="d/m/Y H:i:s")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, "") ;
		$defCol->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Timestamp() ;
		$defCol->Formatteur->FormatDate = $formatDate ;
		return $defCol ;
	}
	public function & InsereDefColSansTri($nomDonnees, $libelle="", $aliasDonnees="")
	{
		$defCol = $this->InsereDefCol($nomDonnees, $libelle, $aliasDonnees) ;
		$defCol->TriPossible = 0 ;
		return $defCol ;
	}
	public function & InsereDefColActions($libelle, $actions=array())
	{
		$col = new \Pv\ZoneWeb\TableauDonnees\Colonne() ;
		$col->TriPossible = 0 ;
		$col->ExporterDonnees = 0 ;
		$col->Libelle = $libelle ;
		$col->AlignEntete = "center" ;
		$col->AlignElement = "center" ;
		$col->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Liens() ;
		$col->Liens = $actions ;
		$this->DefinitionsColonnes[] = & $col ;
		return $col ;
	}
	public function & InsereLienAction(& $col, $formatUrl='', $formatLib='')
	{
		$lien = null ;
		if($this->EstNul($col) || $col->Formatteur == null)
		{
			return $lien ;
		}
		$lien = $this->CreeLienAction() ;
		$lien->FormatURL = $formatUrl ;
		$lien->FormatLibelle = $formatLib ;
		$col->Formatteur->Liens[] = & $lien ;
		return $lien ;
	}
	public function & InsereLienActionAvant(& $col, $index, $formatUrl='', $formatLib='')
	{
		$lien = null ;
		if($this->EstNul($col) || $col->Formatteur == null)
		{
			return $lien ;
		}
		$lien = $this->CreeLienAction() ;
		$lien->FormatURL = $formatUrl ;
		$lien->FormatLibelle = $formatLib ;
		array_splice($col->Formatteur->Liens, $index, 0, array(& $lien)) ;
		return $lien ;
	}
	public function & InsereIconeAction(& $col, $formatUrl='', $formatCheminIcone='', $formatLib='')
	{
		$lien = null ;
		if($this->EstNul($col) || $col->Formatteur == null)
		{
			return $lien ;
		}
		$lien = $this->CreeLienAction() ;
		$lien->FormatURL = $formatUrl ;
		$lien->FormatCheminIcone = $formatCheminIcone ;
		$lien->FormatLibelle = $formatLib ;
		$lien->InclureLibelle = 0 ;
		$col->Formatteur->Liens[] = & $lien ;
		return $lien ;
	}
	public function & InsereIconeActionAvant(& $col, $index, $formatUrl='', $formatCheminIcone='', $formatLib='')
	{
		$lien = null ;
		if($this->EstNul($col) || $col->Formatteur == null)
		{
			return $lien ;
		}
		$lien = $this->CreeLienAction() ;
		$lien->FormatURL = $formatUrl ;
		$lien->FormatCheminIcone = $formatCheminIcone ;
		$lien->FormatLibelle = $formatLib ;
		$lien->InclureLibelle = 0 ;
		array_splice($col->Formatteur->Liens, $index, 0, array(& $lien)) ;
		return $lien ;
	}
	public function CreeLienAction()
	{
		return new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Lien() ;
	}
	public function & InsereCmdRedirectUrl($nomCmd, $url, $libelle='')
	{
		$cmd = $this->CreeCmdRedirectUrl() ;
		$cmd->Url = $url ;
		$cmd->Libelle = $libelle ;
		$this->InscritCommande($nomCmd, $cmd) ;
		return $cmd ;
	}
	public function & InsereCmdRedirectScript($nomCmd, $nomScript, $libelle='', $params=array())
	{
		$cmd = $this->CreeCmdRedirectScript() ;
		$cmd->NomScript = $nomScript ;
		$cmd->Libelle = $libelle ;
		$cmd->Parametres = $params ;
		$this->InscritCommande($nomCmd, $cmd) ;
		return $cmd ;
	}
	public function & InsereCmdScriptSession($nomCmd, $libelle='', $urlDefaut=array())
	{
		$cmd = new \Pv\ZoneWeb\Commande\RedirectScriptSession() ;
		$this->InscritCommande($nomCmd, $cmd) ;
		$cmd->Libelle = $libelle ;
		return $cmd ;
	}
	public function & InsereCmdExportTxt($nomCmd, $libelle='')
	{
		return $this->InsereCmdExportTexte($nomCmd, $libelle) ;
	}
	public function & InsereCmdExportXls($nomCmd, $libelle='')
	{
		return $this->InsereCmdExportExcel($nomCmd, $libelle) ;
	}
	public function & InsereCmdExportTexte($nomCmd, $libelle='')
	{
		$cmd = new \Pv\ZoneWeb\TableauDonnees\Commande\ExportVers() ;
		if($libelle != '')
			$cmd->Libelle = $libelle ;
		$this->InscritCommande($nomCmd, $cmd) ;
		return $cmd ;
	}
	public function & InsereCmdExportExcel($nomCmd, $libelle='')
	{
		$cmd = new \Pv\ZoneWeb\TableauDonnees\Commande\Excel() ;
		if($libelle != '')
			$cmd->Libelle = $libelle ;
		$this->InscritCommande($nomCmd, $cmd) ;
		return $cmd ;
	}
	public function DefinitionsColonnesExport()
	{
		$colonnes = array() ;
		foreach($this->DefinitionsColonnes as $i => $colonne)
		{
			if($colonne->PeutExporterDonnees())
			{
				$colonnes[] = $colonne ;
			}
		}
		return $colonnes ;
	}
	public function ExtraitValeursExport($ligne, & $cmd)
	{
		$valeurs = array() ;
		$colonnes = $this->DefinitionsColonnesExport() ;
		foreach($colonnes as $i => $colonne)
		{
			$valeur = $colonne->FormatteValeur($this, $ligne) ;
			if($valeur == $colonne->ValeurVide)
				$valeur = $cmd->ValeurVideExport ;
			$valeurs[] = $valeur ;
		}
		return $valeurs ;
	}
	public function ExtraitLibellesExport()
	{
		$valeurs = array() ;
		$colonnes = $this->DefinitionsColonnesExport() ;
		foreach($colonnes as $i => $colonne)
		{
			$valeurs[] = $colonne->ObtientLibelle() ;
		}
		return $valeurs ;
	}
	public function FiltresSoumis()
	{
		$nomParamFiltresSoumis = $this->NomParamFiltresSoumis() ;
		return ($this->ToujoursAfficher || (isset($_GET[$nomParamFiltresSoumis]))) ? 1 : 0 ;
	}
	public function PrepareZone()
	{
		$this->ExecuteCommandeSelectionnee() ;
	}
	public function PrepareRendu()
	{
		parent::PrepareRendu() ;
		if($this->ZoneParent->PreparerComposants == 0)
		{
			$this->ExecuteCommandeSelectionnee() ;
		}
		if(! in_array($this->NomParamFiltresSoumis(), $this->ChampsGetSoumetFormulaire))
		{
			$this->ChampsGetSoumetFormulaire[] = $this->NomParamFiltresSoumis() ;
			if($this->EstPasNul($this->CommandeSelectionnee) && $this->CommandeSelectionnee->InclureEnvoiFiltres())
			{
				$this->ChampsGetSoumetFormulaire[] = $this->NomParamCommandeSelectionnee() ;
			}
		}
		if(! $this->FiltresSoumis() && $this->PossedeFiltresRendus())
		{
			return ;
		}
		$this->DetecteParametresLocalisation() ;
		$this->CalculeElementsRendu() ;
	}
	public function ObtientValeursExtraites($lignes)
	{
		$extracteurs = array() ;
		foreach($this->DefinitionsColonnes as $i => $col)
		{
			if($col->NomDonnees != '' && $col->EstPasNul($col->ExtracteurValeur))
			{
				$extracteurs[$col->NomDonnees] = $col->ExtracteurValeur ;
			}
		}
		if(count($extracteurs) == 0)
		{
			return $lignes ;
		}
		$lignesResultat = array() ;
		foreach($lignes as $i => $ligne)
		{
			$lignesResultat[$i] = $ligne ;
			foreach($extracteurs as $nomDonnees => $extracteur)
			{
				if(! isset($ligne[$nomDonnees]))
				{
					continue ;
				}
				$valeursSuppl = $extracteur->Execute($ligne[$nomDonnees], $this) ;
				// print_r($valeursSuppl) ;
				if(is_array($valeursSuppl))
				{
					$lignesResultat[$i] = array_merge($lignesResultat[$i], \Pv\Misc::array_apply_prefix($valeursSuppl, $nomDonnees.'_')) ;
				}
				// print_r(array_keys($lignesResultat[$i])) ;
			}
		}
		return $lignesResultat ;
	}
	public function ObtientDefColsRendu()
	{
		$defCols = $this->DefinitionsColonnes ;
		return $defCols ;
	}
	protected function AlerteExceptionFournisseur()
	{
		$this->MessageAucunElement = "Exception survenue : ".$this->FournisseurDonnees->DerniereException->Message ;
	}
	public function CalculeElementsRendu()
	{
		$defCols = $this->ObtientDefColsRendu() ;
		$this->TotalElements = $this->FournisseurDonnees->CompteElements($this->DefinitionsColonnes, $this->FiltresSelection) ;
		// print_r($this->FournisseurDonnees->BaseDonnees) ;
		// print_r($this->FournisseurDonnees) ;
		if($this->FournisseurDonnees->ExceptionTrouvee())
		{
			$this->AlerteExceptionFournisseur() ;
		}
		else
		{
			// Ajuster l'indice debut
			if($this->IndiceDebut < 0)
				$this->IndiceDebut = 0 ;
			if($this->IndiceDebut >= $this->TotalElements)
				$this->IndiceDebut = $this->TotalElements ;
			if($this->TotalElements > 0)
			{
				$this->IndiceDebut = intval($this->IndiceDebut / $this->MaxElements) * $this->MaxElements ;
				$this->ElementsEnCoursBruts = $this->FournisseurDonnees->RangeeElements($defCols, $this->FiltresSelection, $this->IndiceDebut, $this->MaxElements, $this->IndiceColonneTri, $this->SensColonneTri) ;
				// echo "Sql : ".$this->FournisseurDonnees->BaseDonnees->LastSqlText ;
				// print_r($this->FournisseurDonnees) ;
				if($this->FournisseurDonnees->ExceptionTrouvee())
				{
					$this->TotalElements = 0 ;
					$this->IndiceDebut = 0 ;
					$this->TotalRangees = 0 ;
					$this->IndiceFin = 0 ;
					$this->RangeeEnCours = -1 ;
					$this->ElementsEnCours = array() ;
					$this->AlerteExceptionFournisseur() ;
				}
				else
				{
					foreach($this->ElementsEnCoursBruts as $ix => $lgn)
					{
						foreach($lgn as $n => $v)
						{
							if($v === null)
							{
								$this->ElementsEnCoursBruts[$ix][$n] = "" ;
							}
						}
					}
					if($this->ExtraireValeursElements)
					{
						$this->ElementsEnCours = $this->ObtientValeursExtraites($this->ElementsEnCoursBruts) ;
					}
					else
					{
						$this->ElementsEnCours = $this->ElementsEnCoursBruts ;
					}
					// echo "Sql : ".$this->FournisseurDonnees->BaseDonnees->LastSqlText ;
					// print_r($this->ElementsEnCours) ;
					$this->RangeeEnCours = $this->IndiceDebut / $this->MaxElements ;
					$nbRangees = intval($this->TotalElements / $this->MaxElements) ;
					$nbRangeesDec = $this->TotalElements / $this->MaxElements ;
					$this->TotalRangees = ($nbRangees == $nbRangeesDec) ? $nbRangeesDec : $nbRangees + 1 ;
					$this->IndiceFin = $this->IndiceDebut + count($this->ElementsEnCours) ;
					if($this->IndiceFin >= $this->TotalElements)
					{
						$this->IndiceFin = $this->TotalElements ;
					}
				}
			}
			else
			{
				$this->IndiceDebut = 0 ;
				$this->TotalRangees = 0 ;
				$this->IndiceFin = 0 ;
				$this->RangeeEnCours = -1 ;
				$this->ElementsEnCours = array() ;
			}
		}
	}
	public function AgregeElements($exprs)
	{
		return $this->FournisseurDonnees->AgregeElements($exprs, $this->FiltresSelection) ;
	}
	protected function RenduDispositifBrut()
	{
		if(! $this->EstBienRefere())
		{
			return $this->RenduMalRefere() ;
		}
		$this->PrepareRendu() ;
		$ctn = '' ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'" class="TableauDonneesHTML">'.PHP_EOL ;
		$ctn .= $this->ContenuAvantRendu ;
		$ctn .= $this->RenduComposants().PHP_EOL ;
		$ctn .= $this->ContenuApresRendu ;
		$ctn .= '</div>' ;
		// print_r($this->FournisseurDonnees->BaseDonnees) ;
		return $ctn ;
	}
	public function AppelJsEnvoiFiltres($parametres)
	{
		return 'SoumetEnvoiFiltres'.$this->IDInstanceCalc.'('.htmlentities(svc_json_encode($parametres)).')' ;
	}
	protected function RenduEnvoiFiltres()
	{
		$parametresRendu = $this->ParametresCommandeSelectionnee() ;
		foreach($this->ParamsGetSoumetFormulaire as $j => $n)
		{
			if(isset($_GET[$n]))
				$parametresRendu[$n] = $_GET[$n] ;
		}
		$nomFiltres = array_keys($this->FiltresSelection) ;
		$ctn = '' ;
		$ctn .= '<form id="FormulaireEnvoiFiltres'.$this->IDInstanceCalc.'" action="?" method="post" style="display:none;">'.PHP_EOL ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FiltresSelection[$nomFiltre] ;
			if(! $filtre->RenduPossible() || $filtre->TypeLiaisonParametre != 'get')
			{
				continue ;
			}
			$valeur = $filtre->Lie() ;
			if($valeur !== null)
			{
				$valeur = htmlspecialchars($valeur) ;
			}
			$ctn .= '<input type="hidden" name="'.htmlspecialchars($filtre->ObtientNomComposant()).'" value="'.$valeur.'" />'.PHP_EOL ;
		}
		$ctn .= '<input type="submit" value="Envoyer" />'.PHP_EOL ;
		$ctn .= '</form>'.PHP_EOL ;
		$ctn .= '<script type="text/javascript">'.PHP_EOL ;
		$ctn .= $this->CtnJsEnvoiFiltres($parametresRendu).PHP_EOL ;
		$ctn .= '</script>' ;
		return $ctn ;
	}
	protected function CtnJsEnvoiFiltres(& $parametresRendu)
	{
		$ctn = '' ;
		$ctn .= 'function SoumetEnvoiFiltres'.$this->IDInstanceCalc.'(parametres)
{
var parametresGet = '.svc_json_encode($parametresRendu).' ;
var idFormulaire = '.svc_json_encode('FormulaireEnvoiFiltres'.$this->IDInstanceCalc).' ;
for(var nom in parametres)
{
if(parametresGet[nom] != undefined)
{
	parametresGet[nom] = parametres[nom] ;
}
else
{
	var tableauNoeuds = document.getElementsByName(nom) ;
	if(tableauNoeuds.length > 0)
	{
		for(var j=0; j<tableauNoeuds.length; j++)
		{
			if(tableauNoeuds[j].form != null && tableauNoeuds[j].form.id != idFormulaire)
			{
				tableauNoeuds[j].value = parametres[nom] ;
			}
		}
	}
}
}
var formulaire = document.getElementById(idFormulaire) ;
if(formulaire != null)
{
var url = "?'.(($this->ZoneParent->ActiverRoutes == 0) ? urlencode($this->ZoneParent->NomParamScriptAppele).'='.urlencode($this->ScriptParent->NomElementZone) : '').'" ;
for(var nom in parametresGet)
{
	if(url != "")
		url += "&" ;
	url += encodeURIComponent(nom) + "=" + encodeURIComponent(parametresGet[nom]) ;
}
formulaire.action = url ;
// alert(url) ;
formulaire.submit() ;
}
}' ;
		return $ctn ;
	}
	protected function CtnJsActualiseFormulaireFiltres()
	{
		$ctn = '' ;
		/*
		*/
		$ctn .= 'var elem = document.getElementById("'.$this->IDInstanceCalc.'") ;
if(elem !== null) {
var forms = elem.getElementsByTagName("form") ;
var formFiltres = null ;
for(var j=0; j<forms.length; j++) {
if(forms[j].className.indexOf("FormulaireFiltres") >= 0) {
formFiltres = forms[j] ;
break ;
}
}
if(formFiltres !== null) {
SoumetFormulaire'.$this->IDInstanceCalc.'(formFiltres) ;
formFiltres.submit() ;
}
else{
window.location.href = window.location.href ;
}
}' ;
		return $ctn ;
	}
	public function PossedeColonneEditable()
	{
		$ok = $this->RangeeDonneesEditable ;
		if($ok == 1)
		{
			return $ok ;
		}
		foreach($this->DefinitionsColonnes as $i => $defCol)
		{
			if($defCol->EstVisible($this->ZoneParent) && $defCol->EstEditable())
			{
				$ok = 1 ;
				break ;
			}
		}
		return $ok ;
	}
	public function RenduFiltresNonRenseignes()
	{
		$ctn = '' ;
		$ctn .= '<p class="FiltresNonRenseignes">'.$this->MessageFiltresNonRenseignes.'</p>' ;
		return $ctn ;
	}
	public function RenduComposants()
	{
		$ctn = "" ;
		$ctn .= $this->RenduEnvoiFiltres().PHP_EOL ;
		$ctn .= $this->DeclarationSoumetFormulaireFiltres($this->FiltresSelection).PHP_EOL ;
		if($this->Titre != "")
		{
			$ctn .= '<div class="Titre">'.$this->Titre.'</div>'.PHP_EOL ;
		}
		foreach($this->DispositionComposants as $i => $indice)
		{
			if($i > 0)
			{
				$ctn .= PHP_EOL ;
			}
			switch($indice)
			{
				case \Pv\ZoneWeb\TableauDonnees\DispositionTableau::FormulaireFiltres :
				{
					$ctn .= $this->RenduFormulaireFiltres() ;
				}
				break ;
				case \Pv\ZoneWeb\TableauDonnees\DispositionTableau::BlocCommandes :
				{
					$ctn .= $this->RenduBlocCommandes() ;
				}
				break ;
				case \Pv\ZoneWeb\TableauDonnees\DispositionTableau::RangeeDonnees :
				{
					$ctn .= $this->RenduRangeeDonnees() ;
				}
				break ;
				case \Pv\ZoneWeb\TableauDonnees\DispositionTableau::NavigateurRangees :
				{
					$ctn .= $this->RenduNavigateurRangees() ;
				}
				break ;
				default :
				{
					$ctn .= $this->RenduAutreComposantSupport($indice) ;
				}
				break ;
			}
		}
		return $ctn ;
	}
	protected function RenduAutreComposantSupport($indice)
	{
	}
	public function PossedeFiltresRendus()
	{
		$nomFiltres = array_keys($this->FiltresSelection) ;
		$ok = 0 ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$ok = $this->FiltresSelection[$nomFiltre]->RenduPossible() ;
			if($ok)
			{
				break ;
			}
		}
		return $ok ;
	}
	protected function RenduFormulaireFiltres()
	{
		if($this->CacherFormulaireFiltres)
			return '' ;
		if($this->EstNul($this->DessinateurFiltresSelection))
		{
			$this->InitDessinateurFiltresSelection() ;
		}
		if($this->EstNul($this->DessinateurFiltresSelection))
		{
			return "<p>Le dessinateur de filtres n'est pas défini</p>" ;
		}
		if($this->MaxFiltresSelectionParLigne > 0)
		{
			$this->DessinateurFiltresSelection->MaxFiltresParLigne = $this->MaxFiltresSelectionParLigne ;
		}
		$ctn = "" ;
		if(! $this->PossedeFiltresRendus())
		{
			return '' ;
		}
		$largeur = ($this->LargeurFormulaireFiltres != 0) ? $this->LargeurFormulaireFiltres : '100%' ;
		$ctn .= '<form class="FormulaireFiltres" method="post" enctype="multipart/form-data" onsubmit="return SoumetFormulaire'.$this->IDInstanceCalc.'(this) ;">'.PHP_EOL ;
		$ctn .= '<table width="'.$largeur.'" cellspacing="0"'.(($this->AlignFormulaireFiltres != '') ? ' align="'.$this->AlignFormulaireFiltres.'"' : '').'>'.PHP_EOL ;
		if($this->TitreFormulaireFiltres != '')
		{
			$ctn .= '<tr>'.PHP_EOL ;
			$ctn .= '<th align="'.$this->AlignTitreFormulaireFiltres.'">'.PHP_EOL ;
			$ctn .= $this->TitreFormulaireFiltres ;
			$ctn .= '</th>'.PHP_EOL ;
			$ctn .= '</tr>'.PHP_EOL ;
		}
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<td>'.PHP_EOL ;
		$ctn .= $this->DessinateurFiltresSelection->Execute($this->ScriptParent, $this, $this->FiltresSelection) ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '<tr class="Boutons">'.PHP_EOL ;
		$ctn .= '<td align="'.$this->AlignBoutonSoumettreFormulaireFiltres.'">'.PHP_EOL ;
		$ctn .= '<input type="hidden" name="'.$this->NomParamFiltresSoumis().'" id="'.$this->NomParamFiltresSoumis().'" value="1" />'.PHP_EOL ;
		$ctn .= '<button type="submit">'.$this->TitreBoutonSoumettreFormulaireFiltres.'</button>'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '</table>'.PHP_EOL ;
		$ctn .= '</form>' ;
		return $ctn ;
	}
	protected function ExtraitCommandesRendu()
	{
		return $this->Commandes ;
	}
	protected function RenduBlocCommandes()
	{
		$ctn = '' ;
		if($this->CacherBlocCommandes || (! $this->FiltresSoumis() && $this->PossedeFiltresRendus()))
		{
			return $ctn ;
		}
		$commandes = $this->ExtraitCommandesRendu() ;
		if(count($commandes) == 0 && $this->SautLigneSansCommande == 1)
		{
			return '<br>' ;
		}
		// $parametres = $this->Filtre
		if($this->EstNul($this->DessinateurBlocCommandes))
		{
			$this->InitDessinateurBlocCommandes() ;
		}
		if($this->EstNul($this->DessinateurBlocCommandes))
		{
			return "<p>Le dessinateur de filtres n'est pas défini</p>" ;
		}
		$ctn .= '<div class="BlocCommandes">'.PHP_EOL ;
		$ctn .= $this->DessinateurBlocCommandes->Execute($this->ScriptParent, $this, $commandes) ;
		$ctn .= $this->DeclarationJsActiveCommande().PHP_EOL ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
	public function ParametresRendu()
	{
		$nomParamMaxElements = $this->NomParamMaxElements() ;
		$nomParamIndiceDebut = $this->NomParamIndiceDebut() ;
		$nomParamIndiceColonneTri = $this->NomParamIndiceColonneTri() ;
		$nomParamSensColonneTri = $this->NomParamSensColonneTri() ;
		$parametres = array(
			$nomParamMaxElements => $this->MaxElements,
			$nomParamIndiceDebut => $this->IndiceDebut,
			$nomParamIndiceColonneTri => $this->IndiceColonneTri,
			$nomParamSensColonneTri => $this->SensColonneTri,
		) ;
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
	protected function ParametresCommandeSelectionnee()
	{
		$parametres = $this->ParametresRendu() ;
		$parametres[$this->NomParamFiltresSoumis()] = 1 ;
		$parametres[$this->NomParamCommandeSelectionnee()] = "" ;
		if($this->ForcerDesactCache)
		{
			$parametres[$this->NomParamIdAleat()] = rand(0, 999999) ;
		}
		return $parametres ;
	}
	protected function ObtientNomClsCSSElem($index, & $elem)
	{
		$classePair = ($index % 2 == 0) ? "Pair" : "Impair" ;
		return 'Contenu '.$classePair ;
	}
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		if($this->FiltresSoumis() || ! $this->PossedeFiltresRendus())
		{
			$libelleTriAsc = $this->LibelleTriAsc ;
			$libelleTriDesc = $this->LibelleTriDesc ;
			$libelleTriAscSelectionne = $this->LibelleTriAscSelectionne ;
			$libelleTriDescSelectionne = $this->LibelleTriDescSelectionne ;
			if($this->UtiliserIconesTri)
			{
				$libelleTriAsc = '<img border="0" src="'.$this->CheminRelativeIconesTri."/".$this->NomIconeTriAsc.'" />' ;
				$libelleTriDesc = '<img border="0" src="'.$this->CheminRelativeIconesTri."/".$this->NomIconeTriDesc.'" />' ;
				$libelleTriAscSelectionne = '<img border="0" src="'.$this->CheminRelativeIconesTri."/".$this->NomIconeTriAscSelectionne.'" />' ;
				$libelleTriDescSelectionne = '<img border="0" src="'.$this->CheminRelativeIconesTri."/".$this->NomIconeTriDescSelectionne.'" />' ;
			}
			$parametresRendu = $this->ParametresCommandeSelectionnee() ;
			if(count($this->ElementsEnCours) > 0)
			{
				if($this->PossedeColonneEditable())
				{
					$ctnChampsPost = "" ;
					$nomFiltres = array_keys($this->FiltresSelection) ;
					$parametresRenduEdit = $this->ParametresCommandeSelectionnee() ;
					foreach($this->ParamsGetSoumetFormulaire as $j => $n)
					{
						if(isset($_GET[$n]))
							$parametresRenduEdit[$n] = $_GET[$n] ;
					}
					foreach($nomFiltres as $i => $nomFiltre)
					{
						$filtre = & $this->FiltresSelection[$nomFiltre] ;
						if($filtre->RenduPossible())
						{
							if($filtre->TypeLiaisonParametre == 'post')
							{
								$ctnChampsPost .= '<input type="hidden" name="'.htmlspecialchars($filtre->ObtientNomComposant()).'" value="'.htmlspecialchars($filtre->Lie()).'" />'.PHP_EOL ;
							}
							elseif($filtre->TypeLiaisonParametre == 'get')
							{
								$parametresRenduEdit[$filtre->ObtientNomComposant()] = $filtre->Lie() ;
							}
						}
					}
					$ctn .= '<form id="FormRangee'.$this->IDInstanceCalc.'" action="?'.(($this->ZoneParent->ActiverRoutes == 0) ? urlencode($this->ZoneParent->NomParamScriptAppele).'='.urlencode($this->ZoneParent->ValeurParamScriptAppele) : '').'&'.\Pv\Misc::http_build_query_string($parametresRenduEdit).'" method="post">'.PHP_EOL ;
					$ctn .= $ctnChampsPost ;
				}
				$ctn .= '<table' ;
				$ctn .= ' class="RangeeDonnees"' ;
				if($this->Largeur != "")
				{
					$ctn .= ' width="'.$this->Largeur.'"' ;
				}
				if($this->Hauteur != "")
				{
					$ctn .= ' height="'.$this->Hauteur.'"' ;
				}
				if($this->EspacementCell != "")
				{
					$ctn .= ' cellpadding="'.$this->EspacementCell.'"' ;
				}
				if($this->MargesCell != "")
				{
					$ctn .= ' cellspacing="'.$this->MargesCell.'"' ;
				}
				if($this->LargeurBordure != "")
				{
					$ctn .= ' border="'.$this->LargeurBordure.'"' ;
					if($this->CouleurBordure != "")
					{
						$ctn .= ' bordercolor="'.$this->CouleurBordure.'"' ;
					}
				}
				$ctn .= '>'.PHP_EOL ;
				$ctn .= '<tr class="Entete">'.PHP_EOL ;
				foreach($this->DefinitionsColonnes as $i => $colonne)
				{
					if(! $colonne->EstVisible($this->ZoneParent))
						continue ;
					$triPossible = ($this->TriPossible && $colonne->TriPossible) ;
					$ctn .= ($triPossible) ? '<td' : '<th' ;
					if($colonne->Largeur != "")
					{
						$ctn .= ' width="'.$colonne->Largeur.'"' ;
					}
					if($colonne->AlignEntete != "")
					{
						$ctn .= ' align="'.$colonne->AlignEntete.'"' ;
					}
					$ctn .= '>' ;
					if($triPossible)
					{
						$ctn .= '<table width="100%" cellspacing="0" cellpadding="2">' ;
						$ctn .= '<tr>' ;
						$ctn .= '<th width="*" rowspan="2">' ;
					}
					$ctn .= $colonne->ObtientLibelle() ;
					if($triPossible)
					{
						$ctn .= '</th>' ;
						$selectionne = ($this->IndiceColonneTri == $i && $this->SensColonneTri == "asc") ;
						$paramColAsc = array_merge($parametresRendu, array($this->NomParamSensColonneTri() => "asc", $this->NomParamIndiceColonneTri() => $i, $this->NomParamIndiceDebut() => 0)) ;
						$ctn .= '<td'.(($selectionne) ? ' class="ColonneTriee"' : '').'>' ;
						$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramColAsc).'">'.(($selectionne && $libelleTriAscSelectionne != "") ? $libelleTriAscSelectionne : $libelleTriAsc).'</a>' ;
						$ctn .= '</td>' ;
						$ctn .= '</tr>' ;
						$ctn .= '<tr>' ;
						$selectionne = ($this->IndiceColonneTri == $i && $this->SensColonneTri == "desc") ;
						$paramColAsc = array_merge($parametresRendu, array($this->NomParamSensColonneTri() => "desc", $this->NomParamIndiceColonneTri() => $i, $this->NomParamIndiceDebut() => 0)) ;
						$ctn .= '<td'.(($selectionne) ? ' class="ColonneTriee"' : '').'>' ;
						$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramColAsc).'">'.(($selectionne && $libelleTriDescSelectionne != "") ? $libelleTriDescSelectionne : $libelleTriDesc).'</a>' ;
						$ctn .= '</td>' ;
						$ctn .= '</tr>' ;
						$ctn .= '</table>' ;
					}
					$ctn .= (($triPossible) ? '</td>' : '</th>').PHP_EOL ;
				}
				$ctn .= '</tr>'.PHP_EOL ;
				foreach($this->ElementsEnCours as $j => $ligne)
				{
					$ctn .= '<tr' ;
					$ctn .= ' class="'.htmlentities($this->ObtientNomClsCSSElem($j, $ligne)) .'"' ;
					if($this->SurvolerLigneFocus)
					{
						$ctn .= ' onMouseOver="this.className = this.className + &quot; Survole&quot;;" onMouseOut="this.className = this.className.split(&quot; Survole&quot;).join(&quot; &quot;) ;"' ;
					}
					$ctn .= '>'.PHP_EOL ;
					$ligneDonnees = $ligne ;
					$ligneDonnees = $this->SourceValeursSuppl->Applique($this, $ligneDonnees) ;
					foreach($this->DefinitionsColonnes as $i => $colonne)
					{
						// print_r($ligne) ;
						if(! $colonne->EstVisible($this->ZoneParent))
							continue ;
						$ctn .= '<td' ;
						if($colonne->AlignElement != "")
						{
							$ctn .= ' align="'.$colonne->AlignElement.'"' ;
						}
						if($colonne->StyleCSS != '')
						{
							$ctn .= ' style="'.htmlentities($colonne->StyleCSS).'"' ;
						}
						if($colonne->NomClasseCSS != '')
						{
							$ctn .= ' class="'.htmlentities($colonne->NomClasseCSS).'"' ;
						}
						$ctn .= '>' ;
						$ctn .= $colonne->FormatteValeur($this, $ligneDonnees) ;
						$ctn .= '</td>'.PHP_EOL ;
					}
					$ctn .= '</tr>'.PHP_EOL ;
				}
				$ctn .= '</table>' ;
				if($this->PossedeColonneEditable())
				{
					$ctn .= PHP_EOL .'<div style="display:none"><input type="submit" /></div>
</form>' ;
				}
			}
			elseif($this->AlerterAucunElement == 1)
			{
				$ctn .= '<p class="AucunElement">'.$this->MessageAucunElement.'</p>' ;
			}
		}
		else
		{
			$ctn .= $this->RenduFiltresNonRenseignes() ;
		}
		return $ctn ;
	}
	protected function RenduNavigateurRangeesInt()
	{
		$ctn = '' ;
		$parametresRendu = $this->ParametresRendu() ;
		$ctn .= '<table class="NavigateurRangees"' ;
		if($this->Largeur != '')
			$ctn .= ' width="'.$this->Largeur.'"' ;
		$ctn .= ' cellspacing="0">'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<td align="left" width="50%" class="LiensRangee">'.PHP_EOL ;
		$paramPremiereRangee = array_merge($parametresRendu, array($this->NomParamIndiceDebut() => 0)) ;
		$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramPremiereRangee).'" title="'.$this->TitrePremiereRangee.'">'.$this->LibellePremiereRangee.'</a>'.PHP_EOL ;
		$ctn .= $this->SeparateurLiensRangee ;
		if($this->RangeeEnCours > 0)
		{
			$paramRangeePrecedente = array_merge($parametresRendu, array($this->NomParamIndiceDebut() => ($this->RangeeEnCours - 1) * $this->MaxElements)) ;
			$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramRangeePrecedente).'" title="'.$this->TitreRangeePrecedente.'">'.$this->LibelleRangeePrecedente.'</a>'.PHP_EOL ;
		}
		else
		{
			$ctn .= '<a title="'.$this->TitreRangeePrecedente.'">'.$this->LibelleRangeePrecedente.'</a>'.PHP_EOL ;
		}
		$ctn .= $this->SeparateurLiensRangee ;
		$ctn .= '<input type="text" size="4" onChange="var nb = 0 ; try { nb = parseInt(this.value) ; } catch(ex) { } if (isNaN(nb) == true) { nb = 0 ; } SoumetEnvoiFiltres'.$this->IDInstanceCalc.'({'.htmlentities(svc_json_encode($this->NomParamIndiceDebut())).' : (nb - 1) * '.$this->MaxElements.'}) ;" value="'.($this->RangeeEnCours + 1).'" style="text-align:center" />'.PHP_EOL ;
		$ctn .= $this->SeparateurLiensRangee ;
		//echo $this->RangeeEnCours." &lt; ".(intval($this->TotalElements / $this->MaxElements) - 1) ;
		if($this->RangeeEnCours < intval($this->TotalElements / $this->MaxElements))
		{
			$paramRangeeSuivante = array_merge($parametresRendu, array($this->NomParamIndiceDebut() => ($this->RangeeEnCours + 1) * $this->MaxElements)) ;
			$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramRangeeSuivante).'" title="'.$this->TitreRangeeSuivante.'">'.$this->LibelleRangeeSuivante.'</a>'.PHP_EOL ;
		}
		else
		{
			$ctn .= '<a title="'.$this->TitreRangeeSuivante.'">'.$this->LibelleRangeeSuivante.'</a>'.PHP_EOL ;
		}
		$paramDerniereRangee = array_merge($parametresRendu, array($this->NomParamIndiceDebut() => intval($this->TotalElements / $this->MaxElements) * $this->MaxElements)) ;
		$ctn .= $this->SeparateurLiensRangee ;
		$ctn .= '<a href="javascript:'.$this->AppelJsEnvoiFiltres($paramDerniereRangee).'" title="'.$this->TitreDerniereRangee.'">'.$this->LibelleDerniereRangee.'</a>'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '<td align="right" class="InfosRangees" width="*">'.PHP_EOL ;
		$valeursRangee = array(
			'IndiceDebut' => $this->IndiceDebut,
			'NoDebut' => $this->IndiceDebut + 1,
			'IndiceFin' => $this->IndiceFin,
			'NoFin' => $this->IndiceFin,
			'TotalElements' => $this->TotalElements,
		) ;
		$ctn .= \Pv\Misc::_parse_pattern($this->FormatInfosRangee, $valeursRangee) ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '</table>' ;
		return $ctn ;
	}
	protected function RenduNavigateurRangees()
	{
		$ctn = '' ;
		if(! $this->CacherNavigateurRangees && ! ($this->CacherNavigateurRangeesAuto && $this->TotalElements <= $this->MaxElements) && $this->TotalElements > 0)
		{
			if($this->EstNul($this->NavigateurRangees))
			{
				$ctn .= $this->RenduNavigateurRangeesInt() ;
			}
			else
			{
				$ctn .= $this->NavigateurRangees->Execute($this->ScriptParent, $this) ;
			}
		}
		return $ctn ;
	}
	protected function InitDessinateurFiltresSelection()
	{
		$this->DessinateurFiltresSelection = new \Pv\ZoneWeb\DessinFiltres\Html() ;
	}
	protected function InitDessinateurBlocCommandes()
	{
		$this->DessinateurBlocCommandes = new \Pv\ZoneWeb\DessinCommandes\DessinCommandes() ;
	}
	public function AppliqueScriptParentValsSuppl()
	{
		$this->SourceValeursSuppl = new \Pv\ZoneWeb\Donnees\SrcValsSuppl\AppliqueScriptParent() ;
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
	public function AppliqueZoneParentValsSuppl()
	{
		$this->SourceValeursSuppl = new \Pv\ZoneWeb\Donnees\SrcValsSuppl\AppliqueZoneParent() ;
	}
}