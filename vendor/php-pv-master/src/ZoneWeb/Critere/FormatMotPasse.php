<?php

namespace Pv\ZoneWeb\Critere ;

class FormatMotPasse extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} doivent avoir un mot de passe valide' ;
	protected function RespecteRegle(& $filtre)
	{
		return \Pv\Misc::validate_password_format($filtre->ValeurParametre) ;
	}
}