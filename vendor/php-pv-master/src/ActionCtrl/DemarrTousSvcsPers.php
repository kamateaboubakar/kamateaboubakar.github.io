<?php

namespace Pv\ActionCtrl ;

class DemarrTousSvcsPers extends \Pv\ActionCtrl\ActionCtrl
{
	public function ExecuteArgs($args)
	{
		$nomServsPersists = array_keys($this->TacheCtrlParent->ApplicationParent->ServsPersists) ;
		foreach($nomServsPersists as $i => $nomServPersist)
		{
			$servPersist = & $this->ApplicationParent->ServsPersists[$nomServPersist] ;
			$servPersist->ArreteService() ;
		}
		$this->TacheCtrlParent->Etat->ServsPersistsDesact = array() ;
		$this->TacheCtrlParent->SauveEtat() ;
	}
}