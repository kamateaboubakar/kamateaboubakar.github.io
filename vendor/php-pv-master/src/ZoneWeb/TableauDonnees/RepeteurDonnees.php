<?php

namespace Pv\ZoneWeb\TableauDonnees ;

class RepeteurDonnees extends GrilleDonnees
{
	public $ContenuAvantRangeeDonnees = "" ;
	public $ContenuApresRangeeDonnees = "" ;
	protected function RenduRangeeDonnees()
	{
		$ctn = '' ;
		if($this->FiltresSoumis() || ! $this->PossedeFiltresRendus())
		{
			$this->DetecteContenuLigneModeleUse() ;
			$parametresRendu = $this->ParametresCommandeSelectionnee() ;
			if(count($this->ElementsEnCours) > 0)
			{
				if($this->ContenuAvantRangeeDonnees != "")
				{
					$ctn .= $this->ContenuAvantRangeeDonnees. PHP_EOL ;
				}
				foreach($this->ElementsEnCours as $j => $ligne)
				{
					$ligneDonnees = $ligne ;
					$ligneDonnees["POSITION"] = $j ;
					$ligneDonnees["ID_PARITE"] = ($j % 2 == 0) ? "pair" : "impair" ; ;
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
					$ctn .= _parse_pattern($this->ContenuLigneModeleUse, $ligneDonnees) ;
				}
				if($this->ContenuApresRangeeDonnees != "")
				{
					$ctn .= $this->ContenuApresRangeeDonnees. PHP_EOL ;
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
}

