<?php

namespace Pv\ZoneWeb\PChart ;

class ActionImage extends \Pv\ZoneWeb\Action\EnvoiFichier
{
	public $TypeMime = "image/png" ;
	public $NomFichierAttache = "graphe" ;
	public $ExtensionFichierAttache = "png" ;
	protected function AfficheContenu()
	{
		$this->ComposantRenduParent->EnvoieImage() ;
		exit ;
	}
}