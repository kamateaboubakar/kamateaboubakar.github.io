<?php

namespace Pv\ApiRestful\Route ;

class Filtrable extends \Pv\ApiRestful\Route\Donnees
{
	public $FiltresSelection = array() ;
	public function & InsereFltSelectRef($nom, & $filtreRef, $exprDonnees='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectFixe($nom, $valeur, $exprDonnees='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectCookie($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectSession($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectMembreConnecte($nom, $nomParamLie='', $exprDonnees='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpUpload($nom, $cheminDossierDest="", $exprDonnees='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpGet($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpPost($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpCorps($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreHttpCorps($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltSelectHttpRequest($nom, $exprDonnees='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->ExpressionDonnees = $exprDonnees ;
		$this->FiltresSelection[] = & $flt ;
		return $flt ;
	}
}