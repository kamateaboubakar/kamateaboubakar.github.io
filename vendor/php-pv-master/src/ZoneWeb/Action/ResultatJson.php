<?php

namespace Pv\ZoneWeb\Action ;

class ResultatJson extends \Pv\ZoneWeb\Action\Action
{
	public $Resultat = null ;
	public $InclureEnteteContenu = 0 ;
	public $AfficherException = 0 ;
	public function Execute()
	{
		if(! is_object($this->Resultat))
		{
			$this->Resultat = new \StdClass() ;
		}
		$this->ConstruitResultat() ;
		if($this->InclureEnteteContenu)
		{
			Header('Content-Type:application/json'."\r\n") ;
		}
		if($this->AfficherException == 1)
		{
			echo svc_json_encode($this->Resultat) ;
		}
		else
		{
			echo @svc_json_encode($this->Resultat) ;
		}
		$this->ZoneParent->AnnulerRendu = 1 ;
		exit ;
	}
	protected function ConstruitResultat()
	{
	}
}