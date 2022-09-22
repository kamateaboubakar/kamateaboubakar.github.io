<?php

namespace Pv\InterfPaiement\CoinPayments ;

class \Pv\InterfPaiement\CoinPayments\ResultConfirmIPN
{
	public $Methode = -1 ;
	public $CodeErreur = "non_initialise" ;
	public $Param1 ;
	public $Param2 ;
	public function ConfirmeSucces($methode=1)
	{
		$this->Methode = $methode ;
		$this->CodeErreur = "" ;
	}
	public function RenseigneErreur($methode=1, $codeErreur="")
	{
		$this->Methode = $methode ;
		$this->CodeErreur = $codeErreur ;
	}
	public function EstSucces()
	{
		return $this->CodeErreur == "" ;
	}
}
