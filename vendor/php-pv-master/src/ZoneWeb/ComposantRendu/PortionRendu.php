<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class PortionRendu extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $Contenu = '' ;
	protected function RenduDispositifBrut()
	{
		return $this->Contenu ;
	}
}