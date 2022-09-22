<?php

namespace Rpa2p\ZonePrinc\Document ;

class Onglet extends Document
{
	public function PrepareRendu(& $zone)
	{
		parent::PrepareRendu($zone) ;
		$zone->InclureRenduTitre = false ;
	}
}
