<?php

namespace Pv\Membership\Ldap ;

class Auth
{
	public $Host = "" ;
	public $Port = 636 ;
	protected $Connection = false ;
	protected $ErrorMessage = "" ;
	protected $ErrorNo = "" ;
	public $ProtocolV1Enabled = 1 ;
	public $ProtocolV2Enabled = 1 ;
	protected function ClearError()
	{
		$this->SetError(0, '') ;
	}
	protected function SetError($errorNo, $errorMessage)
	{
		$this->ErrorCode = $errorCode ;
		$this->ErrorMessage = $errorMessage ;
	}
	protected function SetConnectionError()
	{
		$this->SetError(ldap_errno($this->Connection), ldap_error($this->Connection)) ;
	}
	protected function Open()
	{
		try { $this->Connection = ldap_connect($this->Host, $this->Port) ; } catch(Exception $ex) { $this->ConnectionError = $ex->getMessage() ; }
		if($this->Connection == false)
		{
			$this->SetError(-2, 'Impossible de se connecter au serveur ldap '.$this->Host.':'.$this->Port) ;
		}
		if($this->Connection !== false)
		{
			if($this->ProtocolV1Enabled)
			{
				$ok = ldap_set_option($this->Connection, LDAP_OPT_PROTOCOL_VERSION, 3) ;
				if(! $ok)
					$this->SetError(-2, "Protocole Ldap V1 inapplicable");
			}
			if($this->ProtocolV2Enabled)
			{
				$ok = ldap_set_option($this->Connection, LDAP_OPT_REFERRALS, 0) ;
				if(! $ok)
					$this->SetError(-2, "Protocole Ldap V2 inapplicable");
			}
			$this->SetConnectionOptions() ;
		}
		return ($this->Connection != false) ? 1 : 0 ;
	}
	protected function SetConnectionOptions()
	{
	}
	public function Verify($user, $password)
	{
		if(! $this->Open())
		{
			return 0 ;
		}
		$link = ldap_bind($this->Connection, $user, $password);
		$ok = ($link != false) ? 1 : 0 ;
		if(! $ok)
		{
			$this->SetErrorFromConnection() ;
		}
		else
		{
			ldap_unbind($link) ;
		}
		$this->Close() ;
		return $ok ;
	}
	protected function Close()
	{
		unset($this->Connection) ;
		$this->Connection = false ;
	}
}