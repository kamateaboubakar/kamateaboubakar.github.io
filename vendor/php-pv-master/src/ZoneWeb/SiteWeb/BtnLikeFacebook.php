<?php

namespace Pv\ZoneWeb\SiteWeb ;

class \Pv\ZoneWeb\SiteWeb\BtnLikeFacebook extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $Url ;
	public $DataLayout = "standard" ;
	public $DataShowFaces = "true" ;
	public $DataShare = "true" ;
	public $DataAction = "like" ;
	public static $SdkInclus = 0 ;
	protected function RenduSdkInclus()
	{
		if($this->ObtientValStatique("SdkInclus") == 1)
		{
			return "" ;
		}
		$this->AffecteValStatique("SdkInclus", 1) ;
		$ctn = '<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3";
fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>' ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		if($this->Url == "")
		{
			return $ctn ;
		}
		$ctn .= $this->RenduSdkInclus() ;
		$ctn .= '<div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="'.htmlentities($this->DataLayout).'" data-action="'.htmlentities($this->DataAction).'" data-show-faces="'.htmlentities($this->DataShowFaces).'" data-share="'.htmlentities($this->DataShare).'"></div>' ;
		return $ctn ;
	}
}