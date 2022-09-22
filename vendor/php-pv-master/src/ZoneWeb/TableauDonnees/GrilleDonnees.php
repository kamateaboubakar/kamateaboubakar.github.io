<?php

namespace Pv\ZoneWeb\TableauDonnees ;

class GrilleDonnees extends \Pv\ZoneWeb\TableauDonnees\TableauDonnees
{
	public $TypeComposant = 'GrilleDonneesHTML' ;
	public $ContenuLigneModele = '' ;
	public $ContenuLigneModeleUse = '' ;
	public $EmpilerValeursSiModLigVide = 1 ;
	public $OrientationValeursEmpilees = "vertical" ;
	public $AlignVCellule = "middle" ;
	public $AlignCellule = "" ;
	public $AccepterTriColonneInvisible = 1 ;
	public $MaxColonnes = 1 ;
	public $LargeurBordure = 0 ;
	public $SourceValeursSuppl ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->SourceValeursSuppl = new \Pv\ZoneWeb\TableauDonnees\SrcValsSuppl() ;
	}
	protected function DetecteContenuLigneModeleUse()
	{
		$this->ContenuLigneModeleUse = $this->ContenuLigneModele ;
		if(empty($this->ContenuLigneModeleUse) && $this->EmpilerValeursSiModLigVide)
		{
			$this->ContenuLigneModeleUse .= '<table width="100%" cellspacing="0">'.PHP_EOL ;
			switch($this->OrientationValeursEmpilees)
			{
				case "vertical" :
				{
					foreach($this->DefinitionsColonnes as $i => $colonne)
					{
						$this->ContenuLigneModeleUse .= '<tr><td>${VALEUR_COL_'.$i.'}</td></tr>'.PHP_EOL ;
					}
				}
				break ;
				default :
				{
					$this->ContenuLigneModeleUse .= '<tr>'.PHP_EOL ;
					foreach($this->DefinitionsColonnes as $i => $colonne)
					{
						$this->ContenuLigneModeleUse .= '<td>${VALEUR_COL_'.$i.'}</td>'.PHP_EOL ;
					}
					$this->ContenuLigneModeleUse .= '</tr>'.PHP_EOL ;
				}
				break ;
			}
			$this->ContenuLigneModeleUse .= '</table>' ;
		}
	}
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		if($this->FiltresSoumis() || ! $this->PossedeFiltresRendus())
		{
			$this->DetecteContenuLigneModeleUse() ;
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
				$inclureLargCell = 1 ;
				foreach($this->ElementsEnCours as $j => $ligne)
				{
					if($this->MaxColonnes <= 1 || $j % $this->MaxColonnes == 0)
					{
						$ctn .= '<tr>'.PHP_EOL ;
					}
					$classePair = ($j % 2 == 0) ? "Pair" : "Impair" ;
					$ctn .= '<td' ;
					if($inclureLargCell)
					{
						$pourcentCol = ($this->MaxColonnes > 1) ? intval(100 / $this->MaxColonnes) : "100" ;
						$ctn .= ' width="'.$pourcentCol.'%"' ;
					}
					$ctn .= ' class="Contenu '.$classePair.'"' ;
					$ctn .= ' valign="'.$this->AlignVCellule.'"' ;
					if($this->AlignCellule != '')
					{
						$ctn .= ' align="'.$this->AlignCellule.'"' ;
					}
					$ctn .= '>'.PHP_EOL ;
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
					$ctn .= '</td>'.PHP_EOL ;
					if($this->MaxColonnes <= 1 || $j % $this->MaxColonnes == $this->MaxColonnes - 1)
					{
						$ctn .= '</tr>'.PHP_EOL ;
						$inclureLargCell = 0 ;
					}
				}
				if($this->MaxColonnes > 1 && count($this->ElementsEnCours) % $this->MaxColonnes != 0)
				{
					$colFusionnees = $this->MaxColonnes - (count($this->ElementsEnCours) % $this->MaxColonnes) ;
					$ctn .= '<td colspan="'.$colFusionnees.'"></td>'.PHP_EOL ;
					$ctn .= '</tr>'.PHP_EOL ;
				}
				$ctn .= '</table>' ;
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