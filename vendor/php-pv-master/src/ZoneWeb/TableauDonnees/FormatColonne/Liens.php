<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Liens extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $Liens = array() ;
	public $InclureIcone = 0 ;
	public $SeparateurLiens = "&nbsp;&nbsp;" ;
	public function EstAccessible(& $zone, $colonne)
	{
		return ! $zone->ImpressionEnCours() ;
	}
	public function Encode(& $composant, $colonne, $ligne)
	{
		$ctn = '' ;
		$donnees = $this->ObtientDonnees($colonne, $ligne) ;
		foreach($this->Liens as $i => $lien)
		{
			if(! $lien->Accepte($donnees))
			{
				continue ;
			}
			if($ctn != '')
			{
				$ctn .= $this->SeparateurLiens ;
			}
			if($this->InclureIcone)
				$lien->InclureIcone = $this->InclureIcone ;
			$ctn .= $lien->Rendu($donnees) ;
		}
		return $ctn ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = '' ;
		$ctn .= 'noeudCellule.innerHTML = "" ;'.PHP_EOL ;
		foreach($this->Liens as $i => $lien)
		{
			$ctn .= 'if('.$lien->InstrJsAccepte().') {'.PHP_EOL ;
			if($this->InclureIcone)
				$lien->InclureIcone = $this->InclureIcone ;
			$ctnLien = $lien->InstrJsRendu() ;
			if($ctnLien != '')
			{
				$ctn .= 'if(noeudCellule.childNodes.length > 0) {
var noeudSep'.$i.' = document.createElement("span") ;
noeudSep'.$i.'.innerHTML = '.svc_json_encode($this->SeparateurLiens).' ;
noeudCellule.appendChild(noeudSep'.$i.') ;
}'.PHP_EOL ;
				$ctn .= $ctnLien.PHP_EOL ;
			}
			$ctn .= '}'.PHP_EOL ;
		}
		return $ctn ;
	}
}