<?php

namespace Pv\ApiRestful\Colonne ;

class SrcValsSuppl
{
	public $LignesDonneesBrutes ;
	public function Applique(& $composant, $ligneDonnees)
	{
		return $ligneDonnees ;
	}
}