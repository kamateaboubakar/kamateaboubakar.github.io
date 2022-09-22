<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class DateFr extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $InclureHeure = 0 ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$valeurEntree = $ligne[$colonne->NomDonnees] ;
		if($this->InclureHeure == 1)
		{
			return \Pv\Misc::date_time_fr($valeurEntree) ;
		}
		else
		{
			return \Pv\Misc::date_fr($valeurEntree) ;
		}
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = 'var val = (donnees['.svc_json_encode($this->NomDonnees).'] !== undefined) ? donnees['.svc_json_encode($this->NomDonnees).'] : null ;
var valCellule = "" ;
if(val !== null) {
var attrsDate = val.split("-") ;
if(attrsDate.length == 3) {'.PHP_EOL ;
		if($this->InclureHeure)
		{
			$ctn .= 'var attrsHeure = attrsDate[2].split(" ") ;
valCellule = attrsHeure[0] + "/" + attrsDate[1] + "/" + attrsDate[0] + " " + ((attrsHeure.length > 1) ? attrsHeure[1] : "00:00:00") ;'.PHP_EOL ;
		}
		else
		{
			$ctn .= 'valCellule = attrsDate[2] + "/" + attrsDate[1] + "/" + attrsDate[0] ;'.PHP_EOL ;
		}
		$ctn .= '}
}
noeudCellule.innerText = valCellule ;' ;
	}
}