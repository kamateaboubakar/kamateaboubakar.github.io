<?php

namespace Pv\ZoneWeb ;

class BaliseJs extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $TypeComposant = "BaliseJs" ;
	public $Definitions = "" ;
	public $Async = 0 ;
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= '<script language="javascript"'.(($this->Async == 1) ? ' async' : '').'>'.PHP_EOL ;
		$ctn .= $this->Definitions. PHP_EOL ;
		$ctn .= '</script>' ;
		return $ctn ;
	}
}