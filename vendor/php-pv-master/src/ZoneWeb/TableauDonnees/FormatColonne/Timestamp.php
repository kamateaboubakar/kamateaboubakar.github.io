<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Timestamp extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $FormatDate = "Y-m-d H:i:s" ;
	public $FormatDateJs = "Y-m-d H:i:s" ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$valeurEntree = $ligne[$colonne->NomDonnees] ;
		if($valeurEntree == "")
		{
			return $valeurEntree ;
		}
		return date($this->FormatDate, $valeurEntree) ;
	}
}