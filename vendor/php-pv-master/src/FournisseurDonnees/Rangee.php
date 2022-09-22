<?php

namespace Pv\FournisseurDonnees ;

class Rangee extends \Pv\FournisseurDonnees\Direct
{
	public $NomCleRangee = "Rangee" ;
	public $ValeurMin = 0 ;
	public $ValeurMax = 0 ;
	public $NomAttributValeur = "Valeur" ;
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$vals = array() ;
		for($i=$this->ValeurMin; $i <= $this->ValeurMax; $i++)
		{
			$vals[] = array($this->NomAttributValeur => $i) ;
		}
		$this->Valeurs[$this->NomCleRangee] = $vals ;
	}
	public static function Cree($valeurMin, $valeurMax)
	{
		$fournisseur = new \Pv\FournisseurDonnees\Rangee() ;
		$fournisseur->ValeurMin = $valeurMin ;
		$fournisseur->ValeurMax = $valeurMax ;
		return $fournisseur ;
	}
}