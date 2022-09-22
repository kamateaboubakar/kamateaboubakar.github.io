<?php

namespace Pv\ZoneBootstrap\DessinFiltres ;

class AppliqueZoneParent extends DessinFiltres
{
	public $NomMethodeScript ;
	public $MsgMethodeNonTrouvee = "La methode %s n'existe pas dans le script parent" ;
	public function Execute(& $script, & $composant, $parametres)
	{
		$nomMtd = ($this->NomMethodeScript != '') ? $this->NomMethodeScript : "DessineFiltres" ;
		if(method_exists($script, $nomMtd))
		{
			$ctn = call_user_func_array(array($script->ZoneParent, $nomMtd), array(& $composant, $parametres)) ;
		}
		else
		{
			$ctn = sprintf($this->MsgMethodeNonTrouvee, $nomMtd) ;
		}
		if($ctn == '')
		{
			$ctn = parent::Execute($script, $composant, $parametres) ;
		}
		return $ctn ;
	}
}