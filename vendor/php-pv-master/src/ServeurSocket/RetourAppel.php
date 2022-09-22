<?php

namespace Pv\ServeurSocket ;

class RetourAppel
{
	public $message = "resultat non defini" ;
	public $resultat ;
	public $codeErreur = -1 ;
	public function succes()
	{
		return $this->codeErreur == 0 ;
	}
	public function erreurTrouvee()
	{
		return $this->codeErreur != 0 ;
	}
}