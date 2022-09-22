<?php

namespace Rpa2p\Job\TypePeriode ;

class ChaqueJour extends TypePeriode
{
	public function Id()
	{
		return 'chaque_jour' ;
	}
	public function Titre()
	{
		return 'Chaque Jour' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$flt = $form->InsereFltEditHttpPost("param1_periode", "param1_periode") ;
		$flt->Libelle = "Heure(s) de lancement" ;
		$flt->ValeurParDefaut = "06" ;
		$this->InstalleCompHeures($flt) ;
		$flt = $form->InsereFltEditHttpPost("param2_periode", "param2_periode") ;
		$flt->Libelle = "Minutage" ;
		$this->InstalleCompMinutages($flt) ;
	}
	public function CondExec($aliasTable)
	{
		$minutages = \Rpa2p\Config\ExecActivites::MINUTAGES ;
		return "(
(".$aliasTable.".param1_periode = '')
or ((case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end = ".$aliasTable.".param1_periode)
or (".$aliasTable.".param1_periode like concat('%', case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end, ', %'))
or (".$aliasTable.".param1_periode like concat('%, ', case when (HOUR(NOW()) < 10) then concat('0', HOUR(NOW())) else HOUR(NOW()) end, '%'))
) and (
(".$aliasTable.".param2_periode = '')
or MINUTE(NOW()) DIV ".intval(60 / \Rpa2p\Config\ExecActivites::MINUTAGES)." = (".$aliasTable.".param2_periode - 1)
)
)" ;
	}
}
