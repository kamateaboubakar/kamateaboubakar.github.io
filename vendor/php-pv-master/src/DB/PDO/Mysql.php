<?php

namespace Pv\DB\PDO ;

class Mysql extends \Pv\DB\Native\Mysql
{
	public $SetCharacterEncodingOnFetch = 1 ;
	public $AutoSetCharacterEncoding = 1 ;
	public $CharacterEncoding = "utf8" ;
	public $OpenOptions = array() ;
	function ExecFixCharacterEncoding()
	{
		if($this->CharacterEncoding != '')
		{
			$this->Connection->exec('SET NAMES '.$this->CharacterEncoding) ;
		}
	}
	function EscapeRowValue($rowValue)
	{
		$cnx = (is_object($this->StoredProcConnection)) ? $this->StoredProcConnection : $this->Connection ;
		return "'".$cnx->quote($rowValue)."'" ;
	}
	function OpenCnx()
	{
		$this->ClearConnectionException() ;
		return $this->ConnectCnx() ;
	}
	function ExtractConnectionString()
	{
		$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
		$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
		$port = (isset($this->ConnectionParams["port"])) ? $this->ConnectionParams["port"] : "" ;
		$connectionStr = "mysql:host=$server";
		if($port != "")
		{
			$connectionStr .= ";port=$port" ;
		}
		$connectionStr .= ";dbname=$schema" ;
		if($this->CharacterEncoding != "" && $this->MustSetCharacterEncoding == 1)
		{
			$connectionStr .= ";charset=".$this->CharacterEncoding ;
		}
		return $connectionStr ;
	}
	function ConnectCnx()
	{
		$res = 0 ;
		try
		{
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$connectionStr = $this->ExtractConnectionString() ;
			$this->Connection = new \PDO($connectionStr, $user, $password, $this->OpenOptions) ;
			if($this->Connection)
			{
				$this->Connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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
		$cnx = (is_object($this->StoredProcConnection)) ? $this->StoredProcConnection : $this->Connection ;
		if(! is_object($cnx))
		{
			return null ;
		}
		$errorCode = $cnx->errorCode() ;
		if($errorCode !== null && $errorCode !== "00000")
		{
			$errorInfo = $cnx->errorInfo() ;
			return $errorCode." : ".$errorInfo[2] ;
		}
		return "" ;
	}
	protected function ExtractStmtException(& $stmt)
	{
		$errorCode = $stmt->errorCode() ;
		if($errorCode !== null && $errorCode !== "00000")
		{
			$errorInfo = $stmt->errorInfo() ;
			return $errorCode." : ".$errorInfo[2] ;
		}
		return "" ;
	}
	protected function SetConnectionExceptionFromStmt(& $stmt)
	{
		return $this->SetConnectionException($this->ExtractStmtException($stmt)) ;
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
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$connectionStr = $this->ExtractConnectionString() ;
			$this->StoredProcConnection = new \PDO($connectionStr, $user, $password) ;
			if($this->StoredProcConnection)
			{
				$this->StoredProcConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$res = 1 ;
			}
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
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
			$this->StoredProcQuery = $this->StoredProcConnection->query($this->CallStoredProcSql($procName, $params)) ;
		}
		catch(PDOException $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
			if(isset($res) && is_object($res))
			{
				$res->closeCursor() ;
				$res = false ;
			}
		}
		if($this->ConnectionException != '' && $this->StoredProcQuery == false)
		{
			$this->SetConnectionExceptionFromStmt($this->StoredProcConnection) ;
		}
		return $this->StoredProcQuery ;
	}
	function CloseStoredProc(& $res)
	{
		if($this->StoredProcQuery !== false)
		{
			$this->StoredProcQuery->closeCursor() ;
			$this->StoredProcQuery = null ;
			$this->StoredProcQuery = false ;
		}
		$this->StoredProcConnection = null ;
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
		foreach($params as $name => $val)
		{
			if(is_int($name))
			{
				$sql = str_replace(":".$name, ":param_".$name, $sql) ;
			}
		}
		$exceptionMsg = '' ;
		$this->CaptureQuery($sql, $params) ;
		$this->FixCharacterEncoding() ;
		$res = false ;
		try
		{
			$res = $this->Connection->prepare($sql) ;
			if(! $res)
			{
				$this->SetConnectionExceptionFromCnx() ;
				$res = false ;
				return $res ;
			}
			$paramsBound = array() ;
			foreach($params as $name => $val)
			{
				$paramType = \PDO::PARAM_STR ;
				if(is_int($val))
				{
					$paramType = \PDO::PARAM_INT ;
				}
				elseif(is_null($val))
				{
					$params[$name] = '' ;
				}
				if(is_int($name))
				{
					$res->bindParam("param_".$name, $params[$name], $paramType) ;
					$paramsBound["param_".$name] = $val ;
				}
				else
				{
					$res->bindParam($name, $params[$name], $paramType) ;
					$paramsBound[$name] = $val ;
				}
			}
			$ok = $res->execute($paramsBound) ;
			if($res->errorCode() !== null && $res->errorCode() !== "00000")
			{
				$this->SetConnectionExceptionFromStmt($res) ;
				$errInfo = $res->errorInfo() ;
				$exceptionMsg = $errInfo[2] ;
				$res = null ;
				$res = false ;
			}
			// echo $sql."<br>" ;
			$this->LaunchSqlProfiler($sql, ($res) ? "pdo_mysql_object" : '', $exceptionMsg) ;
		}
		catch(\PDOException $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
			if(is_object($res))
			{
				$res->closeCursor() ;
			}
			$res = false ;
		}
		return $res ;
	}
	function ReadQuery(&$res)
	{
		$row = false;
		try
		{
			$row = $res->fetch(\PDO::FETCH_ASSOC) ;
			if(is_array($row))
			{
				$row = array_map(array(& $this, "DecodeRowValue"), $row) ;
			}
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
			$row = false ;
		}
		return $row ;
	}
	function CloseQuery(&$res)
	{
		try
		{
			if(is_object($res))
			{
				$OK = $res->closeCursor() ;
				if($OK)
				{
					$res = null ;
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
		$this->Connection = null ;
		$this->Connection = false ;
		return 1 ;
	}
}
