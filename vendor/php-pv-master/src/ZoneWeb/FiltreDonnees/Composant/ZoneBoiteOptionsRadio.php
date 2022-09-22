<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneBoiteOptionsRadio extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteChoix
{
	public $MaxColonnesParLigne = 2 ;
	public $AlignLibelle = "right" ;
	public $LargeurOption = "" ;
	public $CocherAutoPremiereOption = 1 ;
	protected $CalculerValeurParJs = 1 ;
	public $SeparateurLibelleOption = "&nbsp;&nbsp;" ;
	protected function RenduListeElements()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= $this->RenduFoncJs() ;
		$ctn .= $this->RenduLiens() ;
		$ctn .= '<table' ;
		$ctn .= ' name="Conteneur_'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="Conteneur_'.$this->IDInstanceCalc.'"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= '>'.PHP_EOL ;
		$totalLignes = 0 ;
		$indexLigne = 0 ;
		$pourcentageColonne = intval(100 / $this->MaxColonnesParLigne) ;
		$this->OuvreRequeteSupport() ;
		$premiereValeur = "" ;
		while($ligne = $this->LitRequeteSupport())
		{
			if($indexLigne % $this->MaxColonnesParLigne == 0)
			{
				$ctn .= '<tr>'.PHP_EOL ;
			}
			$ctn .= '<td' ;
			$ctn .= ' width="'.$pourcentageColonne.'%"' ;
			$ctn .= ' valign="top"' ;
			$ctn .= '>'.PHP_EOL ;
			$valeur = $this->ExtraitValeur($ligne, $this->NomColonneValeur) ;
			$libelle = $this->ExtraitValeur($ligne, $this->NomColonneLibelle) ;
			$ctn .= $this->RenduElement($valeur, $libelle, $ligne, $this->RequeteSupport->Position).PHP_EOL ;
			$ctn .= '</td>'.PHP_EOL ;
			if($indexLigne % $this->MaxColonnesParLigne == $this->MaxColonnesParLigne - 1)
			{
				$ctn .= '</tr>'.PHP_EOL ;
			}
			if($indexLigne == 1)
			{
				$premiereValeur = $valeur ;
			}
			$indexLigne++ ;
		}
		if($indexLigne % $this->MaxColonnesParLigne != 0)
		{
			$colonnesFusionnees = $this->MaxColonnesParLigne - ($indexLigne % $this->MaxColonnesParLigne) ;
			$ctn .= '<td colspan="'.$colonnesFusionnees.'"></td>'.PHP_EOL ;
			$ctn .= '</tr>'.PHP_EOL ;
		}
		$this->FermeRequeteSupport() ;
		$ctn .= '</table>' ;
		if($this->CalculerValeurParJs == 1)
		{
			$valeur = $this->Valeur ;
			if($valeur == "" && $this->CocherAutoPremiereOption)
			{
				$valeur = $premiereValeur ;
			}
			$ctn .= PHP_EOL .'<input type="hidden" name="'.$this->NomElementHtml.'" id="'.$this->IDInstanceCalc.'" value="'.htmlspecialchars($valeur).'" />' ;
		}
		// print_r($this->FournisseurDonnees->BaseDonnees) ;
		return $ctn ;
	}
	protected function RenduElement($valeur, $libelle, $ligne, $position=0)
	{
		$ctn = '' ;
		switch($this->AlignLibelle)
		{
			case "right" :
			case "droite" :
			{
				$ctn = $this->RenduOptionElement($valeur, $libelle, $ligne, $position).$this->SeparateurLibelleOption.$this->RenduLibelleElement($valeur, $libelle, $ligne, $position) ;
			}
			break ;
			case "left" :
			case "gauche" :
			{
				$ctn = $this->RenduLibelleElement($valeur, $libelle, $ligne, $position).$this->SeparateurLibelleOption.$this->RenduOptionElement($valeur, $libelle, $ligne, $position) ;
			}
			break ;
			case "hidden" :
			case "cache" :
			{
				$ctn = $this->RenduOptionElement($valeur, $libelle, $ligne, $position) ;
			}
			break ;
		}
		return $ctn ;
	}
	protected function RenduOptionElement($valeur, $libelle, $ligne, $position=0)
	{
		$forcerSelection = 0 ;
		if($position == 1 && $this->Valeur == "" && $this->CocherAutoPremiereOption)
		{
			$forcerSelection = 1 ;
		}
		$ctn = '' ;
		$nomElementHtml = $this->NomElementHtml ;
		$ctn .= '<input type="radio" id="'.$this->IDInstanceCalc.'_'.$position.'"' ;
		$ctn .= ' value="'.htmlentities($valeur).'"' ;
		if($this->EstValeurSelectionnee($valeur) || $forcerSelection)
		{
			$ctn .= ' checked' ;
		}
		$ctn .= ' onclick="document.getElementById(&quot;'.$this->IDInstanceCalc.'&quot;).value = this.value;"' ;
		$ctn .= ' />' ;
		return $ctn ;
	}
	protected function RenduLibelleElement($valeur, $libelle, $ligne, $position=0)
	{
		$ctn = '<label for="'.$this->IDInstanceCalc.'_'.$position.'">'.htmlentities($libelle).'</label>' ;
		return $ctn ;
	}
}