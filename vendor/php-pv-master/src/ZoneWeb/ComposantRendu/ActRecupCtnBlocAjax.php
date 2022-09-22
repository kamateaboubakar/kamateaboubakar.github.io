<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class ActRecupCtnBlocAjax extends \Pv\ZoneWeb\Action\EnvoiFichier
{
	protected function AfficheContenu()
	{
		echo $this->ComposantRenduParent->RecupContenu() ;
	}
}