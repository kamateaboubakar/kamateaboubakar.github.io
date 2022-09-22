<?php

namespace Pv\ZoneWeb\ArborescenceDonnees ;

class DefNoeud extends \Pv\ZoneWeb\TableauDonnees\Colonne
{
	public $TriPrealable = 1 ;
	public $TriPossible = 0 ;
	public $RequeteSelection = '' ;
	public $ExpressionLiaison = '' ;
	public $ValeurEnCours = false ;
	public $ComposantFin ;
	public $ComposantVide ;
	public $ComposantDebut ;
	public $RenduDebutParDefaut = '<li>${VALEUR_ACTUELLE}<ul>' ;
	public $RenduVideParDefaut = '' ;
	public $RenduFinParDefaut = '</ul></li>' ;
	public $IndLgnActuelle ;
	public $IndColActuelle ;
	public $LigneActuelle ;
	public $ArborescenceActuelle ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		/*
		$this->ComposantDebut = new \Pv\ZoneWeb\ArborescenceDonnees\NÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ud() ;
		$this->ComposantVide = new \Pv\ZoneWeb\ArborescenceDonnees\NÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ud() ;
		$this->ComposantFin = new \Pv\ZoneWeb\ArborescenceDonnees\NÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã¢â‚¬Å“ud() ;
		*/
	}
	protected function PrepareRendu(& $arbr, $indLgn, $indCol, $donnees)
	{
		$this->ArborescenceActuelle = $arbr ;
		$this->IndLgnActuelle = $indLgn ;
		$this->IndColActuelle = $indCol ;
		$this->LigneActuelle = $donnees ;
	}
	public function RenduComposantDebut(& $arbr, $indLgn, $indCol, $donnees)
	{
		$this->PrepareRendu($arbr, $indLgn, $indCol, $donnees) ;
		if($this->EstNul($this->ComposantDebut))
			return \Pv\Misc::_parse_pattern($this->RenduDebutParDefaut, $donnees) ;
		return $this->ComposantDebut->RenduDispositif() ;
	}
	public function RenduComposantVide(& $arbr, $indLgn, $indCol, $donnees)
	{
		$this->PrepareRendu($arbr, $indLgn, $indCol, $donnees) ;
		if($this->EstNul($this->ComposantVide))
			return \Pv\Misc::_parse_pattern($this->RenduVideParDefaut, $donnees) ;
		return $this->ComposantVide->RenduDispositif() ;
	}
	public function RenduComposantFin(& $arbr, $indLgn, $indCol, $donnees)
	{
		$this->PrepareRendu($arbr, $indLgn, $indCol, $donnees) ;
		if($this->EstNul($this->ComposantFin))
			return \Pv\Misc::_parse_pattern($this->RenduFinParDefaut, $donnees) ;
		return $this->ComposantFin->RenduDispositif() ;
	}
}