<?php

namespace Pv\TacheProg ;

class CtrlTachesWeb extends \Pv\TacheProg\TacheProg
{
	public $NomsZone = array() ;
	protected function ExecuteSession()
	{
		echo "Demarrage des verifications...\n" ;
		foreach($this->NomsZone as $i => $nomZone)
		{
			if(! isset($this->ApplicationParent->IHMs[$nomZone]))
			{
				continue ;
			}
			$zone = & $this->ApplicationParent->IHMs[$nomZone] ;
			$zone->ChargeConfig() ;
			$zone->DemarreTachesWeb() ;
		}
		echo "Fin.\n" ;
	}
}