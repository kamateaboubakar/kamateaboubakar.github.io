<?php

namespace Pv\ZoneWeb\SiteWeb ;

class FlexPaper extends \Pv\ZoneWeb\SiteWeb\VisionneuseSiteWeb
{
	public $Config = null ;
	public static $CheminSource = "js/flexpaper_flash.js" ;
	public $CheminDocSwf = null ;
	public $Largeur = "100%" ;
	public $Hauteur = "480px" ;
	public static $SourceInclus = 0 ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Config = new \Pv\ZoneWeb\SiteWeb\CfgFlexPaper() ;
	}
	protected function RenduDispositifBrut()
	{
		$this->Config->SwfFile = $this->CheminDocSwf ;
		if(! file_exists($this->CheminDocSwf) || is_dir($this->CheminDocSwf))
		{
			return '' ;
		}
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= $this->InclutSource() ;
		$ctn .= '<a id="'.$this->IDInstanceCalc.'" style="width:'.$this->Largeur.';height:'.$this->Hauteur.';display:block"></a>' ;
		$ctn .= '<script type="text/javascript"> 
var fp'.$this->IDInstanceCalc.' = new FlexPaperViewer(	
"FlexPaperViewer",
'.svc_json_encode($this->IDInstanceCalc).',
{
config : '.svc_json_encode($this->Config).'
}
) ;
</script>' ;
		return $ctn ;	
	}
}