<?php

namespace Pv\ZoneWeb\FiltreDonnees\FormatLbl ;

class DateTimeFr extends \Pv\ZoneWeb\FiltreDonnees\FormatLbl\FormalLbl
{
	public function Rendu($valeur, & $composant)
	{
		return \Pv\Misc::date_time_fr($valeur) ;
	}			
}