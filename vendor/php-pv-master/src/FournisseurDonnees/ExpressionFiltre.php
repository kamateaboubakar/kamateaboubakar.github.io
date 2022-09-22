<?php

namespace Pv\FournisseurDonnees ;


class ExpressionFiltre
{
	public $Texte = "" ;
	public $Parametres = array() ;
	public function EstVide()
	{
		return ($this->Texte == "") ? 1 : 0 ;
	}
}
