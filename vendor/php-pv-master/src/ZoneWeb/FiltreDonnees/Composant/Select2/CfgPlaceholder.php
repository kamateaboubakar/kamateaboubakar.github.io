<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant\Select2 ;

use \Pv\ZoneWeb\FiltreDonnees\Composant ;

class TagsSelect2 extends \Pv\ZoneWeb\FiltreDonnees\Composant\Select2
{
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$valeurs = explode(";", $this->Valeur) ;
		$lignes = $this->FournisseurDonnees->RechsExactesElements($this->FiltresSelection, $this->NomColonneValeur, $valeurs) ;
		$ctn .= '<select name="'.htmlspecialchars($this->NomElementHtml).'[]" id="'.$this->IDInstanceCalc.'"'.$this->RenduAttrStyleCSS().' multiple>'.PHP_EOL ;
		foreach($lignes as $i => $ligne)
		{
			$ctn .= '<option value="'.htmlspecialchars($ligne[$this->NomColonneValeur]).'" selected>'.htmlentities($ligne[$this->NomColonneLibelle]).'</option>'.PHP_EOL ;
		}
		/*
		*/
		$ctn .= '</select>' ;
		return $ctn ;
	}
	protected function InitFonctsInst()
	{
		parent::InitFonctsInst() ;
		$this->FonctsInst[] = new FonctInstJQuery("createTag", array("params"), "if (params.term.indexOf('@') === -1) {
return null;
}
return {
id: params.term,
text: params.term
}") ;
	}
	protected function CreeCfgInst()
	{
		$cfg = new Cfg() ;
		$cfg->tags = true ;
		$cfg->tokenSeparators = array(",", ";", " ") ;
		return $cfg ;
	}
}
