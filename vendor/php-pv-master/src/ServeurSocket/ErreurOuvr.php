<?php

namespace Pv\ServeurSocket ;

class ErreurOuvr
{
	public $No ;
	public $Contenu ;
	public function Trouve()
	{
		return $this->No != '' ;
	}
}