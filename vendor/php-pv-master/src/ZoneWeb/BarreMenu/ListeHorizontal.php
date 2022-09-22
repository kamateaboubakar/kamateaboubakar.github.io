<?php

namespace Pv\ZoneWeb\BarreMenu ;

class ListeHorizontal extends \Pv\ZoneWeb\BarreMenu\BlocHorizontal
{
	public $LargeurSousMenu = "" ;
	protected function RenduSousMenu(& $sousMenu)
	{
		$ctn = '' ;
		$ctn .= $this->RenduTagOuvrLien($sousMenu) ;
		$ctn .= '<div>'.$this->RenduTitreMenu($sousMenu).'</div>' ;
		$ctn .= $this->RenduTagFermLien($sousMenu) ;
		return $ctn ;
	}
}