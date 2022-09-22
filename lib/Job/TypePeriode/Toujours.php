<?php

namespace Rpa2p\Job\TypePeriode ;

class Toujours extends TypePeriode
{
	public function Id()
	{
		return 'toujours' ;
	}
	public function Titre()
	{
		return 'Toujours' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$flt = $form->InsereFltEditFixe("param1_periode", '', "param1_periode") ;
		$flt = $form->InsereFltEditFixe("param2_periode", '', "param2_periode") ;
	}
	public function CondExec($aliasTable)
	{
		return "(1=1)" ;
	}
}
