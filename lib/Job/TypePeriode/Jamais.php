<?php

namespace Rpa2p\Job\TypePeriode ;

class Jamais extends TypePeriode
{
	public function Id()
	{
		return 'jamais' ;
	}
	public function Titre()
	{
		return 'Jamais' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$flt = $form->InsereFltEditFixe("param1_periode", '', "param1_periode") ;
		$flt = $form->InsereFltEditFixe("param2_periode", '', "param2_periode") ;
	}
	public function CondExec($aliasTable)
	{
		return "(1=0)" ;
	}
}
