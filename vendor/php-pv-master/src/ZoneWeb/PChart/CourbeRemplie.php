<?php

namespace Pv\ZoneWeb\PChart ;

class CourbeRemplie extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $InscrirePointilles = 0 ;
	public $Precision = 0.1 ;
	public $ValeurAlpha = 60 ;
	public $AutourZero = false ;
	protected function AppliqueRendu(& $graphe)
	{
		$graphe->Support->drawFilledCubicCurve(
			$graphe->JeuDonnees->GetData(),
			$graphe->JeuDonnees->GetDataDescription(),
			$this->Precision,
			$this->ValeurAlpha,
			$this->AutourZero
		);
	}
}