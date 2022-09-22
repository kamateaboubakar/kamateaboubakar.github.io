<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class AttrsTagVideoJs
{
	public $Skin = "vjs-default-skin" ;
	public $Preload = "auto" ;
	public $Largeur = "640" ;
	public $Hauteur = "264" ;
	public $InclureControles = 1 ;
	public $DataSetup ;
	public function __construct()
	{
		$this->DataSetup = new \Pv\ZoneWeb\FiltreDonnees\Composant\DataSetupVideoJs() ;
	}
}