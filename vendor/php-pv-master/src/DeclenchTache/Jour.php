<?php

namespace Pv\DeclenchTache ;

class Jour extends DeclenchTache
{
	public $Heure = 0 ;
	public $Minute = 0 ;
	public $Seconde = 0 ;
	public function DelaiTacheAtteint(& $tacheProg)
	{
		$secondes = intval(date("s")) ;
		return date("G") == $this->Heure && intval(date("m")) == $this->Minute && ($secondes >= $this->Seconde && $secondes <= $this->Seconde + $tacheProg->ApplicationParent->ValeurUniteTache) ;
	}
}