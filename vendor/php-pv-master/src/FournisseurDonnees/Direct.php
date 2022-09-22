<?php

namespace Pv\FournisseurDonnees ;

class Direct extends \Pv\FournisseurDonnees\Natif
{
	public $Valeurs = array() ;
	public $UtiliserPremValeurSiVide = 1 ;
	public static function CreePourValeurs($valeurs=array(), $reqSelection='valeurs')
	{
		$fournisseur = new \Pv\FournisseurDonnees\Direct() ;
		if(empty($reqSelect))
			$reqSelect = 'valeurs' ;
		$fournisseur->RequeteSelection = $reqSelect ;
		$fournisseur->Valeurs[$reqSelect] = $valeurs ;
		return $fournisseur ;
	}
	protected function ExtraitListeColonnes($colonnes)
	{
		$liste = array() ;
		foreach($colonnes as $i => $colonne)
		{
			if($colonne->NomDonnees != '')
			{
				$liste[] = $colonne->NomDonnees ;
			}
		}
		if(count($colonnes) == 0)
		{
			$liste[] = "*" ;
		}
		return $liste ;
	}
	public function RechExacteElements($filtres, $nomColonne, $valeur)
	{
		$lignes = array() ;
		if($this->RequeteSelection == "")
		{
			$nomValeurs = array_keys($this->Valeurs) ;
			if(count($nomValeurs))
				$this->RequeteSelection = $nomValeurs[0] ;
		}
		if(! isset($this->Valeurs[$this->RequeteSelection]))
		{
			return array() ;
		}
		$valeursSel = $this->Valeurs[$this->RequeteSelection] ;
		foreach($valeursSel as $i => $ligneTemp)
		{
			if(! isset($ligneTemp[$nomColonne]))
				break ;
			if($ligneTemp[$nomColonne] == $valeur)
			{
				$lignes[] = $ligneTemp ;
			}
		}
		return $lignes ;
	}
	public function SelectElements($colonnes, $filtres, $indiceColonneTri=0, $sensColonneTri="asc")
	{
		if($this->RequeteSelection == "")
		{
			$nomValeurs = array_keys($this->Valeurs) ;
			if(count($nomValeurs))
				$this->RequeteSelection = $nomValeurs[0] ;
		}
		if(! isset($this->Valeurs[$this->RequeteSelection]))
		{
			return array() ;
		}
		$listeColonnes = $this->ExtraitListeColonnes($colonnes) ;
		$resultats = array() ;
		foreach($this->Valeurs[$this->RequeteSelection] as $i => $element)
		{
			$resultat = array() ;
			foreach($listeColonnes as $i => $colonne)
			{
				if($colonne == "*")
				{
					$resultat = $element ;
					break ;
				}
				if(isset($element[$colonne]))
				{
					$resultat[$colonne] = $element[$colonne] ;
				}
			}
			$resultats[] = $resultat ;
		}
		return $resultats ;
	}
	public function RangeeElements($colonnes, $filtres, $indiceDebut=0, $maxElements=100, $indiceColonneTri=0, $sensColonneTri="asc")
	{
		if($this->RequeteSelection == "")
		{
			$nomValeurs = array_keys($this->Valeurs) ;
			if(count($nomValeurs))
				$this->RequeteSelection = $nomValeurs[0] ;
		}
		if(! isset($this->Valeurs[$this->RequeteSelection]))
		{
			return array() ;
		}
		$listeColonnes = $this->ExtraitListeColonnes($colonnes) ;
		$resultats = array() ;
		foreach($this->Valeurs[$this->RequeteSelection] as $i => $element)
		{
			if($i < $indiceDebut || $i >= $indiceDebut + $maxElements)
				continue ;
			$resultat = array() ;
			foreach($listeColonnes as $i => $colonne)
			{
				if($colonne == "*")
				{
					$resultat = $element ;
					break ;
				}
				// print_r($element[$colonne]) ;
				if(isset($element[$colonne]))
				{
					$resultat[$colonne] = $element[$colonne] ;
				}
			}
			$resultats[] = $resultat ;
		}
		return $resultats ;
	}
	public function CompteElements($colonnes, $filtres)
	{
		if($this->RequeteSelection == "")
		{
			$nomValeurs = array_keys($this->Valeurs) ;
			if(count($nomValeurs))
				$this->RequeteSelection = $nomValeurs[0] ;
		}
		if(! isset($this->Valeurs[$this->RequeteSelection]))
		{
			return 0 ;
		}
		return count($this->Valeurs[$this->RequeteSelection]) ;
	}
	public function LigneElement($colonnes, $filtresGlobaux, $filtresLigne, $indiceColonneTri=0, $sensColonneTri="asc")
	{
	}
	public function AjoutElement($filtresEdition)
	{
	}
	public function ModifElement($filtresSelection, $filtresEdition)
	{
	}
	public function SupprElement($filtresSelection)
	{
	}
	public function OuvreRequeteSelectElements($filtres, $colonnes=array())
	{
		$requete = false ;
		if(! isset($this->Valeurs[$this->RequeteSelection]))
		{
			return $requete ;
		}
		// print_r($this->Valeurs) ;
		$requete = new \Pv\FournisseurDonnees\Requete() ;
		$requete->RessourceSupport = $this->RequeteSelection ;
		return $requete ;
	}
	public function LitRequete(& $requete)
	{
		if($requete == false or $requete->RessourceSupport == false)
			return false ;
		$ligne = false ;
		if(isset($this->Valeurs[$requete->RessourceSupport]) && isset($this->Valeurs[$requete->RessourceSupport][$requete->Position]))
		{
			$ligne = $this->Valeurs[$requete->RessourceSupport][$requete->Position] ;
		}
		$requete->Position++ ;
		return $ligne ;
	}
	public function FermeRequete(& $requete)
	{
		if($requete == false or $requete->RessourceSupport == false)
			return false ;
		$requete->RessourceSupport = "" ;
		return true ;
	}
}