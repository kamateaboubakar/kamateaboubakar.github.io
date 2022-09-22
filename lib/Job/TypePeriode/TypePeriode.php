<?php

namespace Rpa2p\Job\TypePeriode ;

class TypePeriode
{
	public function Id()
	{
		return 'base' ;
	}
	public function Titre()
	{
		return 'Base' ;
	}
	public function TitreParam(& $lgn)
	{
		return $this->Titre() ;
	}
	public function RemplitFormEdit(& $form)
	{
	}
	public function AppliqueActCmdEdit(& $actCmd)
	{
	}
	public function ValideCritrEdit(& $critere)
	{
	}
	protected function & InstalleCompMinutages(& $flt, $autoriseTous=true)
	{
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect()) ;
		$comp->FournisseurDonnees = new \Pv\FournisseurDonnees\Direct() ;
		$comp->FournisseurDonnees->Valeurs["minutages"] = array() ;
		$ecart = 60 / \Rpa2p\Config\ExecActivites::MINUTAGES ;
		for($i=0; $i<\Rpa2p\Config\ExecActivites::MINUTAGES; $i++)
		{
			$mnDebut = ($i * $ecart) ;
			$mnFin = ($i * $ecart) + $ecart - 1 ;
			$comp->FournisseurDonnees->Valeurs["minutages"][] = array(
				"id" => $i + 1,
				"titre" => $mnDebut." - ".$mnFin." mn",
			) ;
		}
		if($autoriseTous == true)
		{
			$comp->InclureElementHorsLigne = true ;
			$comp->LibelleElementHorsLigne = "(tous)" ;
		}
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		return $comp ;
	}
	protected function & InstalleCompJoursMois(& $flt, $autoriseTous=true)
	{
		$comp = $flt->RemplaceComposant(new \Pv\ZoneBootstrap\FiltreDonnees\Composant\ZoneBoiteOptionsCocher()) ;
		$comp->FournisseurDonnees = new \Pv\FournisseurDonnees\Direct() ;
		$comp->FournisseurDonnees->Valeurs["jours"] = array() ;
		for($i=0; $i<31; $i++)
		{
			$val = ($i + 1 < 10) ? '0'.($i + 1) : $i + 1 ;
			$comp->FournisseurDonnees->Valeurs["jours"][] = array(
				"id" => $val,
				"titre" => $val,
			) ;
		}
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		return $comp ;
	}
	protected function & InstalleCompHeures(& $flt, $autoriseTous=true)
	{
		$comp = $flt->RemplaceComposant(new \Pv\ZoneBootstrap\FiltreDonnees\Composant\ZoneBoiteOptionsCocher()) ;
		$comp->FournisseurDonnees = new \Pv\FournisseurDonnees\Direct() ;
		$comp->FournisseurDonnees->Valeurs["heures"] = array() ;
		for($i=0; $i<24; $i++)
		{
			$val = ($i < 10) ? '0'.$i : $i ;
			$comp->FournisseurDonnees->Valeurs["heures"][] = array(
				"id" => $val,
				"titre" => $val,
			) ;
		}
		$comp->TransmettreTableauValeurs = false ;
		$comp->SeparateurValeurs = ", " ;
		$comp->MaxColonnesParLigne = 8 ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		return $comp ;
	}
	protected function & InstalleCompJours(& $flt, $autoriseTous=true)
	{
		$comp = $flt->RemplaceComposant(new \Pv\ZoneBootstrap\FiltreDonnees\Composant\ZoneBoiteOptionsCocher()) ;
		$comp->FournisseurDonnees = new \Pv\FournisseurDonnees\Direct() ;
		$comp->FournisseurDonnees->Valeurs["jours"] = array(
			array('id' => 0, 'titre' => 'Lundi'),
			array('id' => 1, 'titre' => 'Mardi'),
			array('id' => 2, 'titre' => 'Mercredi'),
			array('id' => 3, 'titre' => 'Jeudi'),
			array('id' => 4, 'titre' => 'Vendredi'),
			array('id' => 5, 'titre' => 'Samedi'),
			array('id' => 6, 'titre' => 'Dimanche'),
		) ;
		$comp->TransmettreTableauValeurs = false ;
		$comp->SeparateurValeurs = ", " ;
		$comp->MaxColonnesParLigne = 4 ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		return $comp ;
	}
	public function CondExec($aliasTable)
	{
		return "1=0" ;
	}
}
