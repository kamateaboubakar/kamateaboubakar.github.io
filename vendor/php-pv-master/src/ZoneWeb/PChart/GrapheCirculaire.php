<?php

namespace Pv\ZoneWeb\PChart ;

class GrapheCirculaire extends \Pv\ZoneWeb\PChart\Diagramme
{
	public $QualiteAntiAlias = 0 ;
	public $TypeLibelle = PIE_PERCENTAGE_LABEL ;
	public $MargeRayon = 60 ;
	public $ValeurOblique = 50 ;
	public $ValeurEpaisseur = 20 ;
	public $TotalDecimaux = 0 ;
	public $DistanceEpaisseur = 5 ;
	public $AccentuerCouleurs = false ;
	public function Applique(& $graphe)
	{
		$this->CommenceRendu($graphe) ;
		$this->AppliqueRendu($graphe) ;
		$this->TermineRendu($graphe) ;
	}
	protected function CommenceRendu(& $graphe)
	{
		$cheminPolice = $this->RecupCheminPolice($graphe) ;
		$graphe->Support->createColorGradientPalette(195,204,56,223,110,41,5);
	}
	protected function AppliqueRendu(& $graphe)
	{
		$ancRapportErr = error_reporting(0);
		$cheminPolice = $this->RecupCheminPolice($graphe) ;
		$graphe->Support->setFontProperties($cheminPolice, $graphe->TaillePoliceEtiquette);
		$rayon = $this->ExtraitRayon($graphe) ;
		$graphe->Support->AntialiasQuality = $this->QualiteAntiAlias;
		$graphe->Support->drawPieGraph(
			$graphe->JeuDonnees->GetData(), $graphe->JeuDonnees->GetDataDescription(),
			$graphe->MargeGaucheForme + $rayon + ($this->MargeRayon / 2),
			$graphe->MargeHautForme + $rayon,
			$rayon,
			$this->TypeLibelle, $this->AccentuerCouleurs, $this->ValeurOblique, $this->ValeurEpaisseur, $this->DistanceEpaisseur, $this->TotalDecimaux
		);
		$graphe->Support->setFontProperties($cheminPolice, $graphe->TaillePoliceEtiquette);
		$graphe->Support->drawPieLegend($graphe->Largeur - $graphe->MargeDroiteForme - 100, $graphe->MargeHautForme + 20, $graphe->JeuDonnees->GetData(), $graphe->JeuDonnees->GetDataDescription(), 250,250,250);
		error_reporting($ancRapportErr) ;
	}
	protected function ExtraitRayon(& $graphe)
	{
		$largeurTravail = $graphe->Largeur - ($graphe->MargeDroiteForme + $graphe->MargeGaucheForme) ;
		$hauteurTravail = $graphe->Hauteur - ($graphe->MargeHautForme + $graphe->MargeBasForme) ;
		return intval((($largeurTravail < $hauteurTravail) ? $largeurTravail - $this->MargeRayon : $hauteurTravail - $this->MargeRayon) / 2) ;
	}
	protected function TermineRendu(& $graphe)
	{
		$cheminPolice = $this->RecupCheminPolice($graphe) ;
	}
}