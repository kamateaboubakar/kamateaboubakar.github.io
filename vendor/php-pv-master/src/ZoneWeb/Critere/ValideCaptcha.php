<?php

namespace Pv\ZoneWeb\Critere ;

class ValideCaptcha extends \Pv\ZoneWeb\Critere\Critere
{
	public $FltCaptchaParent ;
	public $MessageErreur = "Le code de s&eacute;curit&eacute; saisi est incorrect" ;
	public function EstRespecte()
	{
		$ok  = $this->FltCaptchaParent->Composant->VerifieValeurSoumise($this->FltCaptchaParent->Lie()) ;
		return $ok ;
	}
}