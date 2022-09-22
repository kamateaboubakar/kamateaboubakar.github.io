<?php

namespace Pv\ZoneWeb ;

class BaliseCSS extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $TypeComposant = "BaliseCSS" ;
	public $Definitions = "" ;
	public $Media = "" ;
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= '<style type="text/css"'.(($this->Media != '') ? ' media="'.$this->Media.'"' : '').'>'.PHP_EOL ;
		$ctn .= $this->Definitions. PHP_EOL ;
		$ctn .= '</style>' ;
		return $ctn ;
	}
}