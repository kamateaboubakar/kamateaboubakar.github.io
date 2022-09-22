<?php

namespace Rpa2p\ZonePrinc\Document ;

class Modal extends Document
{
	public function PrepareRendu(& $zone)
	{
		$largeurMin = (isset($zone->ScriptPourRendu->LargeurMin)) ? $zone->ScriptPourRendu->LargeurMin : "200px" ;
		parent::PrepareRendu($zone) ;
		$zone->InclureRenduTitre = false ;
		$zone->InscritContenuJs('
jQuery(document).ready(function() {
	window.top.majModal(
	'.json_encode($zone->ScriptPourRendu->Titre).', 
	"'.$largeurMin.'px"
	) ;
	setTimeout(function() {
		window.top.majModal(
		'.json_encode($zone->ScriptPourRendu->Titre).', 
		(jQuery(document).outerHeight(true)) + "px"
		) ;
	}, 1000) ;
})') ;
	}
}
