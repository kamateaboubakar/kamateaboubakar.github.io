<?php

namespace Pv\ZoneWeb\Donnees\SrcValsSuppl ;

class SrcValsSuppl
{
	public $InclureHtml = false ;
	public $SuffixeHtml = "_html" ;
	public $InclureUrl = false ;
	public $SuffixeUrl = "_query_string" ;
	public $LignesDonneesBrutes = null ;
	protected function AppliqueFonct($nomFonct, & $lgn, $suffixe)
	{
		$res = array() ;
		foreach($lgn as $n => $v) {
			if($v == "") {
				$res[$n.$suffixe] = $v ;
			}
			else {
				$res[$n.$suffixe] = call_user_func_array($nomFonct, array($v)) ;
			}
		}
		return $res ;
	}
	public function Applique(& $composant, $ligneDonnees)
	{
		$this->LigneDonneesBrutes = $ligneDonnees ;
		// print_r($ligneDonneesBrutes) ;
		if($this->InclureHtml)
		{
			$ligneDonnees = array_merge(
				$ligneDonnees,
				$this->AppliqueFonct('htmlentities', $this->LigneDonneesBrutes, $this->SuffixeHtml)
			) ;
		}
		if($this->InclureUrl)
		{
			$ligneDonnees = array_merge(
				$ligneDonnees,
				$this->AppliqueFonct('urlencode', $this->LigneDonneesBrutes, $this->SuffixeUrl)
			) ;
		}
		return $ligneDonnees ;
	}
}