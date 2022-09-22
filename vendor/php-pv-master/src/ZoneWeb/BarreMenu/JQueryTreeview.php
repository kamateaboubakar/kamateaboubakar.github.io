<?php

namespace Pv\ZoneWeb\BarreMenu ;

class JQueryTreeview extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	protected static $SourceIncluse = 0 ;
	public $Config ;
	public $CheminCSS = "css/jquery.treeview.css" ;
	public $CheminJsJQueryCookie = "js/jquery.cookie.js" ;
	public $UtiliserJQueryCookie = 1 ;
	public $CheminJs = "js/jquery.treeview.js" ;
	public $AppliquerJQueryUi = 1 ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Config = new \Pv\ZoneWeb\BarreMenu\CfgJQueryTreeview() ;
	}
	protected function RenduSourceIncluse()
	{
		$sourceInc = $this->ObtientValeurStatique("SourceIncluse") ;
		if($sourceInc)
		{
			return "" ;
		}
		$ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienCSS($this->CheminCSS) ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
		if($this->UtiliserJQueryCookie)
		{
			$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJsJQueryCookie) ;
		}
		$this->AffecteValeurStatique("SourceIncluse", 1) ;
		return $ctn ;
	}
	protected function RenduDefinitionJs()
	{
		$ctn = '' ;
		$ctn .= 'jQuery(function() {'.PHP_EOL ;
		$ctn .= 'var selection = jQuery("#'.$this->IDInstanceCalc.'") ;'.PHP_EOL ;
		if($this->AppliquerJQueryUi && $this->ZoneParent->InclureJQueryUi)
		{
			$ctn .= 'selection.addClass("ui-widget ui-state-default") ;'.PHP_EOL ;
			// $ctn .= 'selection.find("ul").css("background", "none") ;'.PHP_EOL ;
		}
		$ctn .= 'selection.treeview('.svc_json_encode($this->Config).') ;'.PHP_EOL ;
		$ctn .= '}) ;' ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduSourceIncluse() ;
		$ctn .= parent::RenduDispositifBrut() ;
		$ctn .= $this->ZoneParent->RenduContenuJsInclus($this->RenduDefinitionJs()) ;
		return $ctn ;
	}

}