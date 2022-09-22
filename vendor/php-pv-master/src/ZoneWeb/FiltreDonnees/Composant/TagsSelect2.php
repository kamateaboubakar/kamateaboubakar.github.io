<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

use \Pv\ZoneWeb\FiltreDonnees\Composant\Select2 ;

class TagsSelect2 extends Select2
{
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$valeurs = explode(";", $this->Valeur) ;
		$lignes = $this->FournisseurDonnees->RechsExactesElements($this->FiltresSelection, $this->NomColonneValeur, $valeurs) ;
		$ctn .= '<select name="'.htmlspecialchars($this->NomElementHtml).'[]" id="'.$this->IDInstanceCalc.'"'.$this->RenduAttrStyleCSS().' multiple>'.PHP_EOL ;
		foreach($lignes as $i => $ligne)
		{
			$valEnc = ($ligne[$this->NomColonneValeur] != "") ? htmlspecialchars($ligne[$this->NomColonneValeur]) : "" ;
			$libEnc = ($ligne[$this->NomColonneLibelle] != "") ? htmlentities($ligne[$this->NomColonneLibelle]) : "" ;
			$ctn .= '<option value="'.$valEnc.'" selected>'.$libEnc.'</option>'.PHP_EOL ;
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
		$cfg = new Select2\Cfg() ;
		$cfg->tags = true ;
		$cfg->tokenSeparators = array(",", ";", " ") ;
		return $cfg ;
	}
}
