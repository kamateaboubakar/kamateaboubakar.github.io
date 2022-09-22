<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class FonctInstJQuery
{
	public $NomMembreInst ;
	public $Args = array() ;
	public $Contenu = "" ;
	public function __construct($nomMembreInst, $args, $ctn='')
	{
		$this->NomMembreInst = $nomMembreInst ;
		$this->Args = $args ;
		$this->Contenu = $ctn ;
	}
	public function CtnJSDef()
	{
		$ctn = '' ;
		$ctn .= 'function ('.join(", ", $this->Args).') {
'.$this->Contenu.'
}' ;
		return $ctn ;
	}
}