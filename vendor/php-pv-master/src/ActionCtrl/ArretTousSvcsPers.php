<?php

namespace Pv\ActionCtrl ;

class ArretTousSvcsPers extends \Pv\ActionCtrl\ActionCtrl
{
	public function ExecuteArgs($args)
	{
		$nomServsPersists = array_keys($this->TacheCtrlParent->ApplicationParent->ServsPersists) ;
		foreach($nomServsPersists as $i => $nomServPersist)
		{
			$servPersist = & $this->ApplicationParent->ServsPersists[$nomServPersist] ;
			$servPersist->ArreteService() ;
			if(in_array($nomServPersist, $this->TacheCtrlParent->Etat->ServsPersistsDesact))
			{
				continue ;
			}
			$this->TacheCtrlParent->Etat->ServsPersistsDesact[] = $nomServPersist ;
		}
		$this->TacheCtrlParent->SauveEtat() ;
	}
}