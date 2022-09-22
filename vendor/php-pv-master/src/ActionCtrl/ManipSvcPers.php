<?php

namespace Pv\ActionCtrl ;

class ManipSvcPers extends \Pv\ActionCtrl\ActionCtrl
{
	public $ServPersistSelect ;
	protected function ExtraitNomServPersist($args)
	{
		$result = "" ;
		if(isset($args["serv_persist"]) && isset($this->TacheCtrlParent->ApplicationParent->ServsPersists[$args["serv_persist"]]))
		{
			$result = $args["serv_persist"] ;
		}
		return $result ;
	}
	public function ExecuteArgs($args)
	{
		$nomServPersist = $this->ExtraitNomServPersist($args) ;
		if($nomServPersist == "")
		{
			return ;
		}
		$this->ServPersistSelect = & $this->TacheCtrlParent->ApplicationParent->ServsPersists[$nomServPersist] ;
		$this->ExecuteManip($args) ;
	}
	protected function ExecuteManip($args)
	{
	}
}