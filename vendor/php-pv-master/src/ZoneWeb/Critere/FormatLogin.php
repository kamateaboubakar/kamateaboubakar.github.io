<?php

namespace Pv\ZoneWeb\Critere ;

class FormatLogin extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} doivent avoir un pseudo valide' ;
	protected function RespecteRegle(& $filtre)
	{
		return \Pv\Misc::validate_name_user_format($filtre->ValeurParametre) ;
	}
}