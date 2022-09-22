<?php

namespace Pv\ZoneWeb\Critere ;

class FormatEmail extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} doivent avoir un email valide' ;
	protected function RespecteRegle(& $filtre)
	{
		return \Pv\Misc::validate_email_format($filtre->ValeurParametre) ;
	}
}
