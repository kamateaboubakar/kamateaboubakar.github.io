<?php

namespace Pv\ApiRestful ;

class Requete extends MessageHttp
{
	public $Methode ;
	public $EncodageParDefaut = "utf-8" ;
	public $EnteteContentType ;
	public $EnteteAuthorization ;
	public $EnteteAuthType ;
	public $EnteteAuthCredentials ;
	public $AttrsContentType = array() ;
	public $CheminRelatifRoute ;
	public $CorpsBrut ;
	public $Corps ;
	public function __construct()
	{
		$this->Entetes = array() ;
		$entetesTemp = apache_request_headers() ;
		foreach($entetesTemp as $nom => $val)
		{
			$this->Entetes[strtolower($nom)] = $val ;
		}
		$this->DetecteEntetesSpec() ;
		$attrs = explode("?", $_SERVER["REQUEST_URI"], 2) ;
		$this->CheminRelatifRoute = $attrs[0] ;
		$this->CorpsBrut = file_get_contents("php://input") ;
		$this->Corps = new \StdClass ;
		if($this->EnteteContentType == "application/x-www-form-urlencoded")
		{
			parse_str($this->CorpsBrut, $vals) ;
			foreach($vals as $nom => $val)
			{
				$this->Corps->$nom = $val ;
			}
		}
		elseif($this->EnteteContentType == "application/json")
		{
			$this->Corps = json_decode($this->CorpsBrut) ;
		}
	}
	protected function DetecteEntetesSpec()
	{
		$this->Methode = $_SERVER["REQUEST_METHOD"] ;
		if(isset($this->Entetes["x-http-method-override"]))
		{
			$this->Methode = $this->Entetes["x-http-method-override"] ;
		}
		if(isset($this->Entetes["content-type"]))
		{
			$attrsContentType = explode(";", strtolower($this->Entetes["content-type"])) ;
			$this->Entetes["content-type"] = $attrsContentType[0] ;
			array_splice($attrsContentType, 0, 1) ;
			$this->AttrsContentType = array() ;
			foreach($attrsContentType as $i => $attrSpec)
			{
				$attrs = explode("=", $attrSpec, 2) ;
				$this->AttrsContentType[strtolower($attrs[0])] = $attrs[1] ;
			}
			$this->EnteteContentType = $this->Entetes["content-type"] ;
		}
		$this->EnteteEncodage = (isset($this->AttrsContentType["encoding"])) ? $this->AttrsContentType["encoding"] : $this->EncodageParDefaut ;
		$this->EnteteAuthorization = (isset($this->Entetes["authorization"])) ? $this->Entetes["authorization"] : null ;
		if($this->EnteteAuthorization != null)
		{
			$attrsAuth = explode(" ", $this->EnteteAuthorization, 2) ;
			$this->EnteteAuthType = strtolower($attrsAuth[0]) ;
			$this->EnteteAuthCredentials = (count($attrsAuth) == 2) ? $attrsAuth[1] : null ;
		}
	}
	public function AttrEntete($nom, $valeurDefaut=null)
	{
		return (isset($this->Entetes[strtolower($nom)])) ? $this->Entetes[strtolower($nom)] : $valeurDefaut ;
	}
}