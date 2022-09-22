<?php

namespace Pv\ZoneWeb\Menu ;

class MenuWeb extends \Pv\Objet\Objet
{
	protected $EstRacine = 0 ;
	public $BarreMenu ;
	public $InclureRenduFa = 1 ;
	public $InclureRenduTitre = 1 ;
	public $InclureRenduIcone = 1 ;
	public $InclureRenduMiniature = 1 ;
	public $EstVisible = 1 ;
	public $FenetreCible = "" ;
	public $Titre = "" ;
	public $Chemin = "" ;
	public $Url = "" ;
	public $CheminIcone = "" ;
	public $CheminMiniature = "" ;
	public $NomClasseCSS = '' ;
	public $ClasseFa = '' ;
	public $Tips = "" ;
	public $Description = "" ;
	public $SousMenus = array() ;
	public $MenuParent = null ;
	public $NomElementMenu = null ;
	public $ZoneParent = null ;
	public $NomElementZone = "" ;
	public $ApplicationParent = null ;
	public $EstSelectionne = 0 ;
	public $StatutSelectionDetecte = 0 ;
	public $ComposantSupport = null ;
	public $NomClasseSousMenuFige = "\Pv\ZoneWeb\Menu\Fige" ;
	public $NomClasseSousMenuUrl = "\Pv\ZoneWeb\Menu\RedirectHttp" ;
	public $NomClasseSousMenuScript = "\Pv\ZoneWeb\Menu\RedirectScript" ;
	public $Privileges = array() ;
	public $ValsConfigSpec = array() ;
	public function EstAccessible()
	{
		if($this->EstNul($this->ZoneParent) || count($this->Privileges) == 0)
		{
			return 1 ;
		}
		return $this->ZoneParent->PossedePrivileges($this->Privileges) ;
	}
	public function EstMenuRacine()
	{
		return $this->EstRacine ;
	}
	public function ObtientDefinitions()
	{
		return "" ;
	}
	public function ObtientTitre()
	{
		$valeur = "" ;
		if($this->Titre != "")
		{
			$valeur = $this->Titre ;
		}
		return $valeur ;
	}
	public function ObtientCheminIcone()
	{
		$valeur = "" ;
		if($this->CheminIcone != "")
		{
			$valeur = $this->CheminIcone ;
		}
		return $valeur ;
	}
	public function ObtientCheminMiniature()
	{
		$valeur = "" ;
		if($this->CheminMiniature != "")
		{
			$valeur = $this->CheminMiniature ;
		}
		return $valeur ;
	}
	public function ObtientChemin()
	{
		$valeur = "" ;
		if($this->Chemin != "")
		{
			$valeur = $this->Chemin ;
		}
		return $valeur ;
	}
	public function ObtientUrl()
	{
		$valeur = "" ;
		if($this->Url != "")
		{
			$valeur = $this->Url ;
		}
		return $valeur ;
	}
	public function AdopteZone($nom, & $zone)
	{
		$this->NomElementZone = $nom ;
		$this->ZoneParent = & $zone ;
	}
	public function AdopteMenu($nom, & $menu)
	{
		$this->NomElementMenu = $nom ;
		$this->MenuParent = & $menu ;
		$this->AdopteZone($nom, $menu->ZoneParent) ;
	}
	public function CreeSousMenu($nomClasseSousMenu)
	{
		$menu = $this->ValeurNulle() ;
		if(! class_exists($nomClasseSousMenu))
		{
			return $menu ;
		}
		$menu = new $nomClasseSousMenu() ;
		return $menu ;
	}
	protected function ObtientNomNouvSousMenu($nom)
	{
		$nom = ($nom == '') ? uniqid("SousMenu_") : $nom ;
		if(isset($this->SousMenus[$nom]))
		{
			$nom = "SousMenu_".count($this->SousMenus) ;
		}
		return $nom ;
	}
	public function & InscritSousMenu($nomClasseSousMenu, $nom)
	{
		$menu = $this->CreeSousMenu($nomClasseSousMenu) ;
		if($this->EstNul($menu))
		{
			return $menu ;
		}
		$this->ValideInscriptionSousMenu($nom, $menu) ;
		return $menu ;
	}
	protected function ValideInscriptionSousMenu($nom, & $menu)
	{
		$nom = $this->ObtientNomNouvSousMenu($nom) ;
		$menu->AdopteMenu($nom, $this) ;
		$this->SousMenus[$nom] = & $menu ;
	}
	public function DeclareSousMenu($nomClasseSousMenu, $nom)
	{
		$menu = $this->InscritSousMenu($nomClasseSousMenu) ;
		if($this->EstNul($menu))
		{
			return $menu ;
		}
		$nomPropriete = 'SousMenu'.ucfirst($nom) ;
		$this->$nomPropriete = & $menu ;
	}
	public function DetecteStatutSelection()
	{
		if($this->StatutSelectionDetecte)
			return ;
		$this->EstSelectionne = $this->ObtientStatutSelection() ;
		$menus = $this->SousMenusAffichables() ;
		if(! $this->EstSelectionne)
		{
			$nomSousMenus = array_keys($menus) ;
			foreach($nomSousMenus as $i => $nom)
			{
				$menus[$nom]->DetecteStatutSelection() ;
				$this->EstSelectionne = $menus[$nom]->EstSelectionne ;
				if($this->EstSelectionne)
				{
					break ;
				}
			}
		}
	}
	protected function ObtientStatutSelection()
	{
		$selectionne = 0 ;
		return $selectionne ;
	}
	public function EstAffichable()
	{
		return $this->EstVisible == 1 && $this->EstAccessible() ;
	}
	public function SousMenusAffichables()
	{
		$menus = array() ;
		foreach($this->SousMenus as $nom => $sousMenu)
		{
			if($sousMenu->EstAffichable())
			{
				$menus[$nom] = & $this->SousMenus[$nom] ;
			}
		}
		return $menus ;
	}
	public function & InscritSousMenuFige($nom, $titre="")
	{
		$nom = $this->ObtientNomNouvSousMenu($nom) ;
		$menu = $this->InscritSousMenu($this->NomClasseSousMenuFige, $nom) ;
		$menu->Titre = $titre ;
		return $menu ;
	}
	public function & InscritSousMenuAppelJs($titre="", $fonct="", $args=array())
	{
		$nom = $this->ObtientNomNouvSousMenu("") ;
		$menu = $this->InscritSousMenu("\Pv\ZoneWeb\Menu\AppelJs", $nom) ;
		$menu->Titre = $titre ;
		$menu->NomFonct = $fonct ;
		$menu->Args = $args ;
		return $menu ;
	}
	public function & InscritSousMenuFonctJs($titre="", $fonct="", $args=array())
	{
		$menu = $this->InscritSousMenuAppelJs($titre, $fonct, $args) ;
		return $menu ;
	}
	public function & InscritSousMenuUrl($titre, $url)
	{
		$nom = 'SousMenuUrl'.count($this->SousMenus) ;
		$menu = $this->InscritSousMenu($this->NomClasseSousMenuUrl, $nom) ;
		$menu->Url = $url ;
		$menu->Titre = $titre ;
		return $menu ;
	}
	public function & InscritSousMenuScript($nomScript)
	{
		$nom = $nomScript ;
		$menu = $this->InscritSousMenu($this->NomClasseSousMenuScript, $nom) ;
		$menu->NomScript = $nomScript ;
		return $menu ;
	}
	public function DefinitValeurConfigSpec($nom, $val)
	{
		$this->ValsConfigSpec[$nom] = $val ;
	}
	public function DefinitValConfigSpec($nom, $val)
	{
		$this->DefinitValeurConfigSpec($nom, $val) ;
	}
	public function DefinitValCfgSpec($nom, $val)
	{
		$this->DefinitValeurConfigSpec($nom, $val) ;
	}
	public function ObtientValeurConfigSpec($nom, $valParDefaut=false)
	{
		return (isset($this->ValsConfigSpec[$nom])) ? $this->ValsConfigSpec[$nom] : $valParDefaut ;
	}
	public function ObtientValConfigSpec($nom, $valParDefaut=false)
	{
		return $this->ObtientValeurConfigSpec($nom, $valParDefaut) ;
	}
	public function ObtientValCfgSpec($nom, $valParDefaut=false)
	{
		return $this->ObtientValeurConfigSpec($nom, $valParDefaut) ;
	}
}