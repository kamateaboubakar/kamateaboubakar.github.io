<?php

namespace Pv\ZoneWeb\BarreMenu ;

class BlocHorizontal extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public $NomClasseCSSMenuRacine = "MenuRacine menu_horiz" ;
	public $NomClasseCSSCellSelect = "" ;
	public $InclureRenduMiniature = 1 ;
	public $SeparateurMenu = "" ;
	public $InclureSeparateurMenu = 0 ;
	public $CentrerSousMenu = 1 ;
	public $LargeurSousMenu = "75" ;
	public $CentrerMenu = 1 ;
	public $MaxColonnes = 8 ;
	public $TotalColonnes = 0 ;
	protected function RenduEnteteTabl()
	{
		$ctn = '' ;
		$ctn .= '<table' ;
		if($this->CentrerMenu)
		{
			$ctn .= ' align="center"' ;
		}
		$ctn .= ' cellspacing="0" cellpadding="4"' ;
		$ctn .= '>'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduPiedTabl()
	{
		$ctn = '' ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '</table>'.PHP_EOL ;
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
			$nomSousMenus = array_keys($menus) ;
			$totalMenus = 0 ; 
			foreach($nomSousMenus as $i => $nomSousMenu)
			{
				$sousMenu = $menus[$nomSousMenu] ;
				if(! $sousMenu->EstVisible)
				{
					if($i == count($menu->SousMenus) - 1)
					{
						$ctn .= $this->RenduPiedTabl() ;
					}
					continue ;
				}
				if($totalMenus % $this->MaxColonnes == 0)
				{
					if($totalMenus > 0)
					{
						$ctn .= $this->RenduPiedTabl() ;
					}
					$ctn .= $this->RenduEnteteTabl() ;
					$totalMenus = 0 ;
				}
				if($totalMenus > 0 && $this->InclureSeparateurMenu && $this->SeparateurMenu != '')
				{
					$ctn .= '<td>'.$this->SeparateurMenu.'</td>' ;
				}
				$attr = '' ;
				if($this->CentrerSousMenu)
					$attr .= ' align="center"' ;
				if($this->LargeurSousMenu > "")
					$attr .= ' width="'.$this->LargeurSousMenu.'"' ;
				if($sousMenu->EstSelectionne)
					$attr .= ' class="'.$this->NomClasseCSSCellSelect.'"' ;
				$ctn .= '<td'.$attr.' valign="bottom">'.$this->RenduSousMenu($sousMenu).'</td>'.PHP_EOL ;
				$totalMenus++ ;
				if($totalMenus % $this->MaxColonnes == $this->MaxColonnes || $i == count($menus) - 1)
				{
					$ctn .= $this->RenduPiedTabl() ;
				}
			}
			$ctn .= '</div>' ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduSousMenu(& $sousMenu)
	{
		$ctn = '' ;
		$ctn .= $this->RenduTagOuvrLien($sousMenu) ;
		$ctn .= '<div>'.$this->RenduMiniatureMenu($sousMenu).'</div>' ;
		$ctn .= '<div>'.$this->RenduTitreMenu($sousMenu).'</div>' ;
		$ctn .= $this->RenduTagFermLien($sousMenu) ;
		return $ctn ;
	}
}