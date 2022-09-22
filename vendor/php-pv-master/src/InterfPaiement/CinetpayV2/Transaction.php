<?php

namespace Pv\InterfPaiement\CinetpayV2 ;

class Transaction extends \Pv\InterfPaiement\Transaction
{
	public $Monnaie = 'XOF' ;
	public $DatePaiement ;
	public $Msisdn ;
	public $Indicatif ;
	public $ApiKey ;
	public $SiteId ;
	public $Channels = "ALL" ;
	public $CustomerId ;
	public $CustomerName ;
	public $CustomerSurname ;
	public $CustomerPhoneNumber ;
	public $CustomerEmail ;
	public $CustomerAddress ;
	public $CustomerCity ;
	public $CustomerCountry = "US" ;
	public $CustomerState = "AL" ;
	public $CustomerZipCode ;
}
