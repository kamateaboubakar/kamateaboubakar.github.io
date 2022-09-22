<?php

namespace Pv\ZoneWeb\ComposantRendu\EncPortionRendu ;

class Enc
{
	public $Prefixe ;
	public $AppliquerTout = 0 ;
	public $NomParams = array() ;
	public function __construct($prefixe='')
	{
		$this->Prefixe = $prefixe;
	}
	public function Execute($params=array(), $elem=array())
	{
		return array() ;
	}
}