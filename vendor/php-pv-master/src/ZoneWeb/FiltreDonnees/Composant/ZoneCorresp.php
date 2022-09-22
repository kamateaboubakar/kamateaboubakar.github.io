<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneCorresp extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEtiquette
{
	public $NomColonneValeur ;
	public $NomColonneLibelle ;
	public $FournisseurDonnees ;
	public $LibelleNonTrouve = "" ;
	public $FiltresSelection = array() ;
	protected function CalculeLibelle()
	{
		$lignes = $this->FournisseurDonnees->RechExacteElements($this->FiltresSelection, $this->NomColonneValeur, $this->Valeur) ;
		// print_r($this->FournisseurDonnees) ;
		$etiquette = '' ;
        // print_r($lignes) ;
		if(is_array($lignes) && count($lignes) > 0)
		{
			$this->Libelle = $lignes[0][$this->NomColonneLibelle] ;
		}
		else
		{
			if($this->FournisseurDonnees->ExceptionTrouvee())
			{
				$this->Libelle = "Erreur : ".$this->DerniereException->Message ;
			}
			else
			{
				$this->Libelle = $this->LibelleNonTrouve ;
			}
		}
		return '<span id="'.$this->IDInstanceCalc.'">'.$etiquette.'</span>' ;
	}
	public function RenduEtiquette()
	{
		return $this->RenduDispositifBrut() ;
	}
	protected function RenduDispositifBrut()
	{
		$this->CalculeLibelle() ;
		$ctn = parent::RenduDispositifBrut() ;
		return $ctn ;
	}
}