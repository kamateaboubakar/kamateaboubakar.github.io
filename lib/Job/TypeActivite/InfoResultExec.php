<?php

namespace Rpa2p\Job\TypeActivite ;

class InfoResultExec
{
	public $Nom ;
	public $Valeur ;
	public $NiveauAlerte ;
	public function __construct($infoArr)
	{
		$this->Nom = $infoArr[0] ;
		$this->Valeur = (isset($infoArr[1])) ? $infoArr[1] : "" ;
		$this->NiveauAlerte = (isset($infoArr[2])) ? $infoArr[2] : "" ;
	}
}
