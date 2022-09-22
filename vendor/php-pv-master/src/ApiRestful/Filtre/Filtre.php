<?php

namespace Pv\ApiRestful\Filtre ;

class Filtre extends \Pv\Objet\Objet
{
	public $Obligatoire = 0 ;
	public $RouteParent ;
	public $ApiParent ;
	public $ApplicationParent ;
	public $NomElementRoute = "" ;
	public $NomElementApi = "" ;
	public $TypeLiaisonParametre = "" ;
	public $Role = "base" ;
	public $Liaison ;
	public $Libelle = "" ;
	public $CheminIcone = "" ;
	public $NomClasseCSS = "" ;
	public $NomClasseCSSIcone = "" ;
	public $EspaceReserve = "" ;
	public $NomParametreLie = "" ;
	public $NomParametreDonnees = "" ;
	public $AliasParametreDonnees = "" ;
	public $NomClasseLiaison ;
	public $ExpressionDonnees = "" ;
	public $NomColonneLiee = "" ;
	public $ExpressionColonneLiee = "" ;
	public $NePasInclureSiVide = 1 ;
	public $ValeurParDefaut ;
	public $ValeurVide ;
	public $ValeurParametre ;
	public $ValeurBrute = "" ;
	public $DejaLie = 0 ;
	public $Invisible = 0 ;
	public $EstEtiquette = 0 ;
	public $LectureSeule = 0 ;
	public $NePasLierColonne = 0 ;
	public $NePasLireColonne = 0 ;
	public $NePasLierParametre = 0 ;
	public $NePasIntegrerParametre = 0 ;
	public $AppliquerCorrecteurValeur = 1 ;
	public $CorrecteurValeur ;
	public $FormatteurEtiquette ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CorrecteurValeur = new \Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\CorrecteurValeur() ;
		$this->FormatteurEtiquette = new \Pv\ZoneWeb\FiltreDonnees\FormatEtiquette\FormatEtiquette() ;
	}
	public function AdopteApi($nom, & $Route)
	{
		$this->ApiParent = & $Route->ApiParent ;
		$this->ApplicationParent = & $Route->ApplicationParent ;
		$this->NomElementApi = $nom ;
	}
	public function AdopteRoute($nom, & $Route)
	{
		$this->RouteParent = & $Route ;
		$this->ApiParent = & $Route->ApiParent ;
		$this->ApplicationParent = & $Route->ApplicationParent ;
		$this->NomElementRoute = $nom ;
	}
	protected function CorrigeConfig()
	{
		if($this->NomParametreDonnees == '' && $this->NomElementRoute != '')
			$this->NomParametreDonnees = $this->NomElementRoute ;
		if($this->NomParametreLie == '' && $this->NomElementRoute != '')
			$this->NomParametreLie = $this->NomElementRoute ;
	}
	public function NePasInclure()
	{
		if($this->NePasInclureSiVide && ! $this->Obligatoire)
		{
			return ($this->ValeurVide == $this->ValeurParametre) ;
		}
		return 0 ;
	}
	public function RenduPossible()
	{
		return (! $this->Invisible && ($this->TypeLiaisonParametre == 'get' or $this->TypeLiaisonParametre == 'post')) ? 1 : 0 ;
	}
	public function ObtientValeurParametre()
	{
		return "" ;
	}
	public function CorrigeNomParametreLie()
	{
		if($this->NomParametreLie == '')
		{
			if($this->NomElementRoute != '')
				$this->NomParametreLie = $this->NomElementRoute ;
			else
				$this->NomParametreLie = $this->IDInstanceCalc ;
		}
	}
	public function ObtientLibelle()
	{
		$libelle = $this->Libelle ;
		if($libelle == '')
		{
			$libelle = $this->NomElementRoute ;
		}
		return $libelle ;
	}
	public function Lie()
	{
		$this->CorrigeConfig() ;
		if($this->DejaLie == 1)
		{
			return $this->ValeurParametre ;
		}
		$this->ValeurParametre = $this->ValeurParDefaut ;
		// echo $this->NomParametreDonnees ;
		if($this->Invisible == 1 || $this->NePasLierParametre == 1)
		{
			return $this->ValeurParametre ;
		}
		$valeurParametre = $this->ObtientValeurParametre() ;
		if($this->AppliquerCorrecteurValeur)
		{
			$valeurParametre = $this->CorrecteurValeur->Applique($valeurParametre, $this) ;
		}
		if($valeurParametre !== $this->ValeurVide || $this->ValeurVide !== null)
		{
			$this->ValeurParametre = $valeurParametre ;
		}
		$this->DejaLie = 1 ;
		return $this->ValeurParametre ;
	}
	public function DefinitColLiee($nomCol)
	{
		$this->NomColonneLiee = $nomCol ;
		$this->NomParametreDonnees = $nomCol ;
	}
	public function FormatTexte()
	{
		$valTemp = $this->Lie() ;
		return $valTemp ;
	}
	public function LiePourRendu()
	{
		$valeur = $this->Lie() ;
		if($valeur !== $this->ValeurVide && $this->EstPasNul($this->CorrecteurValeur))
		{
			$valeur = $this->CorrecteurValeur->AppliquePourRendu($valeur, $this) ;
		}
		return $valeur ;
	}
	public function LiePourTraitement()
	{
		$valeur = $this->Lie() ;
		if($valeur !== $this->ValeurVide && $this->EstPasNul($this->CorrecteurValeur))
		{
			$valeur = $this->CorrecteurValeur->AppliquePourTraitement($valeur, $this) ;
		}
		return $valeur ;
	}
}