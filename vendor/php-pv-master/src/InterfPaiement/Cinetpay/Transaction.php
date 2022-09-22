<?php

namespace Pv\InterfPaiement\Cinetpay ;

class Transaction extends \Pv\InterfPaiement\Transaction
{
	public $Signature ;
	public $Monnaie = 'CFA' ;
	public $ConfigPaiement ;
	public $ActionPage ;
	public $Version ;
	public $DatePaiement ;
	public $MethodePaiement ;
	public $Msisdn ;
	public $Indicatif ;
	public $ApiKey ;
	public $SiteId ;
}
