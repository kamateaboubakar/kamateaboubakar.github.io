<?php

namespace Pv\ZoneWeb\FiltreDonnees\FormatLbl ;

class DateFr extends \Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl
{
	public function Rendu($valeur, & $composant)
	{
		return \Pv\Misc::date_fr($valeur) ;
	}			
}