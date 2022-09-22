<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneSelectBool extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteSelect
{
	public $SelectionStricte = true ;
	public $LibelleVrai = "" ;
	public $LibelleFaux = "" ;
	public $ValeurVrai = "" ;
	public $ValeurFaux = "" ;
	protected function EstValeurSelectionnee($valeur)
	{
		// print $this->IDInstanceCalc ;
		return (in_array($valeur, $this->ValeursSelectionnees, $this->SelectionStricte)) ? 1 : 0 ;
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->FournisseurDonnees = new \Pv\FournisseurDonnees\Booleen() ;
		if($this->ValeurVrai != "")
			$this->FournisseurDonnees->ValeurVrai = $this->ValeurVrai ;
		if($this->ValeurFaux != "")
			$this->FournisseurDonnees->ValeurFaux = $this->ValeurFaux ;
		if($this->LibelleVrai != "")
			$this->FournisseurDonnees->LibelleVrai = $this->LibelleVrai ;
		if($this->LibelleFaux != "")
			$this->FournisseurDonnees->LibelleFaux = $this->LibelleFaux ;
		$this->FournisseurDonnees->ChargeConfig() ;
		$this->FournisseurDonnees->RequeteSelection = $this->FournisseurDonnees->NomCleBool ;
		$this->NomColonneValeur = $this->FournisseurDonnees->NomAttributValeur ;
		$this->NomColonneLibelle = $this->FournisseurDonnees->NomAttributLibelle ;
	}
}