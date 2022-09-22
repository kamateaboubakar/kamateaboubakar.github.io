<?php

namespace Pv\ZoneWeb\PChart ;

class BandesEmpilees extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	public $ValeurAlpha = 100 ;
	public $Continu = false ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawStackedBarGraph($graphe->JeuDonnees->GetData(),$graphe->JeuDonnees->GetDataDescription(), $this->ValeurAlpha, $this->Continu);
	}
}