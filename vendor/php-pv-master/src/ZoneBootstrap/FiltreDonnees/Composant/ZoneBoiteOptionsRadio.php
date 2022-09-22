<?php

namespace Pv\ZoneBootstrap\FiltreDonnees\Composant ;

class ZoneBoiteOptionsRadio extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsRadio
{
	protected function RenduListeElements()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= $this->RenduFoncJs() ;
		$ctn .= $this->RenduLiens() ;
		$ctn .= '<div' ;
		$ctn .= ' class="row"' ;
		$ctn .= ' name="Conteneur_'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="Conteneur_'.$this->IDInstanceCalc.'"' ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= '>'.PHP_EOL ;
		$totalLignes = 0 ;
		$indexLigne = 0 ;
		$idxCol = intval(12 / $this->MaxColonnesParLigne) ;
		$this->OuvreRequeteSupport() ;
		while($ligne = $this->LitRequeteSupport())
		{
			$ctn .= '<div class="col-'.$idxCol.'"' ;
			$ctn .= '>'.PHP_EOL ;
			$valeur = $this->ExtraitValeur($ligne, $this->NomColonneValeur) ;
			$libelle = $this->ExtraitValeur($ligne, $this->NomColonneLibelle) ;
			$ctn .= $this->RenduElement($valeur, $libelle, $ligne, $this->RequeteSupport->Position).PHP_EOL ;
			$ctn .= '</div>'.PHP_EOL ;
			$indexLigne++ ;
		}
		$this->FermeRequeteSupport() ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}