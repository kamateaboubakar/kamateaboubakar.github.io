<?php

namespace Pv\ZoneWeb\Action ;

class RedirectScriptSession extends \Pv\ZoneWeb\Action\Action
{
	public $UrlDefaut = '' ;
	public function Execute()
	{
		return $this->ZoneParent->RenduRedirectScriptSession($this->UrlDefaut) ;
	}
}