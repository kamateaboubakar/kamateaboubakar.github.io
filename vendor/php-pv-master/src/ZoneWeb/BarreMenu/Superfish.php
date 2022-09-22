<?php

namespace Pv\ZoneWeb\BarreMenu ;

class Superfish extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public static $SourceIncluse ;
	public static $CheminJs = "js/superfish.min.js" ;
	public static $CheminCSSPrinc = "css/superfish.css" ;
	public static $CheminCSSNavbar = "css/superfish-navbar.css" ;
	public static $CheminCSSVertical = "css/superfish-vertical.css" ;
	public $AppliquerJQueryUi = 0 ;
	public $CacherBordure = 0 ;
	protected function RenduSourceIncluse()
	{
		$ok = $this->ObtientValeurStatique("SourceIncluse") ;
		if($ok)
		{
			return '' ;
		}
		$ctn = '' ;
		$ctn .= '<script type="text/javascript" src="'.\Pv\ZoneWeb\BarreMenu\Superfish::$CheminJs.'"></script>'.PHP_EOL ;
		$ctn .= '<link rel="stylesheet" type="text/css" href="'.\Pv\ZoneWeb\BarreMenu\Superfish::$CheminCSSPrinc.'">'.PHP_EOL ;
		$ctn .= '<link rel="stylesheet" type="text/css" href="'.\Pv\ZoneWeb\BarreMenu\Superfish::$CheminCSSNavbar.'">'.PHP_EOL ;
		$ctn .= '<link rel="stylesheet" type="text/css" href="'.\Pv\ZoneWeb\BarreMenu\Superfish::$CheminCSSVertical.'">'.PHP_EOL ;
		$this->AffecteValeurStatique("SourceIncluse", 1) ;
		return $ctn ;
	}
	protected function RenduHabillageJQueryUi()
	{
		$ctn = '' ;
		$ctn .= '<script type="text/javascript">'.PHP_EOL ;
		$ctn .= 'jQuery(function () {
var currentMenu = jQuery("#'.$this->IDInstanceCalc.'") ;
currentMenu.find("li")
.addClass("ui-state-default")
currentMenu.find("li a").hover(
function () { jQuery(this).addClass("ui-state-active"); },
function () { jQuery(this).removeClass("ui-state-active"); }
);'.PHP_EOL ;
		if($this->CacherBordure)
		{
			$ctn .= 'currentMenu.find("li").css("border", "0px") ;'.PHP_EOL ;
		}
		$ctn .= '}) ;'.PHP_EOL ;
		$ctn .= '</script>' ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduSourceIncluse() ;
		$ctn .= parent::RenduDispositifBrut() ;
		if($this->AppliquerJQueryUi)
		{
			$ctn .= PHP_EOL .$this->RenduHabillageJQueryUi() ;
		}
		return $ctn ;
	}
}