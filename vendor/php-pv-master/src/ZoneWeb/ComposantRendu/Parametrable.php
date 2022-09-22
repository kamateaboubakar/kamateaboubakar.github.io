<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class Parametrable extends Donnees
{
	public function & CreeFiltreRef($nom, & $filtreRef)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Ref() ;
		$filtre->Source = & $filtreRef ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreFixe($nom, $valeur)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Fixe() ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ValeurParDefaut = $valeur ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreCookie($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Cookie() ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreSession($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Session() ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreMembreConnecte($nom, $nomParamDonnees='')
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Connecte() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nomParamDonnees ;
		return $filtre ;
	}
	public function & CreeFiltreHttpUpload($nom, $cheminDossierDest="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpUpload() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->CheminDossier = $cheminDossierDest ;
		return $filtre ;
	}
	public function & CreeFiltreHttpGet($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpGet() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreHttpPost($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpPost() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreHttpRequest($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpRequest() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function CreeFltRef($nom, & $filtreRef)
	{
		return $this->CreeFiltreRef($nom, $filtreRef) ;
	}
	public function CreeFltFixe($nom, $valeur)
	{
		return $this->CreeFiltreRef($nom, $valeur) ;
	}
	public function CreeFltCookie($nom)
	{
		return $this->CreeFiltreCookie($nom) ;
	}
	public function CreeFltSession($nom)
	{
		return $this->CreeFiltreSession($nom) ;
	}
	public function CreeFltMembreConnecte($nom, $nomParamLie='')
	{
		return $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
	}
	public function CreeFltHttpUpload($nom, $cheminDossierDest="")
	{
		return $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
	}
	public function CreeFltHttpGet($nom)
	{
		return $this->CreeFiltreHttpGet($nom) ;
	}
	public function CreeFltHttpPost($nom)
	{
		return $this->CreeFiltreHttpPost($nom) ;
	}
	public function CreeFltHttpRequest($nom)
	{
		return $this->CreeFiltreHttpRequest($nom) ;
	}
	public function ExtraitValeursParametre(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$valeurs = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtres[$nomFiltre] ;
			$filtre->Lie() ;
			$valeurs[$filtre->NomParametreDonnees] = (($filtre->ValeurParametre) != null) ? $filtre->ValeurParametre : '' ;
		}
		return $valeurs ;
	}
	public function ExtraitValeursParametreLie(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$valeurs = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtres[$nomFiltre] ;
			$valeurs[$filtre->NomParametreLie] = $filtre->Lie() ;
		}
		return $valeurs ;
	}
	public function ExtraitValeursColonneLiee(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$valeurs = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtres[$nomFiltre] ;
			$filtre->Lie() ;
			$valeurs[$filtre->NomColonneLiee] = $filtre->ValeurParametre ;
		}
		return $valeurs ;
	}
	public function ExtraitObjetParametre(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$obj = new StdClass() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtres[$nomFiltre] ;
			$nomProp = $filtre->NomParametreLie ;
			if($nomProp == '')
			{
				continue ;
			}
			$filtre->Lie() ;
			$obj->$nomProp = $filtre->ValeurParametre ;
		}
		return $obj ;
	}
	public function ExtraitObjetColonneLiee(& $filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$obj = new StdClass() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtres[$nomFiltre] ;
			$nomProp = $filtre->NomColonneLiee ;
			if($nomProp == '')
			{
				continue ;
			}
			$filtre->Lie() ;
			$obj->$nomProp = $filtre->ValeurParametre ;
		}
		return $obj ;
	}
	public function ObtientFiltre(& $filtres, $nomParamLie)
	{
	}
	public function CreeCmdRedirectUrl()
	{
		return new \Pv\ZoneWeb\Commande\RedirectionHttp() ;
	}
	public function CreeCmdRedirectScript()
	{
		return new \Pv\ZoneWeb\Commande\RedirectionHttp() ;
	}
	public function ExtraitFiltresDeRendu(& $filtres, $filtresCaches=array())
	{
		$resultats = array() ;
		foreach($filtres as $i => $filtre)
		{
			// print $i.'- '.$filtre->NomParametreLie.' '.$filtre->RenduPossible().'<br />' ;
			if($filtre->RenduPossible() && ! in_array($filtre->NomParametreLie, $filtresCaches))
			{
				$resultats[$i] = & $filtres[$i] ;
			}
		}
		return $resultats ;
	}
	public function ExtraitFiltresAffichables(& $filtres)
	{
		$resultats = array() ;
		foreach($filtres as $i => $filtre)
		{
			if($filtre->RenduPossible() && ! $filtre->LectureSeule)
			{
				$resultats[$i] = & $filtres[$i] ;
			}
		}
		return $resultats ;
	}
}
