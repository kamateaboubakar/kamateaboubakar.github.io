<?php

namespace Pv\DB\Native ;

class SqlLite extends \Pv\DB\Connection\Connection
{
	public $VendorName = "SQLLITE" ;
	public $VendorMinVersion = "0" ;
	public $VendorMaxVersion = "2" ;
	public $DatabaseFilePath = "" ;
	public $OpenPermissions = 0666 ;
	public $ReadQueryType = SQLITE_ASSOC ;
	public $UseBuffer = 0 ;
	public function OpenCnx()
	{
		$this->OpenConnectionHandle() ;
		return ($this->Connection != false) ;
	}
	protected function ValidateDatabaseFilePath()
	{
		if(! file_exists($this->DatabaseFilePath))
		{
			$this->SetConnectionException("Le fichier ".$this->DatabaseFilePath." n'existe pas") ;
			return 0 ;
		}
		return 1 ;
	}
	protected function OpenConnectionHandle()
	{
		$OK = 0 ;
		try
		{
			$this->Connection = sqlite_open($this->DatabaseFilePath, $this->OpenPermissions, $this->ConnectionException) ;
			if($this->Connection != false)
			{
				$OK = 1 ;
			}
		}
		catch(Exception $ex)
		{
		}
	}
	protected function BuildDatabaseFilePath()
	{
		$this->DatabaseFilePath = $this->ConnectionParams["server"] ;
		if($this->ConnectionParams["schema"] != '')
		{
			$this->DatabaseFilePath .= '/'.$this->ConnectionParams["schema"] ;
		}
	}
	public function & OpenQuery($sqlText, $sqlParams=array())
	{
		$res = false ;
		if(! $this->InitConnection())
		{
			return $res ;
		}
		$this->ClearConnectionException() ;
		$this->CaptureQuery($sql, $params) ;
		$sql = $this->PrepareSql($sql, $params) ;
		try
		{
			if($this->UseBuffer)
			{
				$res = sqlite_query($sql, $this->Connection) ;
			}
			else
			{
				$res = sqlite_unbuffered_query($sql, $this->Connection) ;
			}
			$exceptionMsg = "" ;
			if(! $res)
			{
				$exceptionMsg = mysql_error($this->Connection) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, strval($res), $exceptionMsg) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		return $res ;
	}
	public function ReadQuery(& $query)
	{
		if($query == false)
		{
			return false ;
		}
		return sqlite_fetch_array($query, $this->ReadQueryType) ;
	}
	public function CloseQuery(& $query)
	{
		parent::CloseQuery($query) ;
		if($query == false)
		{
			return true ;
		}
		$query = false ;
	}
	public function EscapeParams($sqlText, $sqlParams=array())
	{
		$result = $sqlText ;
		foreach($sqlParams as $name => $value)
		{
			$result = str_replace($result, $this->ParamPrefix.$name, $this->EscapeRowValue($value)) ;
		}
		return $result ;
	}
	public function EscapeRowValue($rowValue)
	{
		return sqlite_escape_string($rowValue) ;
	}
	public function CloseCnx()
	{
		if($this->Connection == false)
			return 1 ;
		return sqlite_close($this->Connection) ;
	}
}