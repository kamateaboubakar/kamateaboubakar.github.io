<?php

namespace Pv\ZoneWeb\Donnees\SrcValsSuppl ;

class AppliqueZoneParent extends SrcValsSuppl
{
	public $InclureHtml = true ;
	public $InclureUrl = true ;
	public function Applique(& $composant, $ligneDonnees)
	{
		$ligneDonnees = parent::Applique($composant, $ligneDonnees) ;
		$ligneDonnees = $composant->ZoneParent->ExtraitSrcValsSuppl($ligneDonnees, $composant, $this) ;
		return $ligneDonnees ;
	}
}