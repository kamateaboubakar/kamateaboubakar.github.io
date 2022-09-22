<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneBoiteSelect extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteChoix
{
	public $TypeEditeur = "select_html" ;
	public function RenduJsDefinitValeur($nomVariable='valeur')
	{
		return 'if(document.getElementById("'.$this->IDInstanceCalc.'") != null) {
var noeudSelect = document.getElementById("'.$this->IDInstanceCalc.'") ;
noeudSelect.selectedIndex = 0 ;
for (var tmp=0; tmp<noeudSelect.options.length; tmp++) {
var noeudOption = noeudSelect.options[tmp] ;
if(noeudOption.value === '.$nomVariable.') {
noeudSelect.selectedIndex = tmp ;
break ;
}
}
}' ;
	}
	protected function RenduListeElements()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= $this->RenduFoncJs() ;
		$ctn .= $this->RenduLiens() ;
		$ctn .= '<select' ;
		$ctn .= ' name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= '>'.PHP_EOL ;
		if($this->InclureElementHorsLigne)
		{
			$ligne = array(
				$this->NomColonneValeur => $this->ValeurElementHorsLigne,
				$this->NomColonneLibelle => $this->LibelleElementHorsLigne,
				$this->NomColonneExtra => $this->ExtraElementHorsLigne,
			) ;
			$ctn .= $this->RenduElement($this->ValeurElementHorsLigne, $this->LibelleElementHorsLigne, $ligne, 0) ;
		}
		$this->OuvreRequeteSupport() ;
		while($ligne = $this->LitRequeteSupport())
		{
			$valeur = $this->ExtraitValeur($ligne, $this->NomColonneValeur) ;
			$libelle = $this->ExtraitValeur($ligne, $this->NomColonneLibelle) ;
			$ctn .= $this->RenduElement($valeur, $libelle, $ligne, $this->RequeteSupport->Position) ;
		}
		$this->FermeRequeteSupport() ;
		$ctn .= '</select>' ;
		return $ctn ;
	}
	protected function RenduElement($valeur, $libelle, $ligne, $position=0)
	{
		$ctn = '' ;
		$ctn .= '<option' ;
		$ctn .= ' value="'.htmlentities($valeur).'"' ;
		if($this->EstValeurSelectionnee($valeur))
		{
			$ctn .= ' selected' ;
		}
		$ctn .= '>' ;
		$ctn .= htmlentities($libelle) ;
		$ctn .= '</option>'.PHP_EOL ;
		return $ctn ;
	}
}