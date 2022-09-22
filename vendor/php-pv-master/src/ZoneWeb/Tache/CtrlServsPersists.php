<?php

namespace Pv\ZoneWeb\Tache ;

class CtrlServsPersists extends \Pv\ZoneWeb\Tache\Tache
{
	public $DelaiTransition = 0 ;
	public $Message = "Verification des services persistants terminee" ;
	protected function ExecuteInstructions()
	{
		$zone = $this->ZoneParent() ;
		$nomServsPersists = array_keys($zone->ApplicationParent->ServsPersists) ;
		foreach($nomServsPersists as $i => $nomServPersist)
		{
			$servPersist = & $zone->ApplicationParent->ServsPersists[$nomServPersist] ;
			if(! $servPersist->EstServiceDemarre() || ! $servPersist->Verifie())
			{
				$servPersist->DemarreService() ;
				if($this->DelaiTransition > 0)
				{
					sleep($this->DelaiTransition) ;
				}
			}
		}
		echo $this->Message."\n" ;
	}
}