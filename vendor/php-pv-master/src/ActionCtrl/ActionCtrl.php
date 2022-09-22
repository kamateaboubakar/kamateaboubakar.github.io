<?php

namespace Pv\ActionCtrl ;

class ActionCtrl extends \Pv\Objet\Objet
{
	public $NomElementTacheCtrl ;
	public $TacheCtrlParent ;
	public $ApplicationParent ;
	public function AdopteTacheCtrl($nom, & $tacheCtrl)
	{
		$this->NomElementTacheCtrl = $nom ;
		$this->TacheCtrlParent = & $tacheCtrl ;
		$this->ApplicationParent = & $tacheCtrl->ApplicationParent ;
	}
	public function ExecuteArgs($args)
	{
	}
}