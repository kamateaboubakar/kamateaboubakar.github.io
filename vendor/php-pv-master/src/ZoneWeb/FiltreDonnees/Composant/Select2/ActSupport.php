<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant\Select2 ;

class ActSupport extends \Pv\ZoneWeb\Action\ResultatJson
{
	protected $TermeRech ;
	public $RechercheParDebut = true ;
	protected function ConstruitResultat()
	{
		$comp = & $this->ComposantRenduParent ;
		$fourn = & $this->ComposantRenduParent->FournisseurDonnees ;
		$this->Resultat = new Result() ;
		// print "hh : ".get_class($comp) ;
		if($this->EstNul($comp) || $comp->EstNul($fourn))
		{
			return ;
		}
		$this->TermeRech = (isset($_GET["q"])) ? $_GET["q"] : '' ;
		$filtres = $comp->ExtraitFiltresSelection($this->TermeRech) ;
		// $this->Resultat->total_count = $fourn->CompteElements(array(), $filtres) ;
		$colonnes = array($comp->NomColonneLibelle, $comp->NomColonneValeur) ;
		if(count($comp->NomsColonneExtra) > 0)
		{
			array_splice($colonnes, count($colonnes), 0, $comp->NomsColonneExtra) ;
		}
		if($this->RechercheParDebut == true)
		{
			$this->Resultat->items = $fourn->RechDebuteElements($filtres, $colonnes, $this->TermeRech) ;
		}
		else
		{
			$this->Resultat->items = $fourn->RechPartielleElements($filtres, $colonnes, $this->TermeRech) ;
		}
		if(count($this->Resultat->items) > $comp->MaxElemsParPage)
		{
			array_splice($this->Resultat->items, $comp->MaxElemsParPage) ;
		}
		$this->Resultat->total_count = count($this->Resultat->items) ;
		// print_r($fourn) ;
	}
}
