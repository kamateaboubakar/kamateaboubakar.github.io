<?php

namespace Pv\DeclenchTache ;

class Semaine extends Jour
{
	public $Jour = 1 ;
	public function DelaiTacheAtteint(& $tacheProg)
	{
		$secondes = intval(date("s")) ;
		return date("w") == $this->Jour && parent::DelaiTacheAtteint($tacheProg) ;
	}
}