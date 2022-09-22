<?php

namespace Pv\ZoneWeb\BarreMenu ;

class OngletsBootstrap extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public $NomClasseCSSMenuRacine = "MenuRacine" ;
	public $NomClasseCSSNavs = "nav nav-pills" ;
	public $InclureRenduMiniature = 0 ;
	public $CentrerMenu = 1 ;
	public $ClasseTailleFa = "fa-4x" ;
	protected function RenduDefinitionsMenuRacine()
	{
		$ctn = '' ;
		if($this->CentrerMenu == 1)
		{
		$ctn .= '<style type="text/css">
#'.$this->IDInstanceCalc.' .nav > li {
float:none;
display:inline-block;
zoom:1;
}
#'.$this->IDInstanceCalc.' .nav {
text-align:center;
}
</style>' ;
		}
		$ctn .= $this->RenduDefinitionsMenu($this->MenuRacine) ;
		return $ctn ;
	}
	protected function RenduMenu($menu)
	{
		$ctn = '' ;
		$this->TotalColonnes = 0 ;
		if(! $menu->EstVisible || ! $menu->EstMenuRacine())
		{
			return '' ;
		}
		$menu->ComposantSupport = $this ;
		$menus = $menu->SousMenusAffichables() ;
		if(count($menus))
		{
			$ctn .= '<div id="'.$this->IDInstanceCalc.'" class="'.$this->NomClasseCSSMenuRacine.'">'.PHP_EOL ;
			$ctn .= '<ul class="'.$this->NomClasseCSSNavs.'">'.PHP_EOL ;
			$nomSousMenus = array_keys($menus) ;
			$totalMenus = 0 ;
			foreach($nomSousMenus as $i => $nomSousMenu)
			{
				$sousMenu = $menus[$nomSousMenu] ;
				$attr = '' ;
				$ctn .= '<li'.(($sousMenu->EstSelectionne) ? ' class="active"' : '').'>'.PHP_EOL ;
				$ctn .= $this->RenduSousMenu($sousMenu).PHP_EOL ;
				$ctn .= '</li>'.PHP_EOL ;
			}
			$ctn .= '</ul>' ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduSousMenu(& $sousMenu)
	{
		$ctn = '' ;
		$ctn .= $this->RenduTagOuvrLien($sousMenu) ;
		$ctn .= '<div>'.$this->RenduFaMenu($sousMenu).'</div>' ;
		$ctn .= '<div>'.$this->RenduTitreMenu($sousMenu).'</div>' ;
		$ctn .= $this->RenduTagFermLien($sousMenu) ;
		return $ctn ;
	}
}