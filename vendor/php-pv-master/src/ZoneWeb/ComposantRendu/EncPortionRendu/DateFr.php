<?php

namespace Pv\ZoneWeb\ComposantRendu\EncPortionRendu ;

class DateFr extends \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Enc
{
	public $AppliquerTout = 0 ;
	public function Execute($params=array(), $elem=array())
	{
		return array_map('\Pv\Misc::date_fr', $params) ;
	}
}