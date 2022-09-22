<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneMotPasse extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEntree
{
	public $TypeElementFormulaire = "password" ;
	public $TypeEditeur = "input_password_html" ;
	protected function RenduDispositifBrut()
	{
		$this->Valeur = '' ;
		return parent::RenduDispositifBrut() ;
	}
}