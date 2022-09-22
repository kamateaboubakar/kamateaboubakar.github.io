<?php

namespace Pv\ActionCtrl ;

class DemarreSvcPers extends \Pv\ActionCtrl\ManipSvcPers
{
	protected function ExecuteManip($args)
	{
		$this->ServPersistSelect->DemarreService() ;
		$this->TacheCtrlParent->ActiveServPersist($this->ServPersistSelect->NomElementApplication) ;
	}
}