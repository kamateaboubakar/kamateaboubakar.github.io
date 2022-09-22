<?php

namespace Pv\ZoneWeb\SliderJs ;

class JqMenuCamera extends \Pv\ZoneWeb\SliderJs\JQueryCamera
{
	public $NomColLibMenu = "" ;
	public $CouleurArrMenu = "red" ;
	public $CouleurTxtMenu = "white" ;
	public $TailleTxtMenu = "16px" ;
	public $Largeur = "100%" ;
	public $PourcentLargeurMenu = "30" ;
	protected function CtnJsInstall()
	{
		$ctn = 'var cfgInit = '.svc_json_encode($this->CfgInit).' ;
var menu'.$this->IDInstanceCalc.' = jQuery("#Conteneur_'.$this->IDInstanceCalc.'") ;
var slide'.$this->IDInstanceCalc.' = jQuery("#'.$this->IDInstanceCalc.'") ;
cfgInit.onEndTransition = function(){
var ind = slide'.$this->IDInstanceCalc.'.find(".camera_target .cameraSlide.cameranext").index();
menu'.$this->IDInstanceCalc.'.find(".menu_item").hide().each(function(index) {
if(index == ind - 1)  {
	jQuery(this).show() ;
}
}) ;
} ;
slide'.$this->IDInstanceCalc.'.camera(cfgInit) ;' ;
		return $ctn ;
	}
	protected function RenduMenu()
	{
		$ctn = '' ;
		$ctn .= '<div class="menu" style="background:'.$this->CouleurArrMenu.'">'.PHP_EOL ;
		foreach($this->ElementsEnCours as $i => $elem)
		{
			$ctnMenu = ($this->NomColLibMenu != "" && isset($elem[$this->NomColLibMenu])) ? $elem[$this->NomColLibMenu] : "Slide ".($i + 1) ;
			$ctn .= '<div class="menu_item" style="display:none; font-size:'.$this->TailleTxtMenu.'; font-weight:bold; color:'.$this->CouleurTxtMenu.'">'.PHP_EOL ;
			$ctn .= htmlentities($ctnMenu).PHP_EOL ;
			$ctn .= '</div>'.PHP_EOL ;
		}
		$ctn .= '</div>' ;
		return $ctn ;
	}
	protected function RenduDispositifBrutSpec()
	{
		$ctn = '' ;
		$ctnCamera = parent::RenduDispositifBrutSpec() ;
		$ctn .= '<table id="Conteneur_'.$this->IDInstanceCalc.'" width="'.$this->Largeur.'" cellspacing="0" cellpadding="0">
<tr>
<td width="'.$this->LargeurMenu.'" bgcolor="'.$this->CouleurArrMenu.'" style="padding:8px">'.PHP_EOL ;
		$ctn .= $this->RenduMenu().PHP_EOL ;
		$ctn .= '</td>
<td width="'.(100 - $this->PourcentLargeurMenu).'%">'.PHP_EOL ;
		$ctn .= $ctnCamera.PHP_EOL ;
		$ctn .= '</td>
</tr>
</table>' ;
		return $ctn ;
	}
}