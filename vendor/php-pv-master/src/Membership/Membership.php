<?php

namespace Pv\Membership ;

class Membership extends \Pv\Membership\Object\Item
{
	public $ParentArea = null ;
	public $MemberLogged = null ;
	public $MemberClassName = '\Pv\Membership\Entity\Member' ;
	public $GuestMemberId = "" ;
	public $UseGuestMember = 0 ;
	public $RootMemberId = "" ;
	public $UseRootMember = 1 ;
	public $SessionMemberId = 0 ;
	public $CryptSessionValues = true ;
	public $SessionMemberKey = "login" ;
	public $UpdateTimeKey = "update_time" ;
	public $SessionSource = "SESSION" ;
	public $SessionTimeout = 0 ;
	public $LastUpdateTime = 0 ;
	public $SessionInactiveFound = 0 ;
	protected function InitConfig(& $parent)
	{
		$this->ParentArea = & $parent ;
	}
	public function CreateSessionCrypter()
	{
		$crypter = new \Pv\Openssl\Crypter() ;
		$crypter->key = str_replace('\\', '.', get_class($this)) ;
		return $crypter ;
	}
	public function EncodeSessionValue($value)
	{
		if($this->CryptSessionValues == false)
		{
			return $value ;
		}
		$crypter = $this->CreateSessionCrypter() ;
		return $crypter->encode($value) ;
	}
	public function DecodeSessionValue($value)
	{
		if($this->CryptSessionValues == false)
		{
			return $value ;
		}
		$crypter = $this->CreateSessionCrypter() ;
		return $crypter->decode($value) ;
	}
	public function GetSessionValue($key, $defaultValue=false)
	{
		$value = $defaultValue ;
		switch(strtoupper($this->SessionSource))
		{
			case "SESSION" :
			case "SESSIONS" :
			{
				// print_r($_SESSION) ;
				if(isset($_SESSION[$key]))
					$value = $this->DecodeSessionValue($_SESSION[$key]) ;
			}
			break ;
			case "COOKIE" :
			case "COOKIES" :
			{
				if(isset($_COOKIE[$key]))
					$value = $this->DecodeSessionValue($_COOKIE[$key]) ;
			}
			break ;
		}
		return $value ;
	}
	public function SetSessionValue($key, $value="")
	{
		switch(strtoupper($this->SessionSource))
		{
			case "SESSION" :
			case "SESSIONS" :
			{
				if($value === null)
					unset($_SESSION[$key]) ;
				else
					$_SESSION[$key] = $this->EncodeSessionValue($value) ;
			}
			break ;
			case "COOKIE" :
			case "COOKIES" :
			{
				setcookie($key, $this->EncodeSessionValue($value)) ;
			}
			break ;
		}
	}
	public function LoadSession()
	{
		if($this->SessionTimeout > 0)
		{
			$this->LastUpdateTime = $this->GetSessionValue($this->UpdateTimeKey) ;
			if($this->UpdateTimeKey !== false && date("U") - $this->LastUpdateTime > $this->SessionTimeout * 60)
			{
				$this->ClearSession() ;
			}
			$this->SetSessionValue($this->UpdateTimeKey, date("U")) ;
		}
		$this->SessionMemberId = $this->GetSessionValue($this->SessionMemberKey) ;
		// print 'Sssion ID : '.$this->SessionMemberId ;
		if($this->SessionMemberId === false && $this->UseGuestMember && $this->GuestMemberId != false)
		{
			$this->SessionMemberId = $this->GuestMemberId ;
		}
		$this->MemberLogged = $this->NullValue() ;
		// exit ;
		if(! empty($this->SessionMemberId))
			$this->MemberLogged = $this->FetchMember($this->SessionMemberId) ;
	}
	public function LoadMember($id)
	{
		$this->SessionMemberId = $id ;
		if($this->SessionMemberId === false && $this->UseGuestMember && $this->GuestMemberId != false)
		{
			$this->SessionMemberId = $this->GuestMemberId ;
		}
		$this->MemberLogged = $this->NullValue() ;
		if(! empty($this->SessionMemberId))
			$this->MemberLogged = $this->FetchMember($this->SessionMemberId) ;
	}
	public function SaveSession($memberId)
	{
		$this->SetSessionValue($this->SessionMemberKey, $memberId) ;
		if($this->SessionTimeout > 0)
		{
			$this->SetSessionValue($this->UpdateTimeKey, date("U")) ;
		}
	}
	public function ClearSession()
	{
		$this->SetSessionValue($this->SessionMemberKey, null) ;
		if($this->SessionTimeout > 0)
		{
			$this->SetSessionValue($this->UpdateTimeKey, null) ;
		}
	}
	public function ValidateConnection($login, $password)
	{
		return $this->IdMemberNotFoundValue ;
	}
	public function LogonMember($memberId)
	{
		$this->SaveSession($memberId) ;
	}
	public function LogoutMember($memberId)
	{
		$this->ClearSession() ;
	}
	public function FetchMemberRow($memberId)
	{
		return array() ;
	}
	public function FetchProfileRows($profileId)
	{
		return array() ;
	}
	protected function CreateMember()
	{
		$member = $this->NullValue() ;
		$className = $this->MemberClassName ;
		if(class_exists($className))
		{
			$member = new $className() ;
			$member->ParentMembership = & $this ;
		}
		return $member ;
	}
	public function FetchMember($memberId)
	{
		$row = $this->FetchMemberRow($memberId) ;
		return $this->FetchMemberFromRow($row) ;
	}
	public function FetchMemberRowByLogin($login)
	{
		$row = $this->FetchMemberRowByLogin($memberId) ;
		return $this->FetchMemberFromRow($row) ;
	}
	public function FetchMemberFromRow($row)
	{
		$member = $this->NullValue() ;
		if(empty($row))
		{
			return $member ;
		}
		$member = $this->CreateMember() ;
		$member->LoadConfig() ;
		if($member != null)
		{
			$member->ImportConfigFromRow($row) ;
		}
		return $member ;
	}
	public function FetchMemberByLogin($login)
	{
		$row = $this->FetchMemberRowByLogin($memberId) ;
		return $this->FetchMemberFromRow($row) ;
	}
	public function InsertMemberRow($memberRow)
	{
		return 0 ;
	}
	public function UpdateMemberRow($memberId, $memberRow)
	{
		return 0 ;
	}
	public function DeleteMemberRow($memberId)
	{
		return 0 ;
	}
	public function InsertProfileRow($profileRow)
	{
		return 0 ;
	}
	public function UpdateProfileRow($profileId, $profileRow)
	{
		return 0 ;
	}
	public function DeleteProfileRow($profileId)
	{
		return 0 ;
	}
	public function InsertRoleRow($roleRow)
	{
		return 0 ;
	}
	public function UpdateRoleRow($roleId, $roleRow)
	{
		return 0 ;
	}
	public function DeleteRoleRow($roleId)
	{
		return 0 ;
	}
	public function FetchMemberRange($start, $max, $filters=array())
	{
		return 0 ;
	}
	public function FetchMemberTotal($filters=array())
	{
		return 0 ;
	}
	public function FetchProfileRange($start, $max, $filters=array())
	{
		return 0 ;
	}
	public function FetchProfileTotal($filters=array())
	{
		return 0 ;
	}
	public function FetchRoleRange($start, $max, $filters=array())
	{
		return 0 ;
	}
	public function FetchRoleTotal($filters=array())
	{
		return 0 ;
	}
	public function Run()
	{
		// echo "mmmm" ;
		$this->LoadSession() ;
	}
}