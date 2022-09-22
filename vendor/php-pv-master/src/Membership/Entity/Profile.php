<?php

namespace Pv\Membership\Entity ;

class Profile extends Row
{
	public $Privileges = array() ;
	protected $ImportRowIndex = -1 ;
	public function ImportConfigFromRows($rows)
	{
		$this->ImportRowIndex = 0 ;
		foreach($rows as $i => $row)
		{
			if($i == 0)
			{
				$this->ImportConfigFromRow($row) ;
			}
			$this->ImportRowIndex = $i ;
			$privilege = $this->CreatePrivilege() ;
			$privilege->ImportConfigFromRow($row) ;
			$this->Privileges[$privilege->Name] = $privilege ;
		}
	}
	protected function ImportConfigFromRowValue($name, $value)
	{
		$success = parent::ImportConfigFromRowValue($name, $value) ;
		if($success)
			return 1 ;
		$success = 1 ;
		switch($name)
		{
			case "PROFILE_ID" :
			{
				$this->Id = $value ;
			}
			break ;
			case "PROFILE_TITLE" :
			{
				$this->Title = $value ;
			}
			break ;
			case "PROFILE_DESCRIPTION" :
			{
				$this->Name = $value ;
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
	protected function CreatePrivilege()
	{
		return new \Pv\Membership\Entity\Privilege() ;
			}
		}