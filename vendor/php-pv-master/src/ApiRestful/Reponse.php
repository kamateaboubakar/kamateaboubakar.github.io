<?php

namespace Pv\ApiRestful ;

class Reponse extends MessageHttp
{
	public $NomFichierAttache ;
	public $EnteteContentType = "application/json" ;
	public $Contenu ;
	public $EnteteStatusCode = 200 ;
	public $MessageStatusCode ;
	public function __construct()
	{
		$this->Contenu = new \Pv\ApiRestful\ContenuJson() ;
	}
	protected function EnvoieCode($code)
	{
		if(function_exists('http_response_code'))
		{
			http_response_code($code) ;
		}
		elseif($code !== NULL)
		{
			$text = '' ;
			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}
			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			header($protocol . ' ' . $code . ' ' . $text);
		}
	}
	public function InsereErreur($code, $msgInterne, $msgUser='')
	{
		$errData = new \Pv\ApiRestful\ErrContenuJson() ;
		$errData->code = $code ;
		$errData->internalMessage = $msgInterne ;
		$errData->userMessage = ($msgUser != '') ? $msgUser : $msgInterne ;
		$this->Contenu->errors[] = $errData ;
		$this->Contenu->data = null ;
	}
	protected function DefinitEnteteStatusCode($code, $message='')
	{
		$this->EnteteStatusCode = $code ;
		if($code != 200)
		{
			$this->InsereErreur($code, (($message != '') ? $message : 'Le service a renvoye le code HTTP '.$code)) ;
		}
	}
	public function EstSucces()
	{
		return $this->EnteteStatusCode == 200 ;
	}
	public function EstEchec()
	{
		return ! $this->EstSucces() ;
	}
	public function ConfirmeSucces()
	{
		$this->DefinitEnteteStatusCode(200) ;
	}
	public function ConfirmeInvalide($message='')
	{
		$this->DefinitEnteteStatusCode(400, $message) ;
	}
	public function ConfirmeEchecAuth($message='')
	{
		$this->DefinitEnteteStatusCode(403, $message) ;
	}
	public function ConfirmeErreurInterne($message='')
	{
		$this->DefinitEnteteStatusCode(500, $message) ;
	}
	public function ConfirmeNonAutoris($message='')
	{
		$this->DefinitEnteteStatusCode(401, $message) ;
	}
	public function ConfirmeNonAutorise($message='')
	{
		$this->DefinitEnteteStatusCode(401, $message) ;
	}
	public function ConfirmeNonTrouve($message='')
	{
		$this->DefinitEnteteStatusCode(404, $message) ;
	}
	protected function CalculeEntetesSpec(& $api)
	{
		$this->Entetes["Access-Control-Allow-Origin"] = $api->OriginesAutorisees ;
		$this->Entetes["Content-Type"] = $this->EnteteContentType ;
		$this->Entetes["Cache-Control"] = "no-cache, must-revalidate" ;
		$this->Entetes["Expires"] = "Sat, 01 Jul 1970 00:00:00 GMT" ;
	}
	protected function EnvoieEntetes(& $api)
	{
		$this->CalculeEntetesSpec($api) ;
		foreach($this->Entetes as $nom => $val)
		{
			header($nom.": ".$val) ;
		}
	}
	public function EnvoieRendu(& $api)
	{
		$this->EnvoieCode($this->EnteteStatusCode) ;
		$this->EnvoieEntetes($api) ;
		$contenu = $this->Contenu ;
		$contenu->_metadatas = $api->Metadatas ;
		if($api->InclureStatutReponse)
		{
			$contenu->status = (count($this->Contenu->errors) == 0) ? "success" : "error" ;
		}
		if($api->CrypterReponse > 0 && count($this->Contenu->errors) == 0)
		{
			$crypter = new \Pv\Openssl\Crypter() ;
			$crypter->cipher = $api->CypherCryptReponse ;
			$crypter->key = $api->CleCryptReponse ;
			$crypter->hmac = $api->HmacCryptReponse ;
			if($api->CrypterReponse == 2)
			{
				$contenu = $crypter->encode(json_encode($contenu)) ;
			}
			else
			{
				$contenu->data = $crypter->encode(json_encode($contenu->data)) ;
			}
		}
		if($api->EncodageJsonNatif == true)
		{
			echo json_encode($contenu) ;
		}
		else
		{
			echo svc_json_encode($contenu) ;
		}
	}
}