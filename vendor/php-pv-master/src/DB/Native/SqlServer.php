<?php

namespace Pv\DB\Native ;

class SqlServer extends \Pv\DB\Connection\Connection
{
	var $VendorName = "SQLSERVER" ;
	var $LoginTimeout = 60 ;
	var $OpenOptions = array() ;
	function ExecFixCharacterEncoding()
	{
	}
	function EscapeVariableName($varName)
	{
		return "[".$varName."]" ;		
	}
	function EscapeTableName($tableName)
	{
		return "[".$tableName."]" ;		
	}
	function EscapeFieldName($tableName, $fieldName)
	{
		return "[".$tableName."].[".$fieldName."]" ;
	}
	/*
	function EscapeRowValue($rowValue)
	{
		if(is_numeric($rowValue))
			return $rowValue;
		$unpacked = unpack('H*hex', $rowValue);
		return '0x' . $unpacked['hex'];
	}
	*/
	function OpenCnx()
	{
		$this->ClearConnectionException() ;
		$OK = 0 ;
		if($this->ConnectCnx())
		{
			$OK = $this->SelectDBCnx() ;
		}
		return $OK ;
	}
	function ConnectCnx()
	{
		$res = 0 ;
		try
		{
			$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
			$connectionInfo = array_merge($this->OpenOptions, array("Database" => $schema, "UID" => $user, "PWD" => $password)) ;
			if($this->AutoSetCharacterEncoding && $this->CharacterEncoding != '')
			{
				$connectionInfo["CharacterSet"] = strtoupper($this->CharacterEncoding) ;
			}
			if($this->LoginTimeout > 0)
			{
				$connectionInfo["LoginTimeout"] = $this->LoginTimeout ;
			}
			$this->Connection = sqlsrv_connect($server, $connectionInfo) ;
			if(! $this->Connection)
			{
				$res = 0 ;
				$this->SetConnectionException(sqlsrv_errors($this->Connection)) ;
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
	function SelectDBCnx()
	{
		return true ;
	}
	function ConnectionErrString()
	{
		return sqlsrv_errors($this->Connection) ;
	}
	function SetConnectionExceptionFromCnx()
	{
		return $this->SetConnectionException($this->ConnectionErrString()) ;
	}
	function SetConnectionException($exception='')
	{
		if(is_array($exception))
		{
			$msg = '' ;
			foreach($exception as $i => $error)
			{
				$msg .= (($i > 0) ? "\n" : '').$error["message"] ;
			}
			parent::SetConnectionException($msg) ;
		}
		else
		{
			parent::SetConnectionException($exception) ;
		}
	}
	function & OpenQuery($sql, $params=array())
	{
		$res = false ;
		if(! $this->InitConnection())
		{
			return $res ;
		}
		$this->ClearConnectionException() ;
		$this->CaptureQuery($sql, $params) ;
		$this->FixCharacterEncoding() ;
		$paramRegex = preg_quote($this->ParamPrefix).'([a-z0-9\_]+)' ;
		preg_match_all('/'.$paramRegex.'/i', $sql, $matches) ;
		$realParams = array() ;
		$paramNames = array() ;
		foreach($matches[1] as $i => $match)
		{
			if(isset($params[$match]))
			{
				$realParams[] = $params[$match] ;
				if(! in_array($match, $paramNames))
				{
					$paramNames[] = $match ;
				}
			}
		}
		if(count($paramNames) > 0)
		{
			rsort($paramNames) ;
			foreach($paramNames as $j => $n)
			$sql = str_ireplace($this->ParamPrefix.$n, '?', $sql) ;
		}
		$res = false ;
		try
		{
			$res = sqlsrv_query($this->Connection, $sql, $realParams) ;
			$exceptionMsg = "" ;
			if(! $res)
			{
				$exceptionMsg = sqlsrv_errors(SQLSRV_ERR_ALL) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, ($res) ? "sqlsrv_object" : '', $exceptionMsg) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		return $res ;
	}
	function ReadQuery(&$res)
	{
		$row = false;
		try
		{
			$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC) ;
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
				$OK = sqlsrv_free_stmt($res) ;
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
			$res = (sqlsrv_close($this->Connection)) ? 1 : 0 ;
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