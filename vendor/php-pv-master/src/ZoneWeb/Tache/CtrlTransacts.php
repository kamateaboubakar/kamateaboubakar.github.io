<?php

namespace Pv\ZoneWeb\Tache ;

class CtrlTransacts extends \Pv\ZoneWeb\Tache\Tache
{
	public $DelaiExecution = 0.25 ;
	public $Message = "Verification des transactions en attente terminee" ;
	protected function ExecuteInstructions()
	{
		$zone = $this->ZoneParent() ;
		$interfsPaiement = $zone->ApplicationParent->InterfsPaiement() ;
		foreach($interfsPaiement as $i => $interfPaiement)
		{
			$interfPaiement->ControleTransactionsEnAttente() ;
		}
		echo $this->Message."\n" ;
	}
}