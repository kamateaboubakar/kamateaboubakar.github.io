<?php

namespace Pv\ZoneWeb\Menu ;

class RedirectScript extends \Pv\ZoneWeb\Menu\MenuWeb
{
	public $NomScript = "" ;
	public $ParamsScript = array() ;
	public $NomScriptsSelect = array() ;
	public function EstAccessible()
	{
		$ok = parent::EstAccessible() ;
		if(! $ok)
			return 0 ;
		$script = $this->ObtientScript() ;
		$ok = 0 ;
		if($script != null)
		{
			$ok = $script->EstAccessible() ;
			/*
			print $script->NomElementZone.' : '.$ok ;
			print_r($script->Privileges) ;
			print "<br>" ;
			*/
		}
		return $ok ;
	}
	protected function ObtientScript()
	{
		$script = null ;
		if($this->EstNul($this->ZoneParent))
		{
			return $script ;
		}
		if(isset($this->ZoneParent->Scripts[$this->NomScript]))
		{
			$script = $this->ZoneParent->Scripts[$this->NomScript] ;
		}
		return $script ;
	}
	public function ObtientTitre()
	{
		$valeur = parent::ObtientTitre() ;
		if($valeur == "")
		{
			$script = $this->ObtientScript() ;
			if($script != null)
			{
				$valeur = $script->Titre ;
			}
		}
		return $valeur ;
	}
	public function ObtientCheminIcone()
	{
		$valeur = parent::ObtientCheminIcone() ;
		if($valeur == "")
		{
			$script = $this->ObtientScript() ;
			if($script != null)
			{
				$valeur = $script->CheminIcone ;
			}
		}
		return $valeur ;
	}
	public function ObtientUrl()
	{
		$valeur = parent::ObtientUrl() ;
		if($valeur == "")
		{
			$script = $this->ObtientScript() ;
			if($script != null)
			{
				$valeur = \Pv\Misc::remove_url_params(
					\Pv\Misc::get_current_url()
				)."?".urlencode($this->ZoneParent->NomParamScriptAppele)."=".urlencode($script->NomElementZone) ;
				$valeur = \Pv\Misc::update_url_params($valeur, $this->ParamsScript) ;
			}
		}
		return $valeur ;
	}
	public function ObtientStatutSelection()
	{
		if($this->EstNul($this->ZoneParent))
			return 0 ;
		$script = $this->ObtientScript() ;
		if($script == null)
			return 0 ;
		return ($this->ZoneParent->ScriptAppele->NomElementZone == $this->NomScript || (count($this->NomScriptsSelect) > 0 && in_array($this->ZoneParent->ScriptAppele->NomElementZone, $this->NomScriptsSelect))) ? 1 : 0 ;
	}
}