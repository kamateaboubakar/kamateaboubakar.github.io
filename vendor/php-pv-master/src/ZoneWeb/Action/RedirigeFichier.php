<?php

namespace Pv\ZoneWeb\Action ;

class RedirigeFichier extends \Pv\ZoneWeb\Action\Action
{
	public $CheminFichierSource ;
	public function Execute()
	{
		$this->DetermineFichierSource() ;
		if($this->CheminFichierSource == '')
		{
			$this->AfficheMsgErreur() ;
		}
		else
		{
			Header('location: '.$this->CheminFichierSource."\r\n") ;
		}
		exit ;
	}
	protected function AfficheMsgErreur()
	{
		echo 'Fichier source non renseigne ;/' ;
	}
	protected function DetermineFichierSource()
	{
	}
}