<?php

namespace Pv\ApiRestful\Auth ;

class Auth
{
	public $MessageErreur ;
	public function IdentifieMembre(& $api, $login, $motPasse)
	{
		return 0 ;
	}
	public function CreeSession(& $api, $idMembre, $device)
	{
		return 0 ;
	}
	public function SupprimeSession(& $api)
	{
		return 0 ;
	}
	public function ChargeSession(& $api)
	{
		return 0 ;
	}
}