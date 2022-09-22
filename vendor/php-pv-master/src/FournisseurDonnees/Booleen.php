<?php

namespace Pv\FournisseurDonnees ;

class Booleen extends \Pv\FournisseurDonnees\Direct
{
	public $NomCleBool = "Bool" ;
	public $ValeurVrai = "1" ;
	public $ValeurFaux = "0" ;
	public $LibelleVrai = "Oui" ;
	public $LibelleFaux = "Non" ;
	public $NomAttributValeur = "Valeur" ;
	public $NomAttributLibelle = "Libelle" ;
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->RemplitValeursBool() ;
	}
	protected function RemplitValeursBool()
	{
		$this->Valeurs[$this->NomCleBool] = array(
			array(
				$this->NomAttributLibelle => $this->LibelleVrai,
				$this->NomAttributValeur => $this->ValeurVrai,
			),
			array(
				$this->NomAttributLibelle => $this->LibelleFaux,
				$this->NomAttributValeur => $this->ValeurFaux,
			),
		) ;
	}
	public static function Cree($valeurVrai=null, $libelleVrai=null, $valeurFaux=null, $libelleFaux=null)
	{
		$fournisseur = new \Pv\FournisseurDonnees\Bool() ;
		if($valeurVrai !== null)
			$fournisseur->ValeurVrai = $valeurVrai ;
		if($libelleVrai !== null)
			$fournisseur->LibelleVrai = $libelleVrai ;
		if($valeurFaux !== null)
			$fournisseur->ValeurFaux = $valeurFaux ;
		if($libelleFaux !== null)
			$fournisseur->LibelleFaux = $libelleFaux ;
		return $fournisseur ;
	}
}