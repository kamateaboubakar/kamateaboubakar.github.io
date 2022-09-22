<?php

namespace Pv\DB\Native ;

class Mysqli extends \Pv\DB\Native\Mysql
{
/**
* Nom de la marque de la base de donnees supportee (MYSQL)
* @var string $VendorName
*/
	var $VendorName = "MYSQL" ;
/**
* Nom de la version minimum de la base de donnees MYSQL supportee.
* @var string $VendorMinVersion
*/
	var $VendorMinVersion = "4" ;
/**
* Nom de la version maximum de la base de donnees MYSQL supportee.
* @var string $VendorMaxVersion
*/
	var $VendorMaxVersion = "7" ;
	function ExecFixCharacterEncoding()
	{
		// $ok = mysqli_query('SET NAMES '.$this->CharacterEncoding, $this->Connection) ;
		if(is_resource($this->Connection))
		{
			mysqli_query($this->Connection, 'SET CHARACTER SET '.$this->CharacterEncoding) ;
			mysqli_set_charset($this->Connection, $this->CharacterEncoding) ;
		}
	}
	function EscapeTableName($tableName)
	{
		return "`".$tableName."`" ;		
	}
	function EscapeFieldName($tableName, $fieldName)
	{
		return "`".$tableName."`.`".$fieldName."`" ;
	}
	function EscapeRowValue($rowValue)
	{
		$cnx = (is_object($this->StoredProcConnection)) ? $this->StoredProcConnection : $this->Connection ;
		return "'".mysqli_escape_string($cnx, $rowValue)."'" ;
	}
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
			$this->Connection = mysqli_connect($server, $user, $password) ;
			if(! $this->Connection)
			{
				$res = 0 ;
				$this->SetConnectionException(mysqli_error($this->Connection)) ;
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
		$res = 0 ;
		if(! $this->Connection)
		{
			return $res ;
		}
		try
		{
			$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
			$OK = mysqli_select_db($this->Connection, $schema) ;
			if($OK === false)
			{
				$this->SetConnectionExceptionFromCnx() ;
				$res = 0 ;
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
		return mysqli_error((is_object($this->StoredProcConnection)) ? $this->StoredProcConnection : $this->Connection) ;
	}
	function SetConnectionExceptionFromCnx()
	{
		return $this->SetConnectionException($this->ConnectionErrString()) ;
	}
	function OpenStoredProcCnx()
	{
		$res = 0 ;
		try
		{
			$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$this->StoredProcConnection = mysqli_connect($server, $user, $password) ;
			if(! $this->StoredProcConnection)
			{
				$res = 0 ;
				$this->SetConnectionException(mysqli_error($this->StoredProcConnection)) ;
			}
			else
			{
				$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
				$OK = mysqli_select_db($this->StoredProcConnection, $schema) ;
				if($OK === false)
				{
					$this->SetConnectionExceptionFromCnx() ;
					$res = 0 ;
				}
				else
				{
					$res = 1 ;
				}
			}
		}
		catch(Exception $ex)
		{
			$this->SetStoredProcConnectionException($ex->getMessage()) ;
		}
		return $res ;
	}
	function & OpenStoredProc($procName, $params=array())
	{
		$this->StoredProcQuery = false ;
		if(! $this->OpenStoredProcCnx())
		{
			return $this->StoredProcQuery ;
		}
		try
		{
			$this->StoredProcQuery = mysqli_query($this->StoredProcConnection, $this->CallStoredProcSql($procName, $params)) ;
		}
		catch(Exception $ex)
		{
		}
		if($this->StoredProcQuery == false)
		{
			$this->SetConnectionException(mysqli_error($this->StoredProcConnection)) ;
		}
		return $this->StoredProcQuery ;
	}
	function CloseStoredProc(& $res)
	{
		if($this->StoredProcQuery !== false)
		{
			mysqli_free_result($this->StoredProcQuery) ;
			$this->StoredProcQuery = false ;
		}
		mysqli_close($this->StoredProcConnection) ;
		$this->StoredProcConnection = false ;
		return 1 ;
	}
	function & OpenQuery($sql, $params=array())
	{
		if(! $this->InitConnection())
		{
			return false ;
		}
		$this->ClearConnectionException() ;
		$this->CaptureQuery($sql, $params) ;
		$this->FixCharacterEncoding() ;
		$sql = $this->PrepareSql($sql, $params) ;
		$res = false ;
		try
		{
			$res = mysqli_query($this->Connection, $sql) ;
			$exceptionMsg = "" ;
			if(! $res)
			{
				$exceptionMsg = mysqli_error($this->Connection) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, ($res) ? "mysqli_object" : '', $exceptionMsg) ;
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
			$row = mysqli_fetch_assoc($res) ;
			if(is_array($row))
			{
				$row = array_map(array(& $this, "DecodeRowValue"), $row) ;
			}
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
				$OK = mysqli_free_result($res) ;
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
			$res = (mysqli_close($this->Connection)) ? 1 : 0 ;
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