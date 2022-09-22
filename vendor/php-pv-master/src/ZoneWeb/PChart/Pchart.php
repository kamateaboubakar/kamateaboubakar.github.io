<?php

namespace Pv\ZoneWeb\PChart ;

class Pchart extends \Pv\ZoneWeb\ComposantRendu\ComposantDonneesSimple
{
	public $InclureTitre = 1 ;
	public $Titre = "Statistiques" ;
	public $InclureArrPlan = 1 ;
	public $ArrPlan ;
	public $InclureLegende = 1 ;
	public $Legende ;
	public $Forme ;
	public $Support ;
	public $DonneesSupport ;
	public $JeuDonnees ;
	public $ActionImage ;
	public $NomActionImage ;
	public $Largeur = 750 ;
	public $Hauteur = 525 ;
	public $MargeGaucheForme = 100 ;
	public $MargeDroiteForme = 115 ;
	public $MargeHautForme = 30 ;
	public $MargeBasForme = 20 ;
	public $NomFichierPolice = "tahoma.ttf" ;
	public $TaillePoliceLegende = 8 ;
	public $TaillePoliceDonnees = 4 ;
	public $TaillePoliceEtiquette = 8 ;
	public $TaillePoliceTitre = 12 ;
	public $TaillePoliceEtiquetteSerie = 6 ;
	public $TailleCourbeArrPlan = 7 ;
	public $DefinitionsSeries = array() ;
	public $Points = array() ;
	public $FiltresSelection = array() ;
	public $Abcisse = null ;
	public function InitConfig()
	{
		parent::InitConfig() ;
		$this->Abcisse = new \Pv\ZoneWeb\PChart\Abscisse() ;
		$this->Forme = $this->CreeForme() ;
	}
	protected function CreeForme()
	{
		return new \Pv\ZoneWeb\PChart\Ondulation() ;
	}
	public function & InsereDefSerie($nomDonnees, $libelle="")
	{
		$defSerie = new \Pv\ZoneWeb\PChart\DefSerie() ;
		$defSerie->NomDonnees = $nomDonnees ;
		$defSerie->Libelle = $libelle ;
		$this->DefinitionsSeries[] = & $defSerie ;
		return $defSerie ;
	}
	public function & InsereDefinitionSerie($nomDonnees, $libelle="")
	{
		$defSerie = $this->InsereDefSerie($nomDonnees, $libelle) ;
		return $defSerie ;
	}
	public function AdopteScript($nom, & $script)
	{
		parent::AdopteScript($nom, $script) ;
		$this->NomActionImage = $this->IDInstanceCalc.'_Image' ;
		$this->ActionImage = new \Pv\ZoneWeb\PChart\ActionImage() ;
		$this->InscritActionAvantRendu($this->NomActionImage, $this->ActionImage) ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= '<img' ;
		$ctn .= ' src="'.$this->ActionImage->ObtientUrl().'"' ;
		if($this->Largeur != '')
		{
			$ctn .= ' width="'.$this->Largeur.'"' ;
		}
		if($this->Hauteur != '')
		{
			$ctn .= ' height="'.$this->Hauteur.'"' ;
		}
		$ctn .= ' />' ;
		return $ctn ;
	}
	protected function CalculeJeuDonnees()
	{
		$this->JeuDonnees = new pData() ;
		$this->Points = array() ;
		if($this->FournisseurDonnees == null)
			return ;
		$this->DonneesSupport = $this->FournisseurDonnees->SelectElements(array(), $this->FiltresSelection) ;
		if($this->DonneesSupport == null)
		{
			return ;
		}
		foreach($this->DefinitionsSeries as $j => $defSerie)
		{
			$this->DefinitionsSeries[$j]->IndexChart = $j ;
			$this->Points[$j] = array() ;
		}
		foreach($this->DonneesSupport as $i => $ligne)
		{
			foreach($this->DefinitionsSeries as $j => $defSerie)
			{
				if($defSerie->NomDonnees != '' && isset($ligne[$defSerie->NomDonnees]))
				{
					$this->Points[$j][] = $ligne[$defSerie->NomDonnees] ;
				}
				else
				{
					$this->Points[$j][] = 0 ;
				}
			}
		}
		foreach($this->Points as $i => $point)
		{
			$this->JeuDonnees->AddPoint($point, "Serie".($i + 1));
		}
		// Dataset definition
		$this->JeuDonnees->AddAllSeries();
		$this->JeuDonnees->SetAbsciseLabelSerie();
		foreach($this->DefinitionsSeries as $j => $defSerie)
		{
			$this->JeuDonnees->SetSerieName($defSerie->ObtientLibelle(), "Serie".($j + 1));
		}
	}
	public function EnvoieImage()
	{
		$this->CalculeJeuDonnees() ;
		$cheminPolice = CHEM_REP_PCHART."/Fonts/".$this->NomFichierPolice ;

		// Initialise the graph
		$this->Support = new pChart($this->Largeur, $this->Hauteur);
		if($this->Abcisse->ValeurMin !== null && $this->Abcisse->ValeurMax !== null)
		{
			$this->Support->setFixedScale($this->Abcisse->ValeurMin, $this->Abcisse->ValeurMax);
		}
		
		$this->Support->setFontProperties($cheminPolice, $this->TaillePoliceTitre);
		$this->Support->setGraphArea($this->MargeGaucheForme, $this->MargeHautForme, $this->Largeur - $this->MargeDroiteForme, $this->Hauteur - $this->MargeBasForme);
		$this->Support->drawFilledRoundedRectangle($this->TailleCourbeArrPlan, $this->TailleCourbeArrPlan, $this->Largeur - $this->TailleCourbeArrPlan, $this->Hauteur - $this->TailleCourbeArrPlan, 5, 240, 240, 240);
		$this->Support->drawRoundedRectangle($this->TailleCourbeArrPlan - 2, $this->TailleCourbeArrPlan - 2, $this->Largeur + 2 - $this->TailleCourbeArrPlan, $this->Hauteur + 2 - $this->TailleCourbeArrPlan, 5, 230, 230, 230);
		$this->Support->drawGraphArea(255, 255, 255, TRUE) ;
		
		// Draw the cubic curve graph
		if($this->Forme != null && count($this->Points) > 0)
		{
			$this->Forme->Applique($this) ;
			$this->Support->setFontProperties($cheminPolice, $this->TaillePoliceEtiquetteSerie);
			foreach($this->DefinitionsSeries as $i => $defSerie)
			{
				$this->AppliqueEtiquetteSerie($defSerie) ;
			}
		}
		
		// Finish the graph
		if(count($this->Points) > 0)
		{
			$this->Support->setFontProperties($cheminPolice, $this->TaillePoliceLegende);
			$this->Support->drawLegend($this->Largeur - 100, 30, $this->JeuDonnees->GetDataDescription(), 255, 255, 255) ;
		}
		
		$this->Support->setFontProperties($cheminPolice, $this->TaillePoliceTitre);
		$this->Support->drawTitle(50, 22, $this->Titre, 50, 50, 50, $this->Largeur) ;
		//$this->Support->Render("example2.png");
		$this->Support->Stroke();
	}
	protected function AppliqueEtiquetteSerie(& $defSerie)
	{
		if($defSerie->EtiquetteDonnees == "")
		{
			return ;
		}
		foreach($this->DonneesSupport as $i => $ligne)
		{
			if(! isset($ligne[$defSerie->EtiquetteDonnees]))
			{
				break ;
			}
			$etiq = $ligne[$defSerie->EtiquetteDonnees] ;
			if($etiq != "")
			{
				$index = $defSerie->IndexChart + 1 ;
				$this->Support->setLabel($this->JeuDonnees->GetData(),$this->JeuDonnees->GetDataDescription(),"Serie".$index, $i, $etiq, 221,230,174);
			}
		}
	}
}