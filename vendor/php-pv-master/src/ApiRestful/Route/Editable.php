<?php

namespace Pv\ApiRestful\Route ;

class Editable extends Filtrable
{
	public $FiltresEdition = array() ;
	public function & InsereFltEditRef($nom, & $filtreRef, $colLiee='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditFixe($nom, $valeur, $colLiee='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditCookie($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditSession($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditMembreConnecte($nom, $nomParamLie='', $colLiee='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpUpload($nom, $cheminDossierDest="", $colLiee='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpGet($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpPost($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpCorps($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreHttpCorps($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFltEditHttpRequest($nom, $colLiee='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditRef($nom, & $filtreRef, $colLiee='', $nomComp='')
	{
		$flt = $this->CreeFiltreRef($nom, $filtreRef) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditFixe($nom, $valeur, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreFixe($nom, $valeur) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditCookie($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreCookie($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditSession($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreSession($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditMembreConnecte($nom, $nomParamLie='', $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreMembreConnecte($nom, $nomParamLie) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditHttpUpload($nom, $cheminDossierDest="", $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpUpload($nom, $cheminDossierDest) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditHttpGet($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpGet($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditHttpPost($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpPost($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
	public function & InsereFiltreEditHttpRequest($nom, $colLiee='', $nomClsComp='')
	{
		$flt = $this->CreeFiltreHttpRequest($nom) ;
		$flt->DefinitColLiee($colLiee) ;
		if($nomClsComp != '')
			$flt->DeclareComposant($nomClsComp) ;
		$this->FiltresEdition[] = & $flt ;
		return $flt ;
	}
}