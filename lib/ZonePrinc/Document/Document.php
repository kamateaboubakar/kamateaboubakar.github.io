<?php

namespace Rpa2p\ZonePrinc\Document ;

class Document extends \Pv\ZoneWeb\Document\Html
{
	protected function RenduEnteteHtmlSimple(& $zone)
	{
		return parent::RenduEntete($zone) ;
	}
	protected function RenduPiedHtmlSimple(& $zone)
	{
		return parent::RenduPied($zone) ;
	}
}
