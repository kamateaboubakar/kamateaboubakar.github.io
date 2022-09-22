<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class ModeleHtml extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $ModeleHtml = "" ;
	public $EncodeValeursHtml = array() ;
	public $EncodeValeursUrl = array() ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$donnees = $this->ObtientDonnees($colonne, $ligne) ;
		if(count($this->EncodeValeursHtml))
		{
			$donnees = array_merge(array_map("htmlentities", \Pv\Misc::array_extract_value_for_keys($donnees, $this->EncodeValeursHtml))) ;
		}
		if(count($this->EncodeValeursUrl))
		{
			$donnees = array_merge(array_map("urlencode", \Pv\Misc::array_extract_value_for_keys($donnees, $this->EncodeValeursUrl))) ;
		}
		return \Pv\Misc::_parse_pattern($this->ModeleHtml, $donnees) ;
	}
	public function InstrsJsEncode(& $composant, $colonne)
	{
		$ctn = '' ;
		$ctn .= 'var modeleHtml = '.svc_json_encode($this->ModeleHtml).' ;
for(var n in donnees)
{
modeleHtml = modeleHtml.split("${" + n + "}").join(encodeURIComponent(donnees[n])) ;
modeleHtml = modeleHtml.split("${" + n + "}").join((donnees[n] !== null) ? donnees[n].replace(/[\u00A0-\u9999<>\&]/gim, function(indexTmp) {
return \'&#\'+indexTmp.charCodeAt(0)+\';\';
}) : "") ;
}
noeudCellule.innerHTML = modeleHtml ;' ;
	}
}