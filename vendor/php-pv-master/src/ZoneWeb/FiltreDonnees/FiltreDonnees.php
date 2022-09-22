<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class FiltreDonnees extends \Pv\Objet\Objet
{
	public $PrefixesLibelle = array() ;
	public $SuffixesLibelle = array() ;
	public $Obligatoire = 0 ;
	public $ScriptParent ;
	public $ZoneParent ;
	public $ApplicationParent ;
	public $NomElementScript = "" ;
	public $NomElementZone = "" ;
	public $TypeLiaisonParametre = "" ;
	public $Role = "base" ;
	public $Liaison ;
	public $Composant ;
	public $Libelle = "" ;
	public $CheminIcone = "" ;
	public $NomClasseCSS = "" ;
	public $NomClasseCSSIcone = "" ;
	public $EspaceReserve = "" ;
	public $NomClasseComposant = '\Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte' ;
	public $NomComposant = "" ;
	public $NomParametreLie = "" ;
	public $NomParametreDonnees = "" ;
	public $AliasParametreDonnees = "" ;
	public $NomClasseLiaison ;
	public $ExpressionDonnees = "" ;
	public $NomColonneLiee = "" ;
	public $ExpressionColonneLiee = "" ;
	public $MethodeSoumetApi = "" ;
	public $NomParametreSoumetApi = "" ;
	public $NePasInclureSiVide = false ;
	public $ValeurParDefaut ;
	public $ValeurVide ;
	public $ValeurParametre ;
	public $ValeurBrute = "" ;
	public $DejaLie = false ;
	public $Invisible = false ;
	public $Trim = true ;
	public $EstEtiquette = false ;
	public $LectureSeule = false ;
	public $NePasLierColonne = false ;
	public $NePasLireColonne = false ;
	public $NePasLierParametre = false ;
	public $NePasIntegrerParametre = false ;
	public $AppliquerCorrecteurValeur = true ;
	public $CorrecteurValeur ;
	public $FormatteurEtiquette ;
	public function ImpressionEnCours()
	{
		return $this->EstPasNul($this->ZoneParent) && $this->ZoneParent->ImpressionEnCours() ;
	}
	public function InserePrefxErr($contenu)
	{
		$this->InserePrefixeLib(new \Pv\ZoneWeb\FiltreDonnees\Marque\Erreur($contenu)) ;
	}
	public function InserePrefxNotice($contenu)
	{
		$this->InserePrefixeLib(new \Pv\ZoneWeb\FiltreDonnees\Marque\Notice($contenu)) ;
	}
	public function InsereSuffxErr($contenu)
	{
		$this->InsereSuffixeLib(new \Pv\ZoneWeb\FiltreDonnees\Marque\Erreur($contenu)) ;
	}
	public function InsereSuffxNotice($contenu)
	{
		$this->InsereSuffixeLib(new \Pv\ZoneWeb\FiltreDonnees\Marque\Notice($contenu)) ;
	}
	public function InsereSuffxLib($val)
	{
		$this->InsereSuffixeLib($val) ;
	}
	public function InserePrefxLib($val)
	{
		$this->InserePrefixeLib($val) ;
	}
	public function InserePrefixeLib($val)
	{
		$this->PrefixesLibelle[] = $val ;
	}
	public function InsereSuffixeLib($val)
	{
		$this->SuffixesLibelle[] = $val ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CorrecteurValeur = new \Pv\ZoneWeb\FiltreDonnees\CorrecteurValeur\Correcteur() ;
		$this->FormatteurEtiquette = new \Pv\ZoneWeb\FiltreDonnees\FormatEtiquette\FormatEtiquette() ;
	}
	public function AdopteZone($nom, & $script)
	{
		$this->ZoneParent = & $script->ZoneParent ;
		$this->ApplicationParent = & $script->ApplicationParent ;
		$this->NomElementZone = $nom ;
	}
	public function AdopteScript($nom, & $script)
	{
		$this->ScriptParent = & $script ;
		$this->ZoneParent = & $script->ZoneParent ;
		$this->ApplicationParent = & $script->ApplicationParent ;
		$this->NomElementScript = $nom ;
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeComposant() ;
	}
	protected function CorrigeConfig()
	{
		if($this->NomParametreDonnees == '' && $this->NomElementScript != '')
			$this->NomParametreDonnees = $this->NomElementScript ;
		if($this->NomParametreLie == '' && $this->NomElementScript != '')
			$this->NomParametreLie = $this->NomElementScript ;
	}
	protected function ChargeComposant()
	{
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
		return (! $this->Invisible && ($this->TypeLiaisonParametre == 'get' or $this->TypeLiaisonParametre == 'post')) ? true : false ;
	}
	public function ObtientValeurParametre()
	{
		return "" ;
	}
	public function CorrigeNomParametreLie()
	{
		if($this->NomParametreLie == '')
		{
			if($this->NomElementScript != '')
				$this->NomParametreLie = $this->NomElementScript ;
			else
				$this->NomParametreLie = $this->IDInstanceCalc ;
		}
	}
	public function ObtientLibelle()
	{
		$libelle = $this->Libelle ;
		if($libelle == '')
		{
			$libelle = $this->NomElementScript ;
		}
		return $libelle ;
	}
	public function Lie()
	{
		$this->CorrigeConfig() ;
		if($this->DejaLie == true)
		{
			return $this->ValeurParametre ;
		}
		$this->ValeurParametre = $this->ValeurParDefaut ;
		if($this->ValeurParametre != "" && $this->Trim)
		{
			$this->ValeurParametre = trim($this->ValeurParametre) ;
		}
		// echo $this->NomParametreDonnees ;
		if($this->Invisible == true || $this->NePasLierParametre == true)
		{
			if($this->ValeurParametre === null)
			{
				$this->ValeurParametre = "" ;
			}
			return $this->ValeurParametre ;
		}
		$valeurParametre = $this->ObtientValeurParametre() ;
		if($valeurParametre != "" && $this->Trim)
		{
			$valeurParametre = trim($this->ObtientValeurParametre()) ;
		}
		if($this->AppliquerCorrecteurValeur)
		{
			$valeurParametre = $this->CorrecteurValeur->Applique($valeurParametre, $this) ;
		}
		if($valeurParametre !== $this->ValeurVide || $this->ValeurVide !== null)
		{
			$this->ValeurParametre = $valeurParametre ;
		}
		if($this->ValeurParametre === null)
		{
			$this->ValeurParametre = "" ;
		}
		$this->DejaLie = true ;
		return $this->ValeurParametre ;
	}
	public function DefinitColLiee($nomCol)
	{
		$this->NomColonneLiee = $nomCol ;
		$this->NomParametreDonnees = $nomCol ;
	}
	public function ObtientNomComposant()
	{
		$this->CorrigeNomParametreLie() ;
		$nomComposant = $this->NomParametreLie ;
		return $nomComposant ;
	}
	public function ObtientIDElementHtmlComposant()
	{
		if($this->EstNul($this->Composant))
		{
			$this->DeclareComposant($this->NomClasseComposant) ;
		}
		if($this->EstNul($this->Composant))
			return "" ;
		$iDInstanceCalc = $this->Composant->IDInstanceCalc ;
		return $iDInstanceCalc ;
	}
	public function ObtientIDComposant()
	{
		return $this->ObtientIDElementHtmlComposant() ;
	}
	public function Rendu()
	{
		if($this->EstEtiquette || $this->ImpressionEnCours())
		{
			return $this->Etiquette() ;
		}
		if($this->EstNul($this->Composant))
		{
			$this->DeclareComposant($this->NomClasseComposant) ;
		}
		if($this->EstNul($this->Composant))
		{
			return "(Composant inexistant : ".$this->NomClasseComposant.")" ;
		}
		$this->Composant->Valeur = $this->LiePourRendu() ;
		$this->Composant->EspaceReserve = $this->EspaceReserve ;
		if($this->Composant->EspaceReserve == "" && $this->ZoneParent->LibelleEspaceReserveFiltres == 1)
		{
			$this->Composant->EspaceReserve = $this->Libelle ;
		}
		$this->Composant->FiltreParent = $this ;
		$ctn = $this->Composant->RenduDispositif() ;
		$this->Composant->FiltreParent = null ;
		return $ctn ;
	}
	public function DefinitFmtLbl($fmt)
	{
		$this->ObtientComposant()->FmtLbl = $fmt ;
	}
	public function Etiquette()
	{
		if($this->EstNul($this->Composant))
		{
			$this->DeclareComposant($this->NomClasseComposant) ;
		}
		if($this->EstNul($this->Composant))
		{
			return "(Composant nul)" ;
		}
		$this->Composant->Valeur = $this->FormatteurEtiquette->Applique($this->LiePourRendu(), $this) ;
		$this->Composant->FiltreParent = $this ;
		$ctn = $this->Composant->RenduEtiquette() ;
		$this->Composant->FiltreParent = null ;
		return $ctn ;
	}
	public function InitComposant()
	{
	}
	public function & ObtientComposant()
	{
		if($this->EstNul($this->Composant))
			return $this->DeclareComposant($this->NomClasseComposant) ;
		return $this->Composant ;
	}
	public function & DeclareComposant($nomClasseComposant)
	{
		if(\Pv\Application\Application::$InclureAliasesCompsFltsDonnees)
		{
			if(isset(\Pv\Application\Application::$AliasesCompsFltsDonnees[$nomClasseComposant]))
			{
				$nomClasseComposant = \Pv\Application\Application::$AliasesCompsFltsDonnees[$nomClasseComposant] ;
			}
		}
		$this->Composant = $this->ValeurNulle() ;
		$this->NomClasseComposant = $nomClasseComposant ;
		if(class_exists($nomClasseComposant))
		{
			$this->Composant = new $nomClasseComposant() ;
			$this->Composant->AdopteScript($this->ObtientNomComposant(), $this->ScriptParent) ;
			$this->InitComposant() ;
			$this->Composant->ChargeConfig() ;
		}
		return $this->Composant ;
	}
	public function & RemplaceComposant($nouvComposant)
	{
		$this->Composant = $nouvComposant ;
		$this->Composant->AdopteScript($this->ObtientNomComposant(), $this->ScriptParent) ;
		$this->InitComposant() ;
		$this->Composant->ChargeConfig() ;
		return $this->Composant ;
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