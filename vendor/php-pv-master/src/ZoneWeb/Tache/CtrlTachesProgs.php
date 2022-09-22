<?php

namespace Pv\ZoneWeb\Tache ;

class CtrlTachesProgs extends \Pv\ZoneWeb\Tache\Tache
{
	public $DelaiTransition = 0 ;
	public $Message = "Verification des taches programmees terminee" ;
	protected function ExecuteInstructions()
	{
		$zone = $this->ZoneParent() ;
		$nomTaches = array_keys($zone->ApplicationParent->TachesProgs) ;
		foreach($nomTaches as $i => $nomTache)
		{
			$tacheProg = & $zone->ApplicationParent->TachesProgs[$nomTache] ;
			$tacheProg->LanceProcessus() ;
			if($this->DelaiTransition > 0)
			{
				sleep($this->DelaiTransition) ;
			}
		}
		echo $this->Message."\n" ;
	}
		}