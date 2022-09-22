<?php

namespace Pv\Membership\Entity ;

class Member extends Row
{
	public $StoreData = 1 ;
	public $Id ;
	public $Login ;
	public $Password ;
	public $FirstName ;
	public $LastName ;
	public $Email ;
	public $Enable ;
	public $ADActivated = 0 ;
	public $MustChangePassword = 0 ;
	public $TotalLoginAttempt = 0 ;
	public $Contact = "" ;
	public $Address = "" ;
	public $ProfileId = 0;
	public $Profile = null;
	public $ParentMembership = null;
	public $ProfileClassName = "\Pv\Membership\Entity\Profile" ;
	protected function InitConfig(& $parent)
	{
		parent::InitConfig($parent) ;
		$this->ParentMembership = & $parent ;
	}
	public function LoadConfig()
	{
		$this->Profile = $this->CreateProfile() ;
	}
	protected function CreateProfile()
	{
		$className = $this->ProfileClassName ;
		$profile = null ;
		if(! class_exists($className))
			return ;
		$profile = new $className() ;
		return $profile ;
	}
	public function ImportConfigFromRow($row)
	{
		parent::ImportConfigFromRow($row) ;
		$this->LoadProfile($this->ProfileId) ;
	}
	public function LoadProfile($profileId)
	{
		$rows = $this->ParentMembership->FetchProfileRows($profileId) ;
		// print $this->ParentMembership->Database->LastSqlText ;
		if($rows == null)
			return null ;
		$this->Profile->ImportConfigFromRows($rows) ;
	}
	protected function ImportConfigFromRowValue($name, $value)
	{
		$success = parent::ImportConfigFromRowValue($name, $value) ;
		if($success)
			return 1 ;
		$success = 1 ;
		switch($name)
		{
			case "MEMBER_ID" :
			{
				$this->Id = $value ;
			}
			break ;
			case "MEMBER_LOGIN" :
			{
				$this->Login = $value ;
			}
			break ;
			case "MEMBER_PASSWORD" :
			{
				$this->Password = $value ;
			}
			break ;
			case "MEMBER_EMAIL" :
			{
				$this->Email = $value ;
			}
			break ;
			case "MEMBER_FIRST_NAME" :
			{
				$this->FirstName = $value ;
			}
			break ;
			case "MEMBER_LAST_NAME" :
			{
				$this->LastName = $value ;
			}
			break ;
			case "MEMBER_ENABLE" :
			{
				$this->Enable = $value ;
			}
			break ;
			case "MEMBER_CONTACT" :
			{
				$this->Contact = $value ;
			}
			break ;
			case "MEMBER_ADDRESS" :
			{
				$this->Address = $value ;
			}
			break ;
			case "MEMBER_AD_ACTIVATED" :
			{
				$this->ADActivated = $value ;
			}
			break ;
			case "MEMBER_MUST_CHANGE_PASSWORD" :
			{
				$this->MustChangePassword = $value ;
			}
			break ;
			case "MEMBER_PROFILE" :
			{
				$this->ProfileId = $value ;
			}
			break ;
			default :
			{
				$success = 0 ;
			}
			break ;
		}
		return $success ;
	}
}