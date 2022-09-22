<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class Recaptcha3 extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public static $ClesSiteInclus = array() ;
	public $CleSite ;
	public $Etiquette = '<img src="https://www.gstatic.com/images/icons/material/product/2x/recaptcha_24dp.png" class="devsite-product-logo" alt="reCAPTCHA" /> Valid&eacute; par <b>reCAPTCHA V3</b>' ;
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
		// $this->CorrigeValeur() ;
		$ctn = '' ;
		if($this->CleSiteUtilisee != '' && ! in_array($this->CleSiteUtilisee, \Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha3::$ClesSiteInclus))
		{
			$ctn .= '<script src="https://www.google.com/recaptcha/api.js?render='.$this->CleSiteUtilisee.'"></script>' ;
			\Pv\ZoneWeb\FiltreDonnees\Composant\Recaptcha3::$ClesSiteInclus[] = $this->CleSiteUtilisee ;
		}
		if($this->CleSiteUtilisee != '')
		{
			$ctn .= '<script>
grecaptcha.ready(function() {
grecaptcha.execute("'.$this->CleSiteUtilisee.'", {action: "'.$this->UseCase.'"}).then(function(token) {
document.getElementById("'.$this->IDInstanceCalc.'").value = token ;
});
});
</script>' ;
			$ctn .= $this->Etiquette ;
			$ctn .= '<input type="hidden" id="'.$this->IDInstanceCalc.'" name="'.$this->NomElementHtml.'" value="" />' ;
		}
		else
		{
			$ctn .= 'Cle du site et secrete manquante. Veuillez vous rendre sur Google/GRECAPTCHA' ;
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
		$this->ContenuRequeteVerif = 'secret='.urlencode($this->CleSecreteUtilisee).'&response='.urlencode($texte) ;
		if($this->HoteUtilise != '')
		{
			$this->ContenuRequeteVerif .= '&hostname='.urlencode($this->HoteUtilise) ;
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