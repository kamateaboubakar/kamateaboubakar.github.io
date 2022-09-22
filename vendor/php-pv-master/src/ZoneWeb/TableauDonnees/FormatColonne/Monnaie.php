<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Monnaie extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $MaxDecimals = 3 ;
	public $MinChiffres = 1 ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$valeurEntree = $ligne[$colonne->NomDonnees] ;
		return \Pv\Misc::format_money($valeurEntree, $this->MaxDecimals, $this->MinChiffres) ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = '' ;
		$nomDonnees = $colonne->NomDonnees ;
		$ctn .= 'var valEntree, val = "" ;
if(donnees['.$nomDonnees.'] !== undefined) {
valEntree = donnees['.$nomDonnees.'] ;
}
if(valEntree !== "") {
var parts = valEntree.toString().split(".");
parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
val = parts.join(".");
}
noeudCellule.innerText = val ;' ;
		return 'noeudCellule.innerHTML = '.svc_json_encode($this->ValeurParDefaut).' ;' ;
	}
}