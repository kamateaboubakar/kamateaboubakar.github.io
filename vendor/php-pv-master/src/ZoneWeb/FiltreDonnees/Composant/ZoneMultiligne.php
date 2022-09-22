<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneMultiligne extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $TypeEditeur = "textarea_html" ;
	public $TotalLignes = 0 ;
	public $TotalColonnes = 0 ;
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$styleCSS = '' ;
		$ctn .= '<textarea name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		if($this->TotalColonnes > 0)
			$ctn .= ' cols="'.$this->TotalColonnes.'"' ;
		if($this->TotalLignes > 0)
			$ctn .= ' rows="'.$this->TotalLignes.'"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= '>' ;
		$ctn .= $this->Valeur ;
		$ctn .= '</textarea>' ;
		return $ctn ;
	}
}