<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneEntree extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $TypeEditeur = "input_text_html" ;
	public $TypeElementFormulaire = "" ;
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$styleCSS = '' ;
		$ctn .= '<input name="'.htmlspecialchars($this->NomElementHtml).'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' type="'.$this->TypeElementFormulaire.'"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$valeurEnc = ($this->Valeur != "") ? htmlspecialchars($this->Valeur) : "" ;
		$ctn .= ' value="'.$valeurEnc.'"' ;
		$ctn .= ' />' ;
		return $ctn ;
	}
}