<?php

namespace Rpa2p\ServPersist ;

class Principale extends ServPersist
{
	public $DelaiAttente = 5 ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::SERV_PERSIST_PRINC ;
	}
	protected function ExecuteSession()
	{
		echo "Service en cours d'exécution :!\n" ;
	}
}