<?php

namespace Pv\ZoneWeb\PChart ;

class BarreBandes extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	public $UtiliserOmbre = true ;
	public $ValeurOmbre = 80 ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawBarGraph($graphe->JeuDonnees->GetData(),$graphe->JeuDonnees->GetDataDescription(), $this->UtiliserOmbre, $this->ValeurOmbre);
	}
}