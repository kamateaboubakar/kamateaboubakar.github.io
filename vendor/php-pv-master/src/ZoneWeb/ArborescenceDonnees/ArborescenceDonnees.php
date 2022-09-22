<?php

namespace Pv\ZoneWeb\ArborescenceDonnees ;

class ArborescenceDonnees extends \Pv\ZoneWeb\TableauDonnees\TableauDonnees
{
	public $RenduDebutRangee = '<ul class="RangeeDonnees">' ;
	public $RenduFinRangee = '</ul>' ;
	public function ObtientDefColsRendu()
	{
		$defCols = parent::ObtientDefColsRendu() ;
		foreach($this->DefinitionsColonnes as $i => $defCol)
		{
			$colTemp = new \Pv\ZoneWeb\TableauDonnees\Colonne() ;
			$colTemp->Visible = 0 ;
			$colTemp->NomDonnees = "VAL_NOEUD_".$i ;
			if($defCol->NomDonneesTri != '')
			{
				$colTemp->AliasDonnees = ($defCol->AliasDonneesTri == '') ? $defCol->NomDonneesTri : $defCol->AliasDonneesTri ;
			}
			else
			{
				$colTemp->AliasDonnees = $defCol->NomDonnees ;
			}
			$defCols[] = $colTemp ;
		}
		return $defCols ;
	}
	public function CalculeElementsRendu()
	{
		$this->FournisseurDonnees->RequeteSelection = "(".$this->ObtientRequeteSelection().")" ;
		// $this->AjusteDefinitionsColonnes() ;
		parent::CalculeElementsRendu() ;
		// print_r($this->FournisseurDonnees->BaseDonnees) ;
	}
	protected function ObtientRequeteSelection()
	{
		$requeteSql = '' ;
		$liaisons = '' ;
		$indiceCol = 0 ;
		$nomCols = array_keys($this->DefinitionsColonnes) ;
		foreach($nomCols as $i => $nomCol)
		{
			$col = $this->DefinitionsColonnes[$nomCol] ;
			if($indiceCol > 0)
			{
				$liaisons .= ' left join ' ;
			}
			$liaisons .= $col->RequeteSelection.' NOEUD_'.$indiceCol ;
			if($indiceCol > 0)
			{
				$exprLiaison = $col->ExpressionLiaison ;
				$exprLiaison = str_replace('<noeud_parent>', 'NOEUD_'.($indiceCol - 1), $exprLiaison) ;
				$exprLiaison = str_replace('<noeud_en_cours>', 'NOEUD_'.$indiceCol, $exprLiaison) ;
				$liaisons .= ' on '.$exprLiaison ;
			}
			$indiceCol++ ;
		}
		$requeteSql = 'select * from '.$liaisons ;
		return $requeteSql ;
	}
	protected function RenduEnteteRangeeDonnees()
	{
		return "" ;
	}
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		$nomCols = array_keys($this->DefinitionsColonnes) ;
		$ctn .= $this->RenduEnteteRangeeDonnees() ;
		$ctn .= $this->RenduDebutRangee ;
		foreach($this->ElementsEnCours as $i => $ligne)
		{
			$attrsLign = array_keys($ligne) ;
			foreach($nomCols as $j => $nomCol)
			{
				$ligne["VALEUR_ACTUELLE"] = $ligne[$attrsLign[$j]] ;
				$defCol = & $this->DefinitionsColonnes[$nomCol] ;
				if($ligne["VAL_NOEUD_".$j] != $defCol->ValeurEnCours)
				{
					if($i > 0)
					{
						$ctn .= $defCol->RenduComposantFin($this, $i, $j, $ligne) ;
					}
					$ctn .= $defCol->RenduComposantDebut($this, $i, $j, $ligne) ;
				}
				else
				{
					$ctn .= $defCol->RenduComposantVide($this, $i, $j, $ligne) ;
				}
				$defCol->ValeurEnCours = $ligne["VAL_NOEUD_".$j] ;
			}
		}
		if(count($this->ElementsEnCours) > 0)
		{
			foreach($nomCols as $j => $nomCol)
			{
				$ctn .= $defCol->RenduComposantFin($this, $i, $j, $ligne) ;
			}
		}
		$ctn .= $this->RenduFinRangee ;
		return $ctn ;
	}
	protected function RenduValeurColonne($nomCol, $j, $ligne)
	{
		$colonne = $this->DefinitionsColonnes[$nomCol] ;
		$ctn = '' ;
		if($colonne->ValeurEnCours === false || $valeurEnCours != $colonne->ValeurEnCours)
		{
			$ctn .= '<li>' ;
			$ctn .= htmlentities($valeurEnCours) ;
			$ctn .= '</li>' ;
		}
		return $ctn ;

	}
}