<?php

namespace Pv\InterfPaiement\Skrill ;

class Transaction extends \Pv\InterfPaiement\Transaction
{
	public $SessionId ;
	public $IdMarchand ;
	public $IdTransactSkrill ;
	public $EmailMarchand ;
	public $EmailPayeur ;
}
