<?php

namespace Pv\ZoneWeb\PChart ;

class Ondulation extends \Pv\ZoneWeb\PChart\Diagramme
{
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawLineGraph($graphe->JeuDonnees->GetData(),$graphe->JeuDonnees->GetDataDescription());
	}
}