<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Editable extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $NomParametrePost ;
	protected $NomClasseComposant  ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Composant = new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte() ;
		$this->NomParametrePost = "Param_".$this->IDInstanceCalc ;
	}
	public function DeclareComposant($nomClasseComposant)
	{
		if($nomClasseComposant == "")
		{
			return ;
		}
		if(! class_exists($nomClasseComposant))
		{
			die("Echec creation du composant ".htmlentities($nomClasseComposant)) ;
		}
		$this->NomClasseComposant = $nomClasseComposant ;
	}
	protected function ChargeComposant(& $composant, & $composantParent)
	{
		$composant->AdopteScript("Composant_".$this->IDInstanceCalc, $composantParent->ScriptParent) ;
		$composant->ChargeConfig() ;
	}
	public function EstEditable()
	{
		return 1 ;
	}
	public function Encode(& $composantParent, $colonne, $ligne)
	{
		$filtreSupport = $composantParent->ScriptParent->CreeFiltreHttpPost($this->NomParametrePost."[]") ;
		$valeur = $ligne[$colonne->NomDonnees] ;
		$nomClasseComposant = $this->NomClasseComposant ;
		$composant = new $nomClasseComposant() ;
		$this->ChargeComposant($composant, $composantParent) ;
		$composant->Valeur = $valeur ;
		$composant->NomElementHtml = $this->NomParametrePost."[]" ;
		$composant->FiltreParent = $filtreSupport ;
		$ctn = $composant->RenduDispositif() ;
		$composant->FiltreParent = null ;
		return $ctn ;
	}
}