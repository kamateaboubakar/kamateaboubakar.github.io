<?php

namespace Pv\InterfPaiement\Paypal ;

class CompteMarchand extends \Pv\InterfPaiement\CompteMarchand
{
	public $ClientId ;
	public $Secret ;
	public $Monnaie = "XOF" ;
	public $TauxChange = 665 ;
}
