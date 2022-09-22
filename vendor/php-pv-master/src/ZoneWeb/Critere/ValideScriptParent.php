<?php

namespace Pv\ZoneWeb\Critere ;

class ValideScriptParent extends \Pv\ZoneWeb\Critere\ValideRegexpForm
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} ne respectent pas les conditions du script' ;
	public function EstRespecte()
	{
		return $this->ScriptParent->ValideCritere($this) ;
	}
}