<?php

namespace Pv\ZoneWeb\ComposantRendu\EncPortionRendu ;

class Url extends \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Enc
{
	public $AppliquerTout = 1 ;
	public function Execute($params=array(), $elem=array())
	{
		return array_map('urlencode', $params) ;
	}
}