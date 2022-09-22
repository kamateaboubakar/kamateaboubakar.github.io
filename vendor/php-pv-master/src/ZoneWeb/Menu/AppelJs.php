<?php

namespace Pv\ZoneWeb\Menu ;

class AppelJs extends \Pv\ZoneWeb\Menu\MenuWeb
{
	public $NomFonct ;
	public $Args = array() ;
	protected function ChaineArgs()
	{
		$ctn = '' ;
		foreach($this->Args as $i => $arg)
		{
			if($i > 0)
				$ctn .= ', ' ;
			$ctn .= svc_json_encode($arg) ;
		}
		return $ctn ;
	}
	public function ObtientUrl()
	{
		return "javascript:".htmlentities($this->NomFonct."(".$this->ChaineArgs().");") ;
	}
}