<?php

namespace Pv\DeclenchTache ;

class Mois extends Jour
{
	public $Jour = 1 ;
	public function DelaiTacheAtteint(& $tacheProg)
	{
		if($this->Jour > date("t"))
			$this->Jour = date("t") ;
		$jourMois = intval(date("j")) ;
		return $jourMois == $this->Jour && parent::DelaiTacheAtteint($tacheProg) ;
	}
}