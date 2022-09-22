<?php

namespace Pv\ApiRestful\Route ;

class Donnees extends \Pv\ApiRestful\Route\Route
{
	public $FournisseurDonnees ;
	public $MessageErreurExecution ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->FournisseurDonnees = new \Pv\FournisseurDonnees\Sql() ;
	}
	public function AdopteApi($nom, $cheminRoute, &$api)
	{
		parent::AdopteApi($nom, $cheminRoute, $api) ;
		$this->FournisseurDonnees->BaseDonnees = $api->CreeBDPrinc() ;
	}
	public function & CreeFiltreRef($nom, & $filtreRef)
	{
		$filtre = new \Pv\ApiRestful\Filtre\Ref() ;
		$filtre->Source = & $filtreRef ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreFixe($nom, $valeur)
	{
		$filtre = new \Pv\ApiRestful\Filtre\Fixe() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ValeurParDefaut = $valeur ;
		$filtre->AdopteRoute($nom, $this) ;
		return $filtre ;
	}
	public function & CreeFiltreCookie($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\Cookie() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteRoute($nom, $this) ;
		return $filtre ;
	}
	public function & CreeFiltreSession($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\Session() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteRoute($nom, $this) ;
		return $filtre ;
	}
	public function & CreeFiltreMembreConnecte($nom, $nomParamLie='')
	{
		$filtre = new \Pv\ApiRestful\Filtre\MembreConnecte() ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->NomParametreLie = $nomParamLie ;
		return $filtre ;
	}
	public function & CreeFiltreHttpUpload($nom, $cheminDossierDest="")
	{
		$filtre = new \Pv\ApiRestful\Filtre\HttpUpload() ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->CheminDossier = $cheminDossierDest ;
		return $filtre ;
	}
	public function & CreeFiltreHttpGet($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\HttpGet() ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreHttpPost($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\HttpPost() ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreHttpCorps($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\HttpCorps() ;
		$filtre->AdopteRoute($nom, $this) ;
		$filtre->NomParametreLie = $nom ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreHttpRequest($nom)
	{
		$filtre = new \Pv\ApiRestful\Filtre\HttpRequest() ;
		$filtre->AdopteRoute($nom, $this) ;
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
	public function AlerteExceptionFournisseur()
	{
		$this->RenseigneException($this->FournisseurDonnees->MessageException()) ;
	}
	protected function ValideFiltresExecution()
	{
	}
	public function LieFiltres(& $filtres)
	{
		foreach($filtres as $i => $filtre)
		{
			$filtre->Lie() ;
		}
	}
}