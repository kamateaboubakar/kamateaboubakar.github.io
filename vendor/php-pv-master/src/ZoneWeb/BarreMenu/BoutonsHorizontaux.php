<?php

namespace Pv\ZoneWeb\BarreMenu ;

class BoutonsHorizontaux extends \Pv\ZoneWeb\BarreMenu\ListeHorizontal
{
	public $LargeurSousMenu = "" ;
	protected function RenduSousMenu(& $sousMenu)
	{
		$ctn = '' ;
		$ctn .= $this->RenduTagOuvrLien($sousMenu) ;
		$ctn .= '<button type="button" onclick="javascript:window.location = '.htmlentities(svc_json_encode($sousMenu->ObtientUrl())).'"
		>'.$this->RenduTitreMenu($sousMenu).'</button>' ;
		$ctn .= $this->RenduTagFermLien($sousMenu) ;
		return $ctn ;
	}
}