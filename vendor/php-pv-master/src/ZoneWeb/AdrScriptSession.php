<?php

namespace Pv\ZoneWeb ;

class AdrScriptSession
{
	public $ChaineGet ;
	public $DonneesPost = array() ;
	public function Sauvegarde(& $zone)
	{
		$_SESSION[$zone->NomElementApplication."_AddrScriptSession"] = serialize($this) ;
	}
	public static function Restaure(& $zone)
	{
		if(isset($_SESSION[$zone->NomElementApplication."_AddrScriptSession"]))
		{
			return unserialize($_SESSION[$zone->NomElementApplication."_AddrScriptSession"]) ;
		}
		return new \Pv\ZoneWeb\AdrScriptSession() ;
	}
	public function ImporteRequeteHttp(& $zone)
	{
		$this->ChaineGet = $_SERVER["REQUEST_URI"] ;
		$this->DonneesPost = $_POST ;
		$this->Sauvegarde($zone) ;
		// print_r($_SESSION) ;
	}
	public function ExporteZone(& $zone)
	{
		return \Pv\ZoneWeb\AdrScriptSession::Restaure($zone) ;
	}
}