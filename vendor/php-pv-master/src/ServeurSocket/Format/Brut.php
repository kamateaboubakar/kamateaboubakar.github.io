<?php

namespace Pv\ServeurSocket\Format ;

class Brut extends \Pv\ServeurSocket\Format\FormatSocket
{
	public function Decode($contenu)
	{
		return $contenu ;
	}
	public function Encode($contenu)
	{
		return $contenu ;
	}
}