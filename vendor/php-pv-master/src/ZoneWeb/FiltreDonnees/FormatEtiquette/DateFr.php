<?php

namespace Pv\ZoneWeb\FiltreDonnees\FormatEtiquette ;

class DateFr extends PvFmtEtiquetteFiltre
{
	public function Applique($valeur, & $filtre)
	{
		return \Pv\Misc::date_fr($valeur) ;
	}
}