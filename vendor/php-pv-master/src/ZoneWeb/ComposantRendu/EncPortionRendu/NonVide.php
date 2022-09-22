<?php

namespace Pv\ZoneWeb\ComposantRendu\EncPortionRendu ;

class NonVide extends \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Enc
{
	public $AppliquerTout = 0 ;
	public $Contenu = '${luimeme}' ;
	public function Execute($params=array(), $elem=array())
	{
		$results = array() ;
		foreach($params as $nom => $val)
		{
			if($val == "")
			{
				$results[$nom] = "" ;
			}
			else
			{
				$elem["luimeme"] = $val ;
				$elem["this"] = $val ;
				$elem["self"] = $val ;
				$results[$nom] = \Pv\Misc::_parse_pattern($this->Contenu, $elem) ;
			}
		}
		return $results ;
	}
}