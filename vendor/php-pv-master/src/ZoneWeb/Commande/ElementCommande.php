<?php

namespace Pv\ZoneWeb\Commande ;

class ElementCommande extends \Pv\ZoneWeb\ElementRendu\ElementRendu
{
	public $TypeElementCommande = "base" ;
	public $FiltresCibles = array() ;
	public $IndiceCommande = -1 ;
	public $CommandeParent = null ;
	public $ScriptParent = null ;
	public $ZoneParent = null ;
	public $ApplicationParent = null ;
	public $FormulaireDonneesParent = null ;
	public function AdopteCommande($indice, & $commande)
	{
		$this->IndiceCommande = $indice ;
		$this->CommandeParent = & $commande ;
		if($this->EstPasNul($commande->FormulaireDonneesParent))
		{
			$this->FormulaireDonneesParent = & $commande->FormulaireDonneesParent ;
			$this->ScriptParent = & $commande->FormulaireDonneesParent->ScriptParent ;
			$this->ZoneParent = & $commande->FormulaireDonneesParent->ZoneParent ;
			$this->ApplicationParent = & $commande->FormulaireDonneesParent->ApplicationParent ;
		}
		elseif($this->EstPasNul($commande->TableauDonneesParent))
		{
			$this->TableauDonneesParent = & $commande->TableauDonneesParent ;
			$this->ScriptParent = & $commande->TableauDonneesParent->ScriptParent ;
			$this->ZoneParent = & $commande->TableauDonneesParent->ZoneParent ;
			$this->ApplicationParent = & $commande->TableauDonneesParent->ApplicationParent ;
		}
	}
	protected function LieFiltresCibles()
	{
		$this->FormulaireDonneesParent->LieFiltres($this->FiltresCibles) ;
	}
	public function CibleTousFiltres()
	{
		if($this->EstNul($this->FormulaireDonneesParent))
		{
			return ;
		}
		$nomFiltres = array_keys($this->FormulaireDonneesParent->FiltresEdition) ;
		$this->FiltresCibles = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$this->FiltresCibles[] = & $this->FormulaireDonneesParent->FiltresEdition[$nomFiltre] ;
		}
	}
	public function CibleFiltres()
	{
		if($this->EstNul($this->FormulaireDonneesParent))
		{
			return ;
		}
		$args = func_get_args() ;
		// print_r($args) ;
		$nomFiltres = array_keys($this->FormulaireDonneesParent->FiltresEdition) ;
		// print_r($nomFiltres) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FormulaireDonneesParent->FiltresEdition[$nomFiltre] ;
			if(in_array($filtre->NomElementScript, $args) || in_array($nomFiltre, $args, true))
			{
				$this->FiltresCibles[] = & $filtre ;
			}
		}
	}
}
