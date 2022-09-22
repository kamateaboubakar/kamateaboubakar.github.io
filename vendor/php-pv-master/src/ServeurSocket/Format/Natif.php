<?php

namespace Pv\ServeurSocket\Format ;

class Natif extends \Pv\ServeurSocket\Format\FormatSocket
{
	public function Decode($contenu)
	{
		$resultat = false ;
		if(empty($contenu))
		{
			return $resultat ;
		}
		$resultat = unserialize($contenu) ;
		return $resultat ;
	}
	public function Encode($contenu)
	{
		if(empty($contenu))
		{
			return "" ;
		}
		return serialize($contenu) ;
	}
}