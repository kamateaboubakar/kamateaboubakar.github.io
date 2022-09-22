<?php

namespace Pv\TacheCtrl ;

class ProgsApp extends \Pv\TacheCtrl\TacheCtrl
{
	public $DelaiTransition = 0 ;
	protected function ExecuteSession()
	{
		$nomTaches = array_keys($this->ApplicationParent->TachesProgs) ;
		foreach($nomTaches as $i => $nomTache)
		{
			$tacheProg = & $this->ApplicationParent->TachesProgs[$nomTache] ;
			if($tacheProg->NomElementApplication == $this->NomElementApplication)
			{
				continue ;
			}
			$tacheProg->LanceProcessus() ;
			if($this->DelaiTransition > 0)
			{
				sleep($this->DelaiTransition) ;
			}
		}
		echo $this->Message."\n" ;
	}
}