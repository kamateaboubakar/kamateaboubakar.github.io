<?php

namespace Pv\ZoneWeb\Action ;

class ImprimeScript extends \Pv\ZoneWeb\Action\Action
{
	public function Execute()
	{
		$this->ZoneParent->DemarreRenduImpression() ;
		echo $this->ZoneParent->RenduDocument() ;
		exit ;
	}
}