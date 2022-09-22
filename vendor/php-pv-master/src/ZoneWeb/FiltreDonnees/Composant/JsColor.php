<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class JsColor extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	public $CheminFichierJs = "js/jscolor.js" ;
	protected static $SourceIncluse = 0;
	protected function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminFichierJs) ;
		return $ctn ;
	}
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$valeurEnc = ($this->Valeur != "") ? htmlspecialchars($this->Valeur) : "" ;
		$ctn .= '<input type="text" maxlength="6" size="12" class="jscolor" value="'.$valeurEnc.'" />' ;
		return $ctn ;
	}
}