<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class Recaptcha2 extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public static $SourceIncluse = 0 ;
	public $CleSite ;
	public $CleSecrete ;
	public $Hote ;
	public $UseCase = "homepage" ; // https://developers.google.com/recaptcha/docs/v3
	public $CleSiteUtilisee ;
	public $CleSecreteUtilisee ;
	public $HoteUtilise ;
	public $ContenuBrutVerif ;
	protected function CalculeCles()
	{
		$this->CleSiteUtilisee = ($this->CleSite != '') ? $this->CleSite : $this->ZoneParent->CleSiteRecaptcha ;
		$this->CleSecreteUtilisee = ($this->CleSecrete != '') ? $this->CleSecrete : $this->ZoneParent->CleSecreteRecaptcha ;
		$this->HoteUtilise = ($this->Hote != '') ? $this->Hote : $this->ZoneParent->HoteRecaptcha ;
		$ctn = '' ;
	}
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$this->CalculeCles() ;
		$ctn = '' ;
		if($this->CleSiteUtilisee != '' && \Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha2::$SourceIncluse == 0)
		{
			$ctn .= '<script src="https://www.google.com/recaptcha/api.js" async defer></script>'.PHP_EOL ;
			\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha2::$SourceIncluse = 1 ;
		}
		if($this->CleSiteUtilisee != '')
		{
			$ctn .= '<script type="text/javascript">
function '.$this->IDInstanceCalc.'_capture(response)
{
document.getElementById("'.$this->IDInstanceCalc.'").value = response ;
}
</script>'.PHP_EOL ;
			$ctn .= '<div id="'.$this->IDInstanceCalc.'_recaptcha" class="g-recaptcha" data-sitekey="'.$this->CleSiteUtilisee.'" data-callback="'.$this->IDInstanceCalc.'_capture"></div>' ;
			$ctn .= '<input type="hidden" id="'.$this->IDInstanceCalc.'" name="'.$this->NomElementHtml.'" value="" />' ;
		}
		else
		{
			$ctn .= 'Cle du site et secrete manquante. Veuillez vous rendre sur Google/RECAPTCHA V2' ;
		}
		return $ctn ;
	}
	public function VerifieValeurSoumise($texte)
	{
		$this->CalculeCles() ;
		if($texte == '')
		{
			return false ;
		}
		// URL : https://www.google.com/recaptcha/api/siteverify
		$fp = fsockopen("ssl://www.google.com", 443, $errno, $errstr, $timeout = 30) ;
		if(! $fp)
		{
			return false ;
		}
		$this->ContenuRequeteVerif = 'secret='.$this->CleSecreteUtilisee.'&response='.urlencode($texte) ;
		if($this->HoteUtilise != '')
		{
			$this->ContenuRequeteVerif .= '&remoteip='.urlencode($this->HoteUtilise) ;
		}
		//send the server request
		fputs($fp, "POST /recaptcha/api/siteverify HTTP/1.1\r\n");
		fputs($fp, "Host: www.google.com\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($this->ContenuRequeteVerif)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $this->ContenuRequeteVerif . "\r\n\r\n");
		$this->ContenuReponseVerif = '' ;
		//loop through the response from the server
		while(! feof($fp))
		{
			$this->ContenuReponseVerif .= fgets($fp, 4096);
		}
		//close fp - we are done with it
		fclose($fp) ;
		$partsCtn = explode("\r\n\r\n", $this->ContenuReponseVerif, 2) ;
		if(count($partsCtn) == 2)
		{
			if(stripos($partsCtn[0], "Transfer-Encoding: chunked") !== false)
			{
				$partsCtn[1] = preg_replace('/[^\{]+\{/', '{', $partsCtn[1]) ;
				$partsCtn[1] = preg_replace('/\}[^\}]+/', '}', $partsCtn[1]) ;
			}
			$resultObj = svc_json_decode($partsCtn[1]) ;
			if($resultObj !== null && $resultObj->success)
			{
				return true ;
			}
		}
		return false ;
	}
}