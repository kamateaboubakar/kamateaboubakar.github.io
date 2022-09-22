<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class Filtrable extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $FournisseurDonnees ;
	public $FiltresSelection = array() ;
	public $SourceValeursSuppl ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->SourceValeursSuppl = new \Pv\ZoneWeb\Donnees\SrcValsSuppl\SrcValsSuppl() ;
	}
	protected function ExtraitValeursLgnDonnees(& $lgn)
	{
		if($this->EstNul($this->SourceValeursSuppl))
		{
			return $lgn ;
		}
		return $this->SourceValeursSuppl->Applique($this, $lgn) ;
	}
	public function & CreeFiltreRef($nom, & $filtreRef)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Ref() ;
		$filtre->Source = & $filtreRef ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreDonnees = $nom ;
		return $filtre ;
	}
	public function & CreeFiltreFixe($nom, $valeur)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Fixe() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->ValeurParDefaut = $valeur ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreCookie($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Cookie() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreSession($nom)
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\Session() ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		return $filtre ;
	}
	public function & CreeFiltreMembreConnecte($nom, $nomParamLie='')
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\MembreConnecte() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
		$filtre->NomParametreDonnees = $nom ;
		$filtre->NomParametreLie = $nomParamLie ;
		return $filtre ;
	}
	public function & CreeFiltreHttpUpload($nom, $cheminDossierDest="")
	{
		$filtre = new \Pv\ZoneWeb\FiltreDonnees\HttpUpload() ;
		$filtre->AdopteScript($nom, $this->ScriptParent) ;
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
	public function & InsereFltSelectRef($nom, & $filtreRef, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectFixe($nom, $valeur, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectCookie($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectSession($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectMembreConnecte($nom, $nomParamLie='', $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpUpload($nom, $cheminDossierDest="", $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpGet($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpPost($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpRequest($nom, $exprDonnees='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function CalculeElementsRendu()
	{
	}
	public function VerifiePreRequisRendu()
	{
		return 1 ;
	}
	public function MsgPreRequisRenduNonVerifies()
	{
		return "(PRE REQUIS DU RENDU NON VERIFIES)" ;
	}
	protected function RenduDispositifBrut()
	{
		$this->CalculeElementsRendu() ;
		if($this->VerifiePreRequisRendu())
		{
			return $this->RenduDispositifBrutSpec() ;
		}
		return $this->MsgPreRequisRenduNonVerifies() ;
	}
	public function ObtientFiltresSelection()
	{
		return $this->FiltresSelection ;
	}
	protected function RenduDispositifBrutSpec()
	{
	}
}