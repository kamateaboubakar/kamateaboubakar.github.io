<?php

namespace Pv\ZoneWeb\Commande ;

class RedirectionHttp extends \Pv\ZoneWeb\Commande\Commande
{
	public $NecessiteFormulaireDonnees = 0 ;
	public $NecessiteTableauDonnees = 0 ;
	public $Url = "" ;
	public $NomScript = "" ;
	public $Parametres = array() ;
	public $Script = null ;
	protected function ObtientUrl()
	{
		$url = $this->Url ;
		$script = null ;
		if($this->NomScript != "" && isset($this->ZoneParent->Scripts[$this->NomScript]))
		{
			$script = $this->ZoneParent->Scripts[$this->NomScript] ;
		}
		if($this->EstNul($script) && $this->EstPasNul($this->Script))
		{
			$script = $this->Script ;
		}
		if($this->EstPasNul($script))
		{
			$url = $script->ObtientUrl() ;
		}
		if($url != '' && count($this->Parametres) > 0)
		{
			$url = \Pv\Misc::update_url_params($url, $this->Parametres) ;
		}
		return $url ;
	}
	protected function ExecuteInstructions()
	{
		$url = $this->ObtientUrl() ;
		if($url == '')
		{
			$this->RenseigneErreur("URL non definie pour la commande ".$this->IDInstanceCalc) ;
			return ;
		}
		\Pv\Misc::redirect_to($url) ;
	}
}