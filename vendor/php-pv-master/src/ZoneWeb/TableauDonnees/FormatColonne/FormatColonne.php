<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class FormatColonne extends \Pv\Objet\Objet
{
	public $ExtracteurValeur ;
	public function EstEditable()
	{
		return 0 ;
	}
	public function EstAccessible(& $zone, $colonne)
	{
		return true ;
	}
	public function Encode(& $composant, $colonne, $ligne)
	{
		if(isset($ligne[$colonne->NomDonnees]))
			return $ligne[$colonne->NomDonnees] ;
		return '' ;
	}
	public function InstrsJsPrepareRendu()
	{
		$ctn = '' ;
		return $ctn ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = '' ;
		if($colonne->NomDonnees == '')
		{
			return '' ;
		}
		$nomDonnees = svc_json_encode($colonne->NomDonnees) ;
		$ctn .= 'var val = "" ;
if(donnees['.$nomDonnees.'] !== undefined) {
val = donnees['.$nomDonnees.'] ;
}
noeudCellule.innerHTML = val ;' ;
		return $ctn ;
	}
	public function ObtientDonnees($colonne, $ligne)
	{
		$valeurCourante = (isset($ligne[$colonne->NomDonnees])) ? $ligne[$colonne->NomDonnees] : '' ;
		$donnees = array_merge($ligne, array('self' => $valeurCourante, 'this' => $valeurCourante)) ;
		return $donnees ;
	}
}