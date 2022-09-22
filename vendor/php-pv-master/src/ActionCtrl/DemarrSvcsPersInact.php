<?php

namespace Pv\ActionCtrl ;

class DemarrSvcsPersInact extends \Pv\ActionCtrl\ActionCtrl
{
	public function ExecuteArgs($args)
	{
		$nomServsPersists = array_keys($this->ApplicationParent->ServsPersists) ;
		foreach($nomServsPersists as $i => $nomServPersist)
		{
			echo "Service : $nomServPersist : " ;
			if($this->TacheCtrlParent->Etat != null && in_array($nomServPersist, $this->TacheCtrlParent->Etat->ServsPersistsDesact))
			{
				echo "annule\n" ;
				continue ;
			}
			$servPersist = & $this->ApplicationParent->ServsPersists[$nomServPersist] ;
			// print get_class($servPersist)." :\n" ;
			if(! $servPersist->EstServiceDemarre() || ! $servPersist->Verifie())
			{
				echo "arrete, demarrage initie\n" ;
				// echo get_class($servPersist)." doit etre redemarre\n" ;
				$servPersist->DemarreService() ;
				if($this->TacheCtrlParent->DelaiTransition > 0)
				{
					echo "pause de ".$this->TacheCtrlParent->DelaiTransition." sec\n" ;
					sleep($this->TacheCtrlParent->DelaiTransition) ;
				}
			}
			else
			{
				echo "en cours\n" ;
			}
		}
	}
}