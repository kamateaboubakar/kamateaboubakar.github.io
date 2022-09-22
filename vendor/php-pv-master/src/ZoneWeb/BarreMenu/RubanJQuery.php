<?php

namespace Pv\ZoneWeb\BarreMenu ;

class RubanJQuery extends \Pv\ZoneWeb\BarreMenu\BarreMenuWeb
{
	public $NomClasseCSSMenuRacine = "MenuRacine jquery-ruban" ;
	public $TypeComposant = 'BarreMenuHTML' ;
	protected function RenduMenuRacine(& $menu)
	{
		$ctn = '' ;
		$ctn .= '<div' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'" class="'.$this->NomClasseCSSMenuRacine.'"' ;
		$ctn .= '>'.PHP_EOL ;
		$ctn .= '<ul>'.PHP_EOL ;
		$menus = $menu->SousMenusAffichables() ;
		foreach($menus as $n => & $sousMenu)
		{
			$ctn .= '<li><a href="#'.$sousMenu->IDInstanceCalc.'_Onglet">'.$this->RenduIconeMenu($sousMenu).$this->RenduTitreMenu($sousMenu).'</a></li>'.PHP_EOL ;
		}
		$ctn .= '</ul>'.PHP_EOL ;
		foreach($menus as $n => & $sousMenu)
		{
			$ctn .= '<div id="'.$sousMenu->IDInstanceCalc.'_Onglet">'.$this->RenduOngletMenu($sousMenu).'</div>'.PHP_EOL ;
		}
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '<script language="javascript">
if(jQuery) {
jQuery(function() {
	jQuery("#'.$this->IDInstanceCalc.'").tabs() ;
}) ;
} else {
alert("Le composant necessite JQuery pour s\'afficher correctement !!!") ;
}
</script>' ;
		return $ctn ;
	}
	protected function RenduOngletMenu(& $menu)
	{
		$ctn = '' ;
		$ctn .= '<table width="100%" class="jquery-ruban-onglet" cellspacing="0" cellpadding="2">'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<td align="left">'.PHP_EOL ;
		$ctn .= '<table>'.PHP_EOL ;
		$menus = $menu->SousMenusAffichables() ;
		foreach($menus as $n => $sousMenu)
		{
			$ctn .= '<tr>'.PHP_EOL ;
			$ctn .= '<td>'.PHP_EOL ;
			$ctn .= $this->RenduMenuRuban($sousMenu) ;
			$ctn .= '</td>'.PHP_EOL ;
			$ctn .= '</tr>'.PHP_EOL ;
		}
		$ctn .= '</table>'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '</table>' ;
		return $ctn ;
	}
	protected function RenduMenuSpecRuban(& $menu)
	{
		$ctn = '' ;
		$styleMenu = strtolower($this->ObtientValCfgSpec("style-menu-ruban-jquery", "aucun")) ;
		switch($styleMenu)
		{
			case "illustration" :
			case "miniature" :
			{
				$ctn .= $this->RenduLnIllustrMenuRuban($menu) ;
			}
			break ;
			case "texte" :
			case "textuel" :
			{
				$ctn .= $this->RenduLnTextuelMenuRuban($menu) ;
			}
			break ;
		}
		return $ctn ;
	}
	protected function RenduLnIllustrMenuRuban(& $menu)
	{
		$ctn = '' ;
		$ctn .= '<table><tr><td align="center">'.$this->RenduMiniatureMenu($menu).'</td></tr><tr><td align="center">'.$this->RenduTitreMenu($menu).'</td></tr></table>' ;
		return $ctn ;
	}
	protected function RenduLnTextuelMenuRuban(& $menu)
	{
		$ctn = '' ;
		$ctn .= '<table><tr><td align="center">'.$this->RenduIconeMenu($menu).'</td><td align="center">'.$this->RenduTitreMenu($menu).'</td></tr></table>' ;
		return $ctn ;
	}
	protected function RenduTablIconesMenuRuban(& $menu)
	{
		$ctn = '' ;
		$menus = $menu->SousMenusAffichables() ;
		$totalMenus = count($menus) ;
		$maxCols = intval($totalMenus / 2) + ((($totalMenus % 2) > 0) ? 1 : 0) ;
		$ctn .= '<table>' ;
		if($totalMenus > 0)
		{
			$ctn .= '<tr>' ;
			$i = 0 ;
			foreach($menus as $nom => $sousMenu)
			{
				if($i == $maxCols)
				{
					$ctn .= '</tr><tr>' ;
				}
				$ctn .= '<td><a href="'.$this->ObtientUrlMenu($menu).'">'.$this->RenduIconeMenu($menu).'</td>' ;
				$i++ ;
			}
			$ctn .= '</tr>' ;
			$ctn .= '<tr><td align="center" colspan="'.$maxCols.'">'.$this->RenduTitreMenu($menu).'</td></tr>' ;
		}
		else
		{
			$ctn .= '<tr><td align="center">'.$this->RenduTitreMenu($menu).'</td></tr>' ;
		}
		$ctn .= '</table>' ;
		return $ctn ;
	}
}