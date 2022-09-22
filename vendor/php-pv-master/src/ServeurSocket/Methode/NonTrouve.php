<?php

namespace Pv\ServeurSocket\Methode ;

class NonTrouve extends \Pv\ServeurSocket\Methode\MethodeSocket
{
	public $MessageRetour = "le nom de la methode a appeler est invalide" ;
	public $CodeRetour = -1 ;
	protected function ExecuteInstructions()
	{
		$this->SignaleErreur($this->CodeRetour, $this->MessageRetour) ;
	}
}