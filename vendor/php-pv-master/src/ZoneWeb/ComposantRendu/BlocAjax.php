<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class BlocAjax extends \Pv\ZoneWeb\ComposantRendu\BaliseHtml
{
	public $TexteMsgSurExpirationAtteint = "" ;
	public $TexteMsgSurChargement = "Chargement en cours..." ;
	public $AlignMsgSurChargement = "center" ;
	public $ActionRecupContenuHtml = null ;
	public $DelaiExpiration = 10 ;
	public $AutoRafraich = false ;
	public $DelaiRafraich = 0 ;
	public $ContenuHtml = "<p>Bloc Ajax</p>" ;
	public $Support = null ;
	public $NomActionRecupContenuHtml = null ;
	public static $SourceInclus = 0 ;
	public static $CheminSource = "js/AppelAjax.js" ;
	public function AdopteScript($nom, & $script)
	{
		parent::AdopteScript($nom, $script) ;
		$this->NomActionRecupContenuHtml = $this->IDInstanceCalc.'_ContenuHtml' ;
		$this->ActionRecupContenuHtml = new \Pv\ZoneWeb\ComposantRendu\BlocAjaxActRecupCtnHtml() ;
		$this->InscritActionAvantRendu($this->NomActionRecupContenuHtml, $this->ActionRecupContenuHtml) ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->InclutSource() ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'"></div>';
		$ctn .= '<script type="text/javascript">
var bloc'.$this->IDInstanceCalc.' = new BlocAjax('.svc_json_encode($this->IDInstanceCalc).', "POST") ;
bloc'.$this->IDInstanceCalc.'.TexteMsgSurChargement = '.svc_json_encode($this->TexteMsgSurChargement).' ;
bloc'.$this->IDInstanceCalc.'.AutoRafraich = '.svc_json_encode($this->AutoRafraich).' ;
bloc'.$this->IDInstanceCalc.'.DelaiRafraich = '.svc_json_encode($this->DelaiRafraich).' ;
bloc'.$this->IDInstanceCalc.'.AlignMsgSurChargement = '.svc_json_encode($this->AlignMsgSurChargement).' ;
bloc'.$this->IDInstanceCalc.'.RequeteAjax.DelaiExpiration = '.intval($this->DelaiExpiration).' ;
bloc'.$this->IDInstanceCalc.'.UtiliserContenuBrutContenuCorps = true ;'.PHP_EOL ;
if(isset($HTTP_RAW_POST_DATA))
{
$ctn .= 'bloc'.$this->IDInstanceCalc.'.ContenuBrutContenuCorps = '.svc_json_encode($HTTP_RAW_POST_DATA).' ;'.PHP_EOL ;
}
$ctn .= 'bloc'.$this->IDInstanceCalc.'.DefinitUrl('.svc_json_encode($this->ActionRecupContenuHtml->ObtientUrl()).') ;
bloc'.$this->IDInstanceCalc.'.Remplit() ;
</script>'.PHP_EOL ;
		return $ctn ;
	}
	public function InscritSupport(& $composantIU)
	{
		$this->ComposantSupport = & $composantIU ;
		$composantIU->AdopteComposantUI($this->IDInstanceCalc."_support", $this) ;
	}
	public function RecupContenu()
	{
		if($this->EstPasNul($this->Support))
		{
			return $this->Support->RenduDispositif() ;
		}
		return $this->ContenuHtml ;
	}
}