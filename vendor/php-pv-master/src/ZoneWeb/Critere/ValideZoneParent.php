<?php

namespace Pv\ZoneWeb\Critere ;

class ValideZoneParent extends \Pv\ZoneWeb\Critere\ValideRegexpForm
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} ne respectent pas les conditions de la zone' ;
	public function EstRespecte()
	{
		return $this->ZoneParent->ValideCritere($this, $this->ScriptParent) ;
	}
}