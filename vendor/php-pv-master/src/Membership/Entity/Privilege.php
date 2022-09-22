<?php

namespace Pv\Membership\Entity ;

class Privilege extends Row
{
	public $Id = "" ;
	public $Name = "" ;
	public $RoleId = "" ;
	public $Title = "" ;
	public $Description = "" ;
	public $Enabled = 0 ;
	protected function ImportConfigFromRowValue($name, $value)
	{
		$success = parent::ImportConfigFromRowValue($name, $value) ;
		if($success)
		{
			return $success ;
		}
		$success = 1 ;
		switch($name)
		{
			case "PRIVILEGE_ID" :
			{
				$this->Id = $value ;
			}
			break ;
			case "ROLE_ID" :
			{
				$this->RoleId = $value ;
			}
			break ;
			case "ROLE_NAME" :
			{
				$this->Name = $value ;
			}
			break ;
			case "ROLE_TITLE" :
			{
				$this->Title = $value ;
			}
			break ;
			case "ROLE_DESCRIPTION" :
			{
				$this->Description = $value ;
			}
			break ;
			case "PRIVILEGE_ENABLED" :
			{
				$this->Enabled = $value ;
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