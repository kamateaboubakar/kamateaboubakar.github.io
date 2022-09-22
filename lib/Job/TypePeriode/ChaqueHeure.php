<?php

namespace Rpa2p\Job\TypePeriode ;

class ChaqueHeure extends TypePeriode
{
	public function Id()
	{
		return 'chaque_heure' ;
	}
	public function Titre()
	{
		return 'Chaque Heure' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$flt = $form->InsereFltEditHttpPost("param1_periode", "param1_periode") ;
		$flt->Libelle = "Minutage" ;
		$this->InstalleCompMinutages($flt, true) ;
	}
	public function CondExec($aliasTable)
	{
		return "(".$aliasTable.".param1_periode = ''
or MINUTE(NOW()) DIV ".intval(60 / \Rpa2p\Config\ExecActivites::MINUTAGES)." = (".$aliasTable.".param2_periode - 1)
)" ;
	}
}
