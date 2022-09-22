<?php

namespace Pv\ZoneWeb\BarreMenu ;

class BlocVertical extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public $InclureRenduIcone = 0 ;
	public $InclureRenduBulle = 1 ;
	public $SymboleBulle = "- " ;
	protected function RenduMenuRacine(& $menu)
	{
		return $this->RenduMenu($menu) ;
	}
	protected function RenduMenu($menu)
	{
		$ctn = '' ;
		if(! $menu->EstAffichable())
		{
			return '' ;
		}
		$menu->ComposantSupport = $this ;
		if(! $menu->EstMenuRacine())
		{
			$ctn .= '<div>'.PHP_EOL ;
			$ctn .= $this->RenduBulleMenu($menu) ;
			$ctn .= $this->RenduTagOuvrLien($menu) ;
			$ctn .= $this->RenduIconeMenu($menu) ;
			$ctn .= $this->RenduTitreMenu($menu) ;
			$ctn .= $this->RenduTagFermLien($menu).PHP_EOL ;
		}
		$menus = $menu->SousMenusAffichables() ;
		if(count($menus))
		{
			$ctn .= '<div' ;
			if($menu->EstMenuRacine())
			{
				$ctn .= ' id="'.$this->IDInstanceCalc.'" class="'.$this->NomClasseCSSMenuRacine.'"' ;
			}
			$ctn .= '>'.PHP_EOL ;
			$nomSousMenus = array_keys($menus) ;
			foreach($nomSousMenus as $i => $nomSousMenu)
			{
				$sousMenu = $menus[$nomSousMenu] ;
				$ctn .= $this->RenduMenu($sousMenu).PHP_EOL ;
			}
			$ctn .= '</div>'.PHP_EOL ;
		}
		if(! $menu->EstMenuRacine())
		{
			$ctn .= '</div>' ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduBulleMenu($menu)
	{
		if($this->InclureRenduBulle == 0)
			return "" ;
		return $this->SymboleBulle ;
	}
}