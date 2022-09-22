<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class FilArianeDonnees extends \Pv\ZoneWeb\ComposantRendu\ComposantDonneesSimple
{
	public $NomClasseCSS = "FilAriane" ;
	public $NomClasseCSSLien = "" ;
	public $DefsLien = array() ;
	protected $LgnsLien = array() ;
	protected $CtnsLien = array() ;
	public $FiltresSelection = array() ;
	public $FournisseurDonnees ;
	public $SeparateurLiens = ' &gt; ' ;
	public $CacherSiVide = 1 ;
	public $InclureLienAccueil = 1 ;
	public $TitreLienAccueil = "Accueil" ;
	public $UrlLienAccueil = "?" ;
	public $NomClasseFournisseurDonnees = "\Pv\FournisseurDonnees\FournisseurDonnees" ;
	protected function InitFournisseurDonnees()
	{
		if($this->EstNul($this->FournisseurDonnees) && $this->NomClasseFournisseurDonnees != "")
		{
			$nomClasse = $this->NomClasseFournisseurDonnees ;
			if(class_exists($nomClasse))
			{
				$this->FournisseurDonnees = new $nomClasse() ;
			}
		}
		if(! $this->EstNul($this->FournisseurDonnees))
		{
			$this->ChargeConfigFournisseurDonnees() ;
			$this->FournisseurDonnees->ChargeConfig() ;
		}
	}
	protected function ChargeConfigFournisseurDonnees()
	{
	}
	protected function CalculeElementsRendu()
	{
		$fourn = & $this->FournisseurDonnees ;
		$paramsSelect = $fourn->ParamsSelection ;
		$this->LgnsLien = array() ;
		foreach($this->DefsLien as $i => $defLien)
		{
			$lienTrouve = 0 ;
			if($defLien->RequeteSelection != '')
			{
				$fourn->RequeteSelection = $defLien->RequeteSelection ;
				$lgnPrec = array() ;
				do
				{
					$flts = $this->FiltresSelection ;
					$fourn->ParamsSelection = $paramsSelect ;
					foreach($lgnPrec as $nom => $valeur)
					{
						$nomFlt = "lgn_prec_".$nom ;
						$fourn->ParamsSelection[$nom] = $valeur ;
					}
					$lgn = $fourn->SelectElements(array(), $flts) ;
					if(is_array($lgn) && count($lgn) > 0)
					{
						$this->CtnsLien[] = $this->CreeCtnLien($defLien, $lgn) ;
						$lienTrouve = 1 ;
					}
					$lgnPrec = $lgn ;
				}
				while($defLien->Recursif == 1) ;
			}
			else
			{
				$lgn = array() ;
				$this->CtnsLien[] = $this->CreeCtnLien($defLien, $lgn) ;
				$lienTrouve = 1 ;
			}
			if($defLien->Obligatoire && $lienTrouve == 0)
			{
				break ;
			}
		}
	}
	protected function CtnsLienRendu()
	{
		$ctnsLien = $this->CtnsLien ;
		if($this->InclureLienAccueil == 1)
		{
			$ctnLien = new \Pv\ZoneWeb\ComposantRendu\CtnLienFilAriane() ;
			$ctnLien->Titre = $this->TitreLienAccueil ;
			$ctnLien->Url = $this->UrlLienAccueil ;
			$ctnsLien[] = $ctnLien ;
		}
		return $ctnsLien ;
	}
	protected function CreeCtnLien($defLien, $lgn)
	{
		$ctnLien = new \Pv\ZoneWeb\ComposantRendu\CtnLienFilAriane() ;
		$ctnLien->Titre = \Pv\Misc::_parse_pattern($defLien->FormatTitre, $lgn) ;
		$ctnLien->Url = \Pv\Misc::_parse_pattern($defLien->FormatUrl, $lgn) ;
		$ctnLien->AttrsHtmlExtra = $defLien->AttrsHtmlExtra ;
		return $ctnLien ;
	}
	protected function RenduDispositifBrut()
	{
		$this->InitFournisseurDonnees() ;
		if(! $this->EstNul($this->FournisseurDonnees))
		{
			$this->ChargeConfigFournisseurDonnees() ;
		}
		$this->CalculeElementsRendu() ;
		if($this->CacherSiVide == 0 || $this->LiensTrouves())
		{
			$ctn .= '<div id="'.$this->IDInstanceCalc.'" class="'.$this->NomClasseCSS.'">' ;
			if($this->EstVide() == 0)
			{
				$ctn .= $this->RenduLiens() ;
			}
			$ctn .= '</div>' ;
		}
		return $ctn ;
	}
	protected function RenduLiens()
	{
		$ctn = '' ;
		$ctnsLien = $this->CtnsLienRendu() ;
		for($i=count($ctnsLien) - 1; $i >= 0; $i--)
		{
			$ctnLien = $ctnsLien[$i] ;
			if($i < count($ctnsLien) - 1)
			{
				$ctn .= $this->SeparateurLiens ;
			}
			$ctn .= '<a href="'.$ctnLien->Url.'"'.(($ctnLien->AttrsHtmlExtra != '') ? ' '.$ctnLien->AttrsHtmlExtra : '').''.(($this->NomClasseCSSLien != '') ? ' class="'.$this->NomClasseCSSLien.'"' : '').'>'.$ctnLien->Titre.'</a>' ;
		}
		return $ctn ;
	}
	public function LiensTrouves()
	{
		return (count($this->CtnsLien) > 0) ;
	}
	public function EstVide()
	{
		return ($this->LiensTrouves() == false) ;
	}
	public function InsereDefLien($requeteSelect, $formatUrl, $formatTitre)
	{
		$lien = new \Pv\ZoneWeb\ComposantRendu\DefLienFilAriane() ;
		$lien->RequeteSelection = $requeteSelect ;
		$lien->FormatUrl = $formatUrl ;
		$lien->FormatTitre = $formatTitre ;
		$this->DefsLien[] = & $lien ;
		return $lien ;
	}
	public function InsereDefLienStatique($formatUrl, $formatTitre)
	{
		$lien = new \Pv\ZoneWeb\ComposantRendu\DefLienFilAriane() ;
		$lien->FormatUrl = $formatUrl ;
		$lien->FormatTitre = $formatTitre ;
		$this->DefsLien[] = & $lien ;
		return $lien ;
	}
	public function InsereDefLienFixe($formatUrl, $formatTitre)
	{
		return $this->InsereDefLienStatique($formatUrl, $formatTitre) ;
	}
}