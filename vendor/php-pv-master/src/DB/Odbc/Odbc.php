<?php

namespace Pv\DB\Odbc ;

class Odbc extends \Pv\DB\Connection\Connection
{
	var $DriverKey = "" ;
	var $DsnServerKey = "Server" ;
	var $DsnSchemaKey = "Database" ;
	function EscapeTableName($tableName)
	{
		return "[".$tableName."]" ;		
	}
	function EscapeFieldName($tableName, $fieldName)
	{
		return "[".$tableName."].[".$fieldName."]" ;
	}
	function EscapeRowValue($rowValue)
	{
		return "'".str_replace("'", "''", $rowValue)."'" ;
	}
	function ExtractDsn($server, $schema)
	{
		$dsn = "" ;
		if($this->DriverKey != "")
		{
			$dsn .= 'Driver={'.$this->DriverKey.'}' ;
			if($server != '')
			{
				$dsn .= ';'.$this->DsnServerKey.'='.$server ;
			}
			if($schema != "")
			{
				$dsn .= ';'.$this->DsnSchemaKey.'='.$schema ;
			}
		}
		else
		{
			if($server != "")
			{
				$dsn = $server ;
			}
		}
		return $dsn ;
	}
	function OpenCnx()
	{
		$this->ClearConnectionException() ;
		$OK = $this->ConnectCnx() ;
		return $OK ;
	}
	function ConnectCnx()
	{
		$res = false ;
		try
		{
			$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
			$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$dsn = $this->ExtractDsn($server, $schema) ;
			$this->Connection = odbc_connect($dsn, $user, $password) ;
			if(! $this->Connection)
			{
				$res = 0 ;
				$this->SetConnectionException(odbc_errormsg()) ;
			}
			else
			{
				$res = 1 ;
			}
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		return $res ;
	}
	function ConnectionErrString()
	{
		return odbc_errormsg($this->Connection) ;
	}
	function SetConnectionExceptionFromCnx()
	{
		return $this->SetConnectionException($this->ConnectionErrString()) ;
	}
	function & PrepareSql($sql, $params=array(), $realParamNames=array())
	{
		$stmt = false ;
		try
		{
			$stmtSql =  $this->ReplaceParamsToSql($sql, $realParamNames, "?") ;
			$stmt = odbc_prepare($this->Connection, $stmtSql) ;
		}
		catch(Exception $ex)
		{
		}
		return $stmt ;
	}
	function & OpenQuery($sql, $params=array())
	{
		if(! $this->InitConnection())
		{
			return false ;
		}
		$this->ClearConnectionException() ;
		$realParamNames = $this->ExtractParamsFromSql($sql, $params) ;
		$stmt = $this->PrepareSql($sql, $params, $realParamNames) ;
		$res = false ;
		try
		{
			$stmtParams = $this->ExtractParamValues($realParamNames, $params) ;
			$res = odbc_execute($stmt, $stmtParams) ;
			$exceptionMsg = "" ;
			if(! $res)
			{
				$exceptionMsg = odbc_errormsg($this->Connection) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, ($res) ? "odbc_object" : '', $exceptionMsg) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		if(! $res)
			return false ;
		return $stmt ;
	}
	function ReadQuery(&$res)
	{
		$row = false;
		try
		{
			$row = odbc_fetch_array($res) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		return $row ;
	}
	function CloseQuery(&$res)
	{
		try
		{
			if(is_resource($res))
			{
				$OK = odbc_free_result($res) ;
				if($OK)
				{
					$res = false ;
				}
			}
		}
		catch(Exception $ex)
		{
		}
		$this->AutoFinalConnection() ;
	}
	function CloseCnx()
	{
		if(! $this->Connection)
		{
			return 1 ;
		}
		try
		{
			$res = (odbc_close($this->Connection)) ? 1 : 0 ;
			if($res)
			{
				$this->Connection = false ;
			}
		}
		catch(Exception $ex)
		{
		}
		return $res ;
	}
}