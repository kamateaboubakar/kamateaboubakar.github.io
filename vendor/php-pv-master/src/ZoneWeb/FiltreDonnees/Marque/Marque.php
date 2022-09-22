<?php

namespace Pv\ZoneWeb\FiltreDonnees\Marque ;

class Marque
{
	public $Contenu ;
	public $CouleurPolice ;
	public $CouleurArPl ;
	public function __construct($contenu)
	{
		$this->Contenu = $contenu ;
	}
}