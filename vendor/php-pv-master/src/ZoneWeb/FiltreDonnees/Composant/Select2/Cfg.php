<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant\Select2 ;

class Cfg
{
	public $ajax ;
	public $language = "fr" ;
	public $escapeMarkup ;
	public $minimumInputLength = 1 ;
	public $placeholder = "" ;
	public $allowClear = true ;
	public $data = true ;
	public $tags = false ;
	public $tokenSeparators = array() ;
	public function __construct()
	{
		$this->ajax = new CfgAjax() ;
	}
}
