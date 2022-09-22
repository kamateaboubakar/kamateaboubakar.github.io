<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Fixe extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $ValeurParDefaut = "" ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		return htmlentities($this->ValeurParDefaut) ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		return 'noeudCellule.innerHTML = '.svc_json_encode($this->ValeurParDefaut).' ;' ;
	}
}