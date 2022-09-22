<?php

namespace Rpa2p\Job\TypePeriode ;

class ChaqueSemaine extends TypePeriode
{
	public function Id()
	{
		return 'chaque_semaine' ;
	}
	public function Titre()
	{
		return 'Chaque Semaine' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$flt = $form->InsereFltEditHttpPost("param1_periode", "param1_periode") ;
		$flt->Libelle = "Jour(s) de lancement" ;
		$flt->ValeurParDefaut = "06" ;
		$this->InstalleCompJours($flt) ;
		$flt = $form->InsereFltEditHttpPost("param2_periode", "param2_periode") ;
		$flt->Libelle = "Heure(s) de lancement" ;
		$flt->ValeurParDefaut = "06" ;
		$this->InstalleCompHeures($flt) ;
	}
	public function CondExec($aliasTable)
	{
		return "(
(
(".$aliasTable.".param1_periode = '')
or (WEEKDAY(NOW()) = ".$aliasTable.".param1_periode)
or (".$aliasTable.".param1_periode like concat('%', WEEKDAY(NOW()), ', %'))
or (".$aliasTable.".param1_periode like concat('%, ', WEEKDAY(NOW()), '%'))
) and (
(".$aliasTable.".param2_periode = '')
or (case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end = ".$aliasTable.".param2_periode)
or (".$aliasTable.".param2_periode like concat('%', case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end, ', %'))
or (".$aliasTable.".param2_periode like concat('%, ', case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end, '%'))
)
)" ;
	}
}
