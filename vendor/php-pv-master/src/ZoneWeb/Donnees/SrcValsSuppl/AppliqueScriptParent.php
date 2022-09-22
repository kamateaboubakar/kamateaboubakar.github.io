<?php

namespace Pv\ZoneWeb\Donnees\SrcValsSuppl ;

class AppliqueScriptParent extends SrcValsSuppl
{
	public $InclureHtml = true ;
	public $InclureUrl = true ;
	public function Applique(& $composant, $ligneDonnees)
	{
		$ligneDonnees = parent::Applique($composant, $ligneDonnees) ;
		$ligneDonnees = $composant->ZoneParent->ScriptPourRendu->ExtraitSrcValsSuppl($ligneDonnees, $composant, $this) ;
		return $ligneDonnees ;
	}
}