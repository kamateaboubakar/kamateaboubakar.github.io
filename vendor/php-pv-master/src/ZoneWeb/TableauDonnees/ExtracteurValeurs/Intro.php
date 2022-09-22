<?php

namespace Pv\ZoneWeb\TableauDonnees\ExtracteurValeurs ;

class Intro extends \Pv\ZoneWeb\TableauDonnees\ExtracteurValeurs\ExtracteurValeurs
{
	public $MaxMots = 255 ;
	public $ExprPlus = "..." ;
	public $AccepteValeursVide = 1 ;
	protected function DecodeValeurs($texte, & $composant)
	{
		$valeurs = array("\Pv\Misc::intro" => \Pv\Misc::intro($texte, $this->MaxMots, $this->ExprPlus)) ;
		return $valeurs ;
	}
}