<?php

namespace Pv\ZoneWeb\PChart ;

class Diagramme extends \Pv\ZoneWeb\PChart\Forme
{
	public $InscrirePointilles = 1 ;
	public $ConfigEchelle ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->ConfigEchelle = new \Pv\ZoneWeb\PChart\CfgEchelle() ;
	}
	public function Applique(& $graphe)
	{
		$this->CommenceRendu($graphe) ;
		$this->AppliqueRendu($graphe) ;
		$this->TermineRendu($graphe) ;
	}
	protected function CommenceRendu(& $graphe)
	{
		$cheminPolice = $this->RecupCheminPolice($graphe) ;
		
		$graphe->Support->drawScale(
			$graphe->JeuDonnees->GetData(),
			$graphe->JeuDonnees->GetDataDescription(),
			$this->ConfigEchelle->Mode,
			150, 150, 150,
			$this->ConfigEchelle->InclureMarques,
			$this->ConfigEchelle->Angle,
			$this->ConfigEchelle->TotalDecimaux,
			$this->ConfigEchelle->AvecMarge,
			$this->ConfigEchelle->AnnulerEtiquettes,
			$this->ConfigEchelle->PositionDroite
		);
		$graphe->Support->drawGrid(4, TRUE, 230, 230, 230, 50);
		// Draw the 0 line
		$graphe->Support->setFontProperties($cheminPolice, $graphe->TaillePoliceDonnees);
		$graphe->Support->drawTreshold(0, 143, 55, 72, TRUE, TRUE);
	}
	protected function AppliqueRendu(& $graphe)
	{
	}
	protected function TermineRendu(& $graphe)
	{
		$cheminPolice = $this->RecupCheminPolice($graphe) ;
		if($this->InscrirePointilles)
		{
			$graphe->Support->drawPlotGraph($graphe->JeuDonnees->GetData(), $graphe->JeuDonnees->GetDataDescription());
		}
	}
}