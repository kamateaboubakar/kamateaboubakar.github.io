<?php

namespace Pv\ZoneWeb\BarreMenu ;

class Cadre extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public $NomClasseCSSMenuRacine = "MenuRacine cadre_menu" ;
	public $NomClasseCSSMenuNv1 = "sous-menu-nv1" ;
	public $NomClasseCSSMenuNv2 = "sous-menu-nv2" ;
	public $AlignMenuRacine = "center" ;
	public $AlignMenuNv1 = "center" ;
	public $AlignMenuNv2 = "center" ;
	protected function RenduMenuRacine(& $menu)
	{
		$ctn = '' ;
		/*
		if(! $menu->EstAffichable())
		{
			return '' ;
		}
		*/
		$menu->ComposantSupport = $this ;
		$menus = $menu->SousMenusAffichables() ;
		if(count($menus))
		{
			$ctn .= '<table' ;
			$ctn .= ' align="'.$this->AlignMenuRacine.'"' ;
			$ctn .= ' id="'.$this->IDInstanceCalc.'" class="'.$this->NomClasseCSSMenuRacine.'"' ;
			$ctn .= '>'.PHP_EOL ;
			$ctn .= '<tr>'.PHP_EOL ;
			$nomSousMenus = array_keys($menus) ;
			foreach($nomSousMenus as $i => $nomSousMenu)
			{
				$sousMenu = $menus[$nomSousMenu] ;
				if(! $sousMenu->EstAffichable())
				{
					continue ;
				}
				$ctn .= '<td class="cadre-sous-menu">'.$this->RenduMenuNv1($sousMenu).'</td>'.PHP_EOL ;
			}
			$ctn .= '</tr>'.PHP_EOL ;
			$ctn .= '</table>'.PHP_EOL ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduMenuNv1($menu)
	{
		$ctn = '' ;
		if(! $menu->EstAffichable())
		{
			return '' ;
		}
		$menu->ComposantSupport = $this ;
		$ctn .= '<div align="'.$this->AlignMenuNv1.'" class="'.$this->NomClasseCSSMenuNv1.'">'.PHP_EOL ;
		$ctn .= $this->RenduTagOuvrLien($menu).PHP_EOL ;
		$ctn .= $this->RenduTitreMenu($menu).PHP_EOL ;
		$ctn .= $this->RenduTagFermLien($menu).PHP_EOL ;
		$ctn .= '</div>' ;
		if(count($menu->SousMenus))
		{
			$ctn .= '<table>'.PHP_EOL ;
			$ctn .= '<tr>'.PHP_EOL ;
			$nomSousMenus = array_keys($menu->SousMenus) ;
			foreach($nomSousMenus as $i => $nomSousMenu)
			{
				$sousMenu = $menu->SousMenus[$nomSousMenu] ;
				if(! $sousMenu->EstVisible)
				{
					continue ;
				}
				$ctn .= '<td>' ;
				$ctn .= $this->RenduMenuNv2($sousMenu) ;
				$ctn .= '</td>' ;
			}
			$ctn .= '</tr>'.PHP_EOL ;
			$ctn .= '</table>'.PHP_EOL ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduMenuNv2($menu)
	{
		$ctn = '' ;
		$ctn .= '<div class="'.$this->NomClasseCSSMenuNv2.'">'.PHP_EOL ;
		$ctn .= $this->RenduTagOuvrLien($menu) ;
		$ctn .= $this->RenduTitreMenu($menu) ;
		$ctn .= $this->RenduTagFermLien($menu) ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}