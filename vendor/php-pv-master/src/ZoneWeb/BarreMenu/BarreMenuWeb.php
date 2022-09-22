<?php

namespace Pv\ZoneWeb\BarreMenu ;

class BarreMenuWeb extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $TypeComposant = 'BarreMenuHTML' ;
	public $NomClasseCSSSelect = 'Selectionne' ;
	public $MenuRacine = null ;
	public $InclureSelection = 1 ;
	public $InclureRenduFa = 1 ;
	public $InclureRenduIcone = 1 ;
	public $InclureRenduTitre = 1 ;
	public $InclureRenduMiniature = 1 ;
	public $ClasseFaParDefaut = "fa-menu" ;
	public $ClasseTailleFa = "" ;
	public $LargeurIcone = 21 ;
	public $HauteurMiniature = 42 ;
	public $NomClasseMenuRacine = "\Pv\ZoneWeb\Menu\Racine" ;
	public $NomClasseCSSMenuRacine = "MenuRacine sf-menu" ;
	public $CheminIconeParDefaut = "images/icones/menu-defaut.png" ;
	public $CheminMiniatureParDefaut = "images/icones/menu-defaut.png" ;
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeMenuRacine() ;
	}
	public function AdopteScript($nom, & $script)
	{
		parent::AdopteScript($nom, $script) ;
	}
	public function AdopteZone($nom, & $zone)
	{
		parent::AdopteZone($nom, $zone) ;
		$this->InitMenuRacine() ;
		if($this->EstPasNul($this->MenuRacine))
		{
			$this->MenuRacine->AdopteZone($nom."MenuRacine", $this->ZoneParent) ;
		}
	}
	protected function InitMenuRacine()
	{
		// echo "kkkkk " ;
		if(! $this->EstNul($this->MenuRacine))
			return ;
		$nomClasseMenuRacine = $this->NomClasseMenuRacine ;
		if(class_exists($nomClasseMenuRacine))
		{
			$this->MenuRacine = new $nomClasseMenuRacine() ;
			$this->MenuRacine->BarreMenu = & $this ;
		}
	}
	protected function ChargeMenuRacine()
	{
	}
	protected function RenduDispositifBrut()
	{
		if($this->Visible == 0)
		{
			return '' ;
		}
		if($this->InclureSelection)
		{
			$this->MenuRacine->DetecteStatutSelection() ;
		}
		$ctn = '' ;
		$ctn .= $this->RenduMenuRacine($this->MenuRacine).PHP_EOL ;
		$ctn .= $this->RenduDefinitionsMenuRacine($this->MenuRacine) ;
		return $ctn ;
	}
	protected function RenduDefinitionsMenu(& $menu)
	{
		$ctn = '' ;
		$ctn .= $menu->ObtientDefinitions() ;
		$menus = $menu->SousMenusAffichables() ;
		foreach($menus as $i => $sousMenu)
		{
			$ctn .= $this->RenduDefinitionsMenu($sousMenu) ;
		}
		return $ctn ;
	}
	protected function RenduDefinitionsMenuRacine()
	{
		return $this->RenduDefinitionsMenu($this->MenuRacine) ;
	}
	protected function ObtientUrlMenu(& $menu)
	{
	}
	protected function RenduMenuRacine(& $menu)
	{
		return $this->RenduMenu($menu) ;
	}
	protected function RenduMenu($menu)
	{
		$ctn = '' ;
		// print count($menu->SousMenus) ;
		if(! $menu->EstAffichable())
		{
			return '' ;
		}
		$menu->ComposantSupport = $this ;
		if(! $menu->EstMenuRacine())
		{
			$ctn .= '<li>'.PHP_EOL ;
			// echo get_class($menu) ;
			$ctn .= $this->RenduTagOuvrLien($menu).PHP_EOL ;
			$ctn .= $this->RenduIconeMenu($menu).PHP_EOL ;
			$ctn .= $this->RenduFaMenu($menu).PHP_EOL ;
			$ctn .= $this->RenduTitreMenu($menu).PHP_EOL ;
			$ctn .= $this->RenduTagFermLien($menu).PHP_EOL ;
		}
		$menus = $menu->SousMenusAffichables() ;
		if(count($menus))
		{
			$ctn .= '<ul' ;
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
			$ctn .= '</ul>'.PHP_EOL ;
		}
		if(! $menu->EstMenuRacine())
		{
			$ctn .= '</li>' ;
		}
		$menu->ComposantSupport = null ;
		return $ctn ;
	}
	protected function RenduTagOuvrLien(& $menu)
	{
		$ctn = '' ;
		$ctn .= '<a href="'.$menu->ObtientUrl().'"' ;
		if($menu->NomClasseCSS != '')
		{
			$ctn .= ' class="'.$this->NomClasseCSS.((! $menu->EstSelectionne) ? '' : ' '.$this->NomClasseCSSSelect).'"' ;
		}
		elseif($menu->EstSelectionne)
			$ctn .= ' class="'.$this->NomClasseCSSSelect.'"' ;
		if($menu->Tips != '')
		{
			$ctn .= ' title="'.htmlspecialchars($menu->Tips).'"' ;
		}
		if($menu->FenetreCible != '')
		{
			$ctn .= ' target="'.htmlspecialchars($menu->FenetreCible).'"' ;
		}
		$ctn .= '>' ;
		return $ctn ;
	}
	protected function RenduTagFermLien(& $menu)
	{
		$ctn = '' ;
		$ctn .= '</a>' ;
		return $ctn ;
	}
	protected function RenduMiniatureMenu(& $menu)
	{
		$ctn = '' ;
		if($this->InclureRenduMiniature && $menu->InclureRenduMiniature)
		{
			$cheminMiniature = $menu->ObtientCheminMiniature() ;
			if($cheminMiniature == '')
			{
				$cheminMiniature = $this->CheminMiniatureParDefaut ;
			}
			$attrHauteur = (intval($this->HauteurMiniature) > 0) ? ' width="'.$this->HauteurMiniature.'"' : '' ;
			$ctn .= '<img src="'.$cheminMiniature.'"'.$attrHauteur.' border="0" />' ;
		}
		return $ctn ;
	}
	protected function RenduIconeMenu(& $menu)
	{
		$ctn = '' ;
		if($this->InclureRenduIcone && $menu->InclureRenduIcone)
		{
			$cheminIcone = $menu->ObtientCheminIcone() ;
			if($cheminIcone == '')
			{
				$cheminIcone = $this->CheminIconeParDefaut ;
			}
			$ctn .= '<img src="'.$cheminIcone.'" border="0" /> ' ;
		}
		return $ctn ;
	}
	protected function RenduFaMenu(& $menu)
	{
		$ctn = '' ;
		if($this->InclureRenduFa && $menu->InclureRenduFa)
		{
			$classeFa = $menu->ClasseFa ;
			if($classeFa == '')
			{
				$classeFa = $this->ClasseFaParDefaut ;
			}
			$ctn .= '<i class="fa'.(($this->ClasseTailleFa != '') ? ' '.$this->ClasseTailleFa : '').' '.$classeFa.'"></i> ' ;
		}
		return $ctn ;
	}
	protected function RenduTitreMenu(& $menu)
	{
		$ctn = '' ;
		if($this->InclureRenduTitre && $menu->InclureRenduTitre)
		{
			$ctn .= $menu->ObtientTitre() ;
		}		
		return $ctn ;
	}
}