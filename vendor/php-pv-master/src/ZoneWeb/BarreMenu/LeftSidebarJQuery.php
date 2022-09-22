<?php

namespace Pv\ZoneWeb\BarreMenu ;

class LeftSidebarJQuery extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	protected $CheminJs = "js/slidebars.js" ;
	protected $CheminCSS = "css/slidebars.css" ;
	protected $NomClsCSSSlideBar = "sb-left" ;
	protected static $SourceIncluse = 0 ;
	// Doit etre initialisÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ avant la methode "ChargeConfig()"
	public $ComposantSupport ;
	public $LibelleLien = "Ouvrir le menu" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->ComposantSupport = new \Pv\ZoneWeb\ComposantRendu\PortionRendu() ;
	}
	public function & DeclareComposantSupport($comp)
	{
		$this->RemplaceCompSupport($comp) ;
		return $comp ;
	}
	public function & DeclareCompSupport($comp)
	{
		$this->RemplaceCompSupport($comp) ;
		return $comp ;
	}
	public function RemplaceComposantSupport(& $comp)
	{
		$this->ComposantSupport = & $comp ;
		if($this->EstPasNul($this->ScriptParent))
		{
			$this->ComposantSupport->AdopteScript('support_'.$this->NomElementScript, $this->ScriptParent) ;
		}
		if($this->EstPasNul($this->ZoneParent))
		{
			$this->ComposantSupport->AdopteZone('support_'.$this->NomElementZone, $this->ZoneParent) ;
		}
	}
	public function RemplaceCompSupport(& $comp)
	{
		$this->RemplaceComposantSupport($comp) ;
	}
	public function InsereCompSupport($comp)
	{
		$this->RemplaceComposantSupport($comp) ;
	}
	public function AdopteScript($nom, & $script)
	{
		parent::AdopteScript($nom, $script) ;
		if($this->EstPasNul($this->ComposantSupport))
		{
			$this->ComposantSupport->AdopteScript($nom.'_support', $script) ;
		}
	}
	public function AdopteZone($nom, & $zone)
	{
		parent::AdopteZone($nom, $zone) ;
		if($this->EstPasNul($this->ComposantSupport) && $this->EstNul($this->ScriptParent))
		{
			$this->ComposantSupport->AdopteZone($nom.'_support', $zone) ;
		}
	}
	protected function RenduSourceIncluse()
	{
		if($this->ObtientValeurStatique("SourceIncluse"))
		{
			return "" ;
		}
		$ctn = "" ;
		$ctn .= $this->ZoneParent->RenduLienCSS($this->CheminCSS) ;
		$ctn .= $this->ZoneParent->RenduContenuCSS('.sb-slidebar {
padding: 14px;
color: #fff;
}
html.sb-active #sb-site, .sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close {
cursor: pointer;
}
/* Fixed position examples */
#fixed-top {
position: fixed;
top: 0;
width: 100%;
height: 50px;
background-color: red;
z-index: 4;
}
#fixed-top span.sb-toggle-left {
float: left;
color: white;
padding: 10px;
}
#fixed-top span.sb-toggle-right {
float: right;
color: white;
padding: 10px;
}') ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
		$ctn .= $this->ZoneParent->RenduContenuJsInclus($this->RenduDefinitionJs()) ;
		$this->AffecteValeurStatique("SourceIncluse", 1) ;
		return $ctn ;
	}
	protected function RenduDefinitionJs()
	{
		$ctn = '' ;
		$ctn .= '(function(jQuery) {
jQuery(document).ready(function() {
jQuery.slidebars();
});
}) (jQuery);' ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduSourceIncluse() ;
		$ctn .= '<div id="Lien'.$this->IDInstanceCalc.'"><a href="javascript:;" class="sb-open-left">'.$this->LibelleLien.'</a></div>'.PHP_EOL ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'" class="sb-slidebar '.$this->NomClsCSSSlideBar.'">'.PHP_EOL ;
		if($this->EstPasNul($this->ComposantSupport))
		{
			$ctn .= $this->ComposantSupport->RenduDispositif() ;
		}
		$ctn .= '</div>' ;
		return $ctn ;
	}
}