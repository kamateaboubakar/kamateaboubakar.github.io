<?php

namespace Pv\ZoneWeb\TableauDonnees\ExtracteurValeurs ;

class JSON extends \Pv\ZoneWeb\TableauDonnees\ExtracteurValeurs\ExtracteurValeurs
{
	protected function DecodeValeurs($texte, & $composant)
	{
		$valeurs = svc_json_decode($texte) ;
	}
}