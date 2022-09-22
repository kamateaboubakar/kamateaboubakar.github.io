<?php

namespace Pv\ZoneBootstrap\TableauDonnees ;

class GrilleDonnees extends \Pv\ZoneWeb\TableauDonnees\GrilleDonnees
{
	public $ClasseCSSRangee = "table-striped" ;
	public $ClasseCSSCellule = "" ;
	public $EncadrerCellule = 1 ;
	public $ClasseCSSBtnNav = "btn-primary" ;
	public $ClsBstEnteteFormFiltres ;
	public $ClsBstPiedFormFiltres ;
	public $ClsBstBoutonSoumettre = "btn-success" ;
	public $ClsBstFormFiltresSelect = "col-12" ;
	public $SautLigneSansCommande = 0 ;
	public $MaxColonnesXs = 0 ;
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
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		if($this->FiltresSoumis() || ! $this->PossedeFiltresRendus())
		{
			$this->DetecteContenuLigneModeleUse() ;
			$parametresRendu = $this->ParametresCommandeSelectionnee() ;
			if(count($this->ElementsEnCours) > 0)
			{
				$ctn .= '<div' ;
				if($this->EncadrerCellule == 1)
				{
					$ctn .= ' class="RangeeDonnees container-fluid">'.PHP_EOL ;
				}
				else
				{
					$ctn .= ' class="RangeeDonnees row">'.PHP_EOL ;
				}
				$inclureLargCell = 1 ;
				$maxColsXs = ($this->MaxColonnesXs > 0) ? $this->MaxColonnesXs : $this->MaxColonnes ;
				$colXs = 12 / $maxColsXs ;
				$colDef = 12 / $this->MaxColonnes ;
				foreach($this->ElementsEnCours as $j => $ligne)
				{
					if($this->EncadrerCellule == 1)
					{
						if($this->MaxColonnes <= 1 || $j % $this->MaxColonnes == 0)
						{
							$ctn .= '<div class="row">'.PHP_EOL ;
						}
						$ctn .= '<div class="Contenu col-'.$colXs.' col-sm-'.$colDef.(($this->ClasseCSSCellule != '') ? ' '.$this->ClasseCSSCellule : '').'"' ;
						$ctn .= ' align="'.$this->AlignCellule.'"' ;
						$ctn .= '>'.PHP_EOL ;
					}
					$ligneDonnees = $ligne ;
					$ligneDonnees["POSITION"] = $j ;
					$ligneDonnees["NO"] = $j + 1 ;
					foreach($this->DefinitionsColonnes as $i => $colonne)
					{
						if($colonne->Visible == 0)
							continue ;
						$ligneDonnees["VALEUR_COL_".$i] = $colonne->FormatteValeur($this, $ligne) ;
						if($colonne->NomDonnees != "")
						{
							$ligneDonnees["VALEUR_COL_".$colonne->NomDonnees] = $ligneDonnees["VALEUR_COL_".$i] ;
						}
					}
					$ligneDonnees = $this->SourceValeursSuppl->Applique($this, $ligneDonnees) ;
					$ctn .= \Pv\Misc::_parse_pattern($this->ContenuLigneModeleUse, $ligneDonnees) ;
					if($this->EncadrerCellule == 1)
					{
						$ctn .= '</div>'.PHP_EOL ;
						if($this->MaxColonnes <= 1 || $j % $this->MaxColonnes == $this->MaxColonnes - 1)
						{
							$ctn .= '</div>'.PHP_EOL ;
							$inclureLargCell = 0 ;
						}
					}
				}
				if($this->EncadrerCellule == 1)
				{
					if($this->MaxColonnes > 1 && count($this->ElementsEnCours) % $this->MaxColonnes != 0)
					{
						$ctn .= '</div>'.PHP_EOL ;
					}
				}
				$ctn .= '</div>' ;
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
}