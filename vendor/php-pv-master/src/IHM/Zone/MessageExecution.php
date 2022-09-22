<?php

namespace Pv\IHM\Zone ;

class MessageExecution
{
	public $NomScriptSource ;
	public $Statut ;
	public $Contenu ;
	public function EstVide()
	{
		return $this->Contenu == "" ;
	}
	public function NonRenseigne()
	{
		return $this->Statut === null ;
	}
	public function Succes()
	{
		return $this->Statut == 1 ;
	}
}
