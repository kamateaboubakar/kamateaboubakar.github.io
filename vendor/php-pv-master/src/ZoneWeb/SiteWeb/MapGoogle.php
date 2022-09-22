<?php

namespace Pv\ZoneWeb\SiteWeb ;

class MapGoogle extends \Pv\ZoneWeb\SiteWeb\VisionneuseSiteWeb
{
	public $Zoom = 14 ;
	public $NomSite = '' ; 
	public $NomRue = '' ;
	public $NomVille = '' ;
	public $Largeur = '600px' ;
	public $Hauteur = '500px' ;
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'">'.PHP_EOL ;
		$ctn .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><div style="overflow:hidden;height:'.$this->Hauteur.';width:'.$this->Largeur.';"><div id="gmap_canvas" style="height:'.$this->Hauteur.';width:'.$this->Largeur.';"></div><style>#gmap_canvas img{max-width:none!important;background:none!important}</style><a class="google-map-code" href="http://www.mapsembed.com/goertz-gutschein/" id="get-map-data">http://www.mapsembed.com/goertz-gutschein/</a></div><script type="text/javascript"> function init_map(){var myOptions = {zoom:'.intval($this->Zoom).',center:new google.maps.LatLng(5.380144700000001,-3.989596699999993),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(5.380144700000001, -3.989596699999993)});infowindow = new google.maps.InfoWindow({content:'.svc_json_encode('<b>'.$this->NomSite.'</b><br/>'.$this->NomRue.'</br/>'.$this->NomVille.'</br/>').' });google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, \'load\', init_map);</script>'.PHP_EOL ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}