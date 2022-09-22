<?php

namespace Pv\PlateformeProc ;

class http extends PlateformeProc
{
	public function EstDisponible()
	{
		return php_sapi_name() != 'cli' ? 1 : 0 ;
	}
	public function RecupArgs()
	{
		$args = $_GET ;
		return $args ;
	}
	protected function ExtraitPort()
	{
		$port = (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != 80) ? $_SERVER["SERVER_PORT"] : 80 ;
		return $port ;
	}
	protected function ExtraitUrlProg(& $prog)
	{
		$port = $this->ExtraitPort() ;
		$url = (isset($_SERVER["HTTPS"])) ? 'https' : 'http'.'://'.$_SERVER["SERVER_NAME"].(($port != 80) ? ':'.$port : '').'/'.$prog->CheminFichierRelatif ;
		return $url ;
	}
	public function LanceProcessusProg(& $prog)
	{
		$port = $this->ExtraitPort() ;
		$entetesHttp = '' ;
		$entetesHttp .= "GET /".$prog->CheminFichierRelatif."?".\Pv\Misc::http_build_query_string($prog->ArgsParDefaut)." HTTP/1.0\r\n" ;
		$entetesHttp .= "Host: ".$_SERVER["SERVER_NAME"]."\r\n";
		$entetesHttp .= "Connection: close\r\n\r\n";
		$flux = @fsockopen($_SERVER["SERVER_NAME"], $port) ;
		if($flux == false)
		{
			return 0 ;
		}
		fputs($flux, $entetesHttp) ;
		fclose($flux) ;
		$flux = false ;
		return 1 ;
	}
}