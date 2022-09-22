<?php

namespace Pv\ZoneBootstrap\TableauDonnees ;

class TableauDonnees extends \Pv\ZoneWeb\TableauDonnees\TableauDonnees
{
	public $SautLigneSansCommande = 0 ;
	public $Responsive = true ;
	public $ClasseCSSRangee = "table-striped table-hover" ;
	public $ClasseCSSBtnNav = "btn-primary" ;
	public $ClsBstBoutonSoumettre = "btn-success" ;
	public $ClsBstEnteteFormFiltres ;
	public $ClsBstPiedFormFiltres ;
	public $ClsBstFormFiltresSelect = "col-12" ;
	public $ClsBstBlocCommandes = "text-dark bg-light" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->DessinateurFiltresSelection = new \Pv\ZoneBootstrap\DessinFiltres\DessinFiltres() ;
		$this->DessinateurBlocCommandes = new \Pv\ZoneBootstrap\DessinCommandes\DessinCommandes() ;
		$this->NavigateurRangees = new NavTableauDonnees() ;
	}
	protected function RenduFormulaireFiltres()
	{
		if($this->CacherFormulaireFiltres)
			return '' ;
		if($this->EstNul($this->DessinateurFiltresSelection))
		{
			$this->InitDessinateurFiltresSelection() ;
		}
		// print_r(get_class($this->DessinateurFiltresSelection)) ;
		if($this->EstNul($this->DessinateurFiltresSelection))
		{
			return "<p>Le dessinateur de filtres n'est pas d&eacute;fini</p>" ;
		}
		$ctn = "" ;
		if($this->MaxFiltresSelectionParLigne > 0)
		{
			$this->DessinateurFiltresSelection->MaxFiltresParLigne = $this->MaxFiltresSelectionParLigne ;
		}
		if(! $this->PossedeFiltresRendus())
		{
			return '' ;
		}
		$ctn .= '<form class="FormulaireFiltres" method="post" enctype="multipart/form-data" onsubmit="return SoumetFormulaire'.$this->IDInstanceCalc.'(this) ;" role="form">'.PHP_EOL ;
		$ctn .= '<div class="card card-primary">'.PHP_EOL ;
		if($this->TitreFormulaireFiltres != '')
		{
			$ctn .= '<div class="card-header'.(($this->ClsBstEnteteFormFiltres == '') ? '' : ' '.$this->ClsBstEnteteFormFiltres).'" align="'.$this->AlignTitreFormulaireFiltres.'">'.PHP_EOL ;
			$ctn .= $this->TitreFormulaireFiltres ;
			$ctn .= '</div>'.PHP_EOL ;
		}
		$ctn .= '<div class="card-body">'.PHP_EOL ;
		$ctn .= '<div class="row">'.PHP_EOL ;
		$ctn .= '<div class="'.$this->ClsBstFormFiltresSelect.'">'.PHP_EOL ;
		$ctn .= $this->DessinateurFiltresSelection->Execute($this->ScriptParent, $this, $this->FiltresSelection) ;
		$ctn .= '<input type="hidden" name="'.$this->NomParamFiltresSoumis().'" id="'.$this->NomParamFiltresSoumis().'" value="1" />'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '<div class="card-footer'.(($this->ClsBstPiedFormFiltres == '') ? '' : ' '.$this->ClsBstPiedFormFiltres).'" align="'.$this->AlignBoutonSoumettreFormulaireFiltres.'">'.PHP_EOL ;
		$ctn .= '<button class="btn '.$this->ClsBstBoutonSoumettre.'" type="submit">'.$this->TitreBoutonSoumettreFormulaireFiltres.'</button>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</form>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduBlocCommandes()
	{
		$ctn = trim(parent::RenduBlocCommandes()) ;
		if(count($this->Commandes) > 0)
		{
			$ctn = '<div class="card '.$this->ClsBstBlocCommandes.'">
<div class="card-footer">'.PHP_EOL
.$ctn.PHP_EOL
.'</div>
</div>' ;
		}
		return $ctn ;
	}
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		if($this->FiltresSoumis() || ! $this->PossedeFiltresRendus())
		{
			if($this->ZoneParent->InclureFontAwesome == true)
			{
				$libelleTriAsc = '<span data-fa-transform="up-4" class="text-muted fa fa-sort-up" title="'.htmlspecialchars($this->LibelleTriAsc).'"></span>' ;
				$libelleTriDesc = '<span data-fa-transform="down-4" class="text-muted fa fa-sort-down" title="'.htmlspecialchars($this->LibelleTriDesc).'"></span>' ;
				$libelleTriAscSelectionne = '<span data-fa-transform="up-4" class="fa fa-sort-up" title="'.htmlspecialchars($this->LibelleTriAsc).'"></span>' ;
				$libelleTriDescSelectionne = '<span data-fa-transform="down-4" class="fa fa-sort-down" title="'.htmlspecialchars($this->LibelleTriDesc).'"></span>' ;
			}
			else
			{
				$libelleTriAsc = '<span class="text-muted" title="'.htmlspecialchars($this->LibelleTriAsc).'">Asc</span>' ;
				$libelleTriDesc = '<span class="text-muted fa fa-sort-down" title="'.htmlspecialchars($this->LibelleTriDesc).'">Desc</span>' ;
				$libelleTriAscSelectionne = '<span title="'.htmlspecialchars($this->LibelleTriAsc).'">Asc</span>' ;
				$libelleTriDescSelectionne = '<span class="fa" title="'.htmlspecialchars($this->LibelleTriDesc).'">Desc</span>' ;
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
					$ctn .= '<form id="FormRangee'.$this->IDInstanceCalc.'" action="?'.(($this->ZoneParent->ActiverRoutes == 0) ? urlencode($this->ZoneParent->NomParamScriptAppele).'='.urlencode($this->ZoneParent->ValeurParamScriptAppele).'&' : '').\Pv\Misc::http_build_query_string($parametresRenduEdit).'" method="post">'.PHP_EOL ;
					$ctn .= $ctnChampsPost ;
				}
				$ctn .= '<div class="card">
<div class="card-body'.(($this->Responsive) ? ' table-responsive' : '').'">'.PHP_EOL ;
				$ctn .= '<table' ;
				$ctn .= ' class="RangeeDonnees table '.$this->ClasseCSSRangee.'"' ;
				$ctn .= '>'.PHP_EOL ;
				$ctn .= '<thead>'.PHP_EOL ;
				$ctn .= '<tr class="Entete">'.PHP_EOL ;
				foreach($this->DefinitionsColonnes as $i => $colonne)
				{
					if(! $colonne->EstVisible($this->ZoneParent))
						continue ;
					$triPossible = ($this->TriPossible && $colonne->TriPossible) ;
					$ctn .= '<th' ;
					if($colonne->Largeur != "")
					{
						$ctn .= ' width="'.$colonne->Largeur.'"' ;
					}
					if($colonne->AlignEntete != "")
					{
						$ctn .= ' align="'.$colonne->AlignEntete.'"' ;
					}
					$ctn .= '>' ;
					$ctn .= $colonne->ObtientLibelle() ;
					if($triPossible)
					{
						$selectionne = ($this->IndiceColonneTri == $i && $this->SensColonneTri == "asc") ;
						$paramColAsc = array_merge($parametresRendu, array($this->NomParamSensColonneTri() => "asc", $this->NomParamIndiceColonneTri() => $i, $this->NomParamIndiceDebut() => 0)) ;
						$ctn .= ' <a href="javascript:'.$this->AppelJsEnvoiFiltres($paramColAsc).'"'.(($selectionne) ? ' class="ColonneTriee"' : '').'>' ;
						$ctn .= (($selectionne && $libelleTriAscSelectionne != "") ? $libelleTriAscSelectionne : $libelleTriAsc) ;
						$ctn .= '</a>' ;
						$selectionne = ($this->IndiceColonneTri == $i && $this->SensColonneTri == "desc") ;
						$paramColAsc = array_merge($parametresRendu, array($this->NomParamSensColonneTri() => "desc", $this->NomParamIndiceColonneTri() => $i, $this->NomParamIndiceDebut() => 0)) ;
						$ctn .= ' <a href="javascript:'.$this->AppelJsEnvoiFiltres($paramColAsc).'"'.(($selectionne) ? ' class="ColonneTriee"' : '').'>' ;
						$ctn .= (($selectionne && $libelleTriDescSelectionne != "") ? $libelleTriDescSelectionne : $libelleTriDesc) ;
						$ctn .= '</a>' ;
					}
					$ctn .= '</th>'.PHP_EOL ;
				}
				$ctn .= '</tr>'.PHP_EOL ;
				$ctn .= '</thead>'.PHP_EOL ;
				$ctn .= '<tbody>'.PHP_EOL ;
				foreach($this->ElementsEnCours as $j => $ligne)
				{
					$ctn .= '<tr>'.PHP_EOL ;
					foreach($this->DefinitionsColonnes as $i => $colonne)
					{
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
						$ligneDonnees = $ligne ;
						$ligneDonnees = $this->SourceValeursSuppl->Applique($this, $ligneDonnees) ;
						$ctn .= $colonne->FormatteValeur($this, $ligneDonnees) ;
						$ctn .= '</td>'.PHP_EOL ;
					}
					$ctn .= '</tr>'.PHP_EOL ;
				}
				$ctn .= '</tbody>'.PHP_EOL ;
				$ctn .= '</table>'.PHP_EOL ;
				$ctn .= '</div>
</div>' ;
				if($this->PossedeColonneEditable())
				{
					$ctn .= PHP_EOL .'<div style="display:none"><input type="submit" /></div>
</form>' ;
				}
			}
			else
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
}
