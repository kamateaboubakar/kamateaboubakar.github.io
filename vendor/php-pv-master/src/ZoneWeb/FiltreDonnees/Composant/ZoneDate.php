<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneDate extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEntree
{
	public $TypeEditeur = "input_date_html" ;
	public $TypeElementFormulaire = "date" ;
	public $DateMin ;
	public $DateMax ;
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
		if($this->DateMin != '')
		{
			$ctn .= ' min="'.htmlspecialchars($this->DateMin).'"' ;
		}
		if($this->DateMax != '')
		{
			$ctn .= ' max="'.htmlspecialchars($this->DateMax).'"' ;
		}
		$valeurEnc = ($this->Valeur != "") ? htmlspecialchars($this->Valeur) : "" ;
		$ctn .= ' value="'.$valeurEnc.'"' ;
		$ctn .= ' />' ;
		return $ctn ;
	}
}