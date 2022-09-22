<?php

namespace Pv\ZoneWeb\PChart ;

class DefSerie
{
	public $IndexChart = -1 ;
	public $Libelle ;
	public $NomDonnees = '' ;
	public $EtiquetteDonnees = '' ;
	public function ObtientLibelle()
	{
		return $this->Libelle != '' ? $this->Libelle : $this->NomDonnees ;
	}
}