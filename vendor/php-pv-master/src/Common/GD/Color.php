<?php

namespace Pv\Common\GD ;

class Color
{
	public $R ;
	public $G ;
	public $B ;
	function __construct($R=0, $G=0, $B=0)
	{
		$this->R = $R ;
		$this->G = $G ;
		$this->B = $B ;
	}
	function FromRVBHex($RVB)
	{
		$this->R = \Pv\Misc::bcmod($RVB, 256) ;
		$this->G = \Pv\Misc::bcmod($RVB, 256*256) ;
		$this->B = \Pv\Misc::bcmod($RVB, 256*256*256) ;
	}
}