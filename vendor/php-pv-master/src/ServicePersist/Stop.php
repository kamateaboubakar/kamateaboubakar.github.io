<?php

namespace Pv\ServicePersist ;

class Stop extends \Pv\TacheCtrl\TacheCtrl
{
	protected function ExecuteSession()
	{
		$nomServsPersists = array_keys($this->ApplicationParent->ServsPersists) ;
		foreach($nomServsPersists as $i => $nomServPersist)
		{
			$servPersist = & $this->ApplicationParent->ServsPersists[$nomServPersist] ;
			$servPersist->ArreteService() ;
		}
		echo $this->Message."\n" ;
	}
}