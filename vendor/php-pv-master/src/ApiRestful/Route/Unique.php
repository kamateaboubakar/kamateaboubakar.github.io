<?php

namespace Pv\ApiRestful\Route ;

class Unique extends Element
{
	public $AutoriserSelect = 1 ;
	public $AutoriserAjout = 0 ;
	public $AutoriserModif = 0 ;
	public $AutoriserSuppr = 0 ;
	public $AutoriserDesact = 0 ;
	public $ValeursDesact = array() ;
	protected $ModeEdition = 0 ;
}