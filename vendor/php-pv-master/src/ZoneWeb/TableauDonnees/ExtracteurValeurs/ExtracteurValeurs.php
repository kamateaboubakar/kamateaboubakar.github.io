<?php

namespace Pv\ZoneWeb\TableauDonnees\ExtracteurValeurs ;

class ExtracteurValeurs
{
	public $AccepteValeursVide = 0 ;
	public $ChainesCaractSeulement = 1 ;
	public function Execute($texte, & $composant)
	{
		if($this->AccepteValeursVide == 0 && $texte == '')
			return array() ;
		$valeursBrutes = $this->DecodeValeurs($texte, $composant) ;
		if(! is_array($valeursBrutes))
			return array() ;
		$valeurs = $this->NettoieValeurs($valeursBrutes) ;
		return $valeurs ;
	}
	protected function DecodeValeurs($texte, & $composant)
	{
		return array() ;
	}
	protected function NettoieValeurs($valeursBrutes)
	{
		$valeurs = array() ;
		foreach($valeursBrutes as $nom => $valeur)
		{
			if(! is_scalar($valeur) && $this->ChainesCaractSeulement)
			{
				continue ;
			}
			$valeurs[$nom] = $valeur ;
		}
		return $valeurs ;
	}
}