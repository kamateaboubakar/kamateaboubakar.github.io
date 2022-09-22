<?php

namespace Pv\ZoneWeb\PChart ;

class LigneLimitee extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawLimitsGraph($graphe->JeuDonnees->GetData(),$graphe->JeuDonnees->GetDataDescription(),3,2, 255, 255, 255);
	}
}