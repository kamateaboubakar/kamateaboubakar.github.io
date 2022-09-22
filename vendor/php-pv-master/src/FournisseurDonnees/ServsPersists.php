<?php

namespace Pv\FournisseurDonnees ;

class ServsPersists extends \Pv\FournisseurDonnees\Direct
{
	public $Application ;
	public function ExtraitValeursServPersist(& $servPersist)
	{
		$valeurs = array() ;
		$valeurs["titre"] = $servPersist->ObtientTitre() ;
		$valeurs["chemin_fichier_relatif"] = $servPersist->CheminFichierRelatif ;
		$valeurs["arguments"] = \Pv\Application\Application::EncodeArgsShell($servPersist->ArgsParDefaut) ;
		$valeurs["script"] = $valeurs["chemin_fichier_relatif"].' '.$valeurs["arguments"] ;
		$valeurs["nature_plateforme"] = $servPersist->NaturePlateforme ;
		$valeurs["verifie"] = ($servPersist->Verifie()) ? 1 : 0 ;
		$valeurs["est_service_demarre"] = ($servPersist->EstServiceDemarre()) ? 1 : 0 ;
		return $valeurs ;
	}
	public function SelectElements($colonnes, $filtres, $indiceColonneTri=0, $sensColonneTri="asc")
	{
		$listeColonnes = $this->ExtraitListeColonnes($colonnes) ;
		$resultats = array() ;
		foreach($this->Application->ServsPersists as $i => $element)
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
}