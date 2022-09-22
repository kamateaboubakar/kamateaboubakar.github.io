<?php

namespace Pv\ZoneWeb\Action ;

class SoumetForm extends \Pv\ZoneWeb\Action\RenduPage
{
	public $ParamsGet = array() ;
	public $ParamsPost = array() ;
	public $DelaiEnvoi = 0 ;
	public $UrlEnvoi = "" ;
	public $MsgChargement = "Veuillez patienter..." ;
	public $CtnAttrsBody = 'onload="demarreSoumissForm() ;"' ;
	protected function RenduCorpsDoc()
	{
		$urlEnvoi = \Pv\Misc::update_url_params($this->UrlEnvoi, $this->ParamsGet) ;
		$ctn = '' ;
		$ctn .= '<div class="msg-chargement" align="center">'.$this->MsgChargement.'</div>'.PHP_EOL ;
		$ctn .= '<form action="'.htmlentities($urlEnvoi).'" id="formSoumis" method="post">'.PHP_EOL ;
		foreach($this->ParamsPost as $n => $v)
		{
			$ctn .= '<input type="hidden" name="'.htmlentities($n).'" value="'.htmlentities($v).'" />'.PHP_EOL ;
		}
		$ctn .= '</form>' ;
		$ctn .= '<script type="text/javascript">
function demarreSoumissForm()
{
var delai = '.intval($this->DelaiEnvoi).' ;
var formSoumisNode = document.getElementById("formSoumis") ;
if(delai > 0) {
	setTimeout(function() { formSoumisNode.submit() ; }, delai * 1000) ;
}
else
{
	formSoumisNode.submit() ;
}
}
</script>' ;
		return $ctn ;
	}
}