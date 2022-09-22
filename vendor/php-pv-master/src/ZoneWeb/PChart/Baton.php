<?php

namespace Pv\ZoneWeb\PChart ;

class Baton extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawCubicCurve(
			$graphe->JeuDonnees->GetData(),
			$graphe->JeuDonnees->GetDataDescription()
		);
	}
}