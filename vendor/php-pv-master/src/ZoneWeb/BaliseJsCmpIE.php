<?php

namespace Pv\ZoneWeb ;

class BaliseJsCmpIE extends \Pv\ZoneWeb\BaliseJs
{
	public $VersionMin = 9 ;
	protected function RenduDispositifBrut()
	{
		$ctn = '<!--[if lt IE '.intval($this->VersionMin).']>'.PHP_EOL ;
		$ctn .= parent::RenduDispositifBrut().PHP_EOL ;
		$ctn .= '<![endif]-->' ;
		return $ctn ;
	}
}