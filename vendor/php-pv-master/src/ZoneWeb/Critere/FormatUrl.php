<?php

namespace Pv\ZoneWeb\Critere ;

class FormatUrl extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} doivent avoir une URL valide' ;
	protected function RespecteRegle(& $filtre)
	{
		return \Pv\Misc::validate_url_format($filtre->ValeurParametre) ;
	}
}