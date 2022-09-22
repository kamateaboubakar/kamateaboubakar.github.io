<?php

namespace Pv\ZoneWeb\PChart ;

class LigneRemplie extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawFilledLineGraph($graphe->JeuDonnees->GetData(),$graphe->JeuDonnees->GetDataDescription(), 50, TRUE);
	}
}