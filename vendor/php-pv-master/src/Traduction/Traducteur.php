<?php

namespace Pv\Traduction ;

class Traducteur extends \Pv\Objet\Objet
{
	public $Exprs = array() ;
	public $IdLangue = 0 ;
	public $NomLangue = "" ;
	public $LibelleLangue = "" ;
	public $EstNul = 0 ;
	public function Execute($nomExpr, $params=array(), $valParDefaut='')
	{
		$val = $valParDefaut ;
		if(isset($this->Exprs[$nomExpr]))
		{
			$val = \Pv\Misc::_parse_pattern($this->Exprs[$nomExpr], $params) ;
		}
		return $val ;
	}
}