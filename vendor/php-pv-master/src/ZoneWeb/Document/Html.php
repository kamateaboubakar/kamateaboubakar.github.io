<?php

namespace Pv\ZoneWeb\Document ;

class Html extends \Pv\ZoneWeb\Document\Document
{
	public $AttrsBody = array() ;
	public function RenduEntete(& $zone)
	{
		$ctn = '' ;
		$ctn .= '<!doctype html>'.PHP_EOL ;
		$ctn .= '<html lang="'.$zone->LangueDocument.'">'.PHP_EOL ;
		$ctn .= '<head>'.PHP_EOL ;
		$ctn .= $zone->RenduLienBase() ;
		$ctn .= $zone->RenduLienFavicon() ;
		$ctn .= $zone->RenduMetasDocument() ;
		$ctn .= '<title>'.$zone->ObtientTitreDocument().'</title>'.PHP_EOL ;
		if($zone->InclureCtnJsEntete)
		{
			$ctn .= $this->RenduDefsJS($zone) ;
		}
		$ctn .= $this->RenduDefsCSS($zone) ;
		$ctn .= $zone->RenduExtraHead ;
		$ctn .= '</head>'.PHP_EOL ;
		$ctnAttrsBody = '' ;
		foreach($this->AttrsBody as $n => $v)
		{
			$ctnAttrsBody .= ' '.$n.'="'.htmlspecialchars(html_entity_decode($v)).'"' ;
		}
		$ctn .= '<body'.$ctnAttrsBody.'>' ;
		return $ctn ;
	}
	public function RenduPied(& $zone)
	{
		$ctn = '' ;
		if($zone->InclureCtnJsEntete == 0)
		{
			$ctn .= $this->RenduDefsJS($zone).PHP_EOL ;
		}
		$ctn .= $zone->RenduCtnJsPied($zone) ;
		$ctn .= '</body>'.PHP_EOL ;
		$ctn .= '</html>' ;
		return $ctn ;
	}
}
