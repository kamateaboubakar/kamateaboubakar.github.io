<?php

namespace Pv\DB\Native ;

class Mysql extends \Pv\DB\Connection\Connection
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
	var $VendorMaxVersion = "6" ;
	var $UseBuffer = 1 ;
	var $StoredProcConnection = false ;
	function ExecFixCharacterEncoding()
	{
		// mysql_query('SET NAMES '.$this->CharacterEncoding, $this->Connection) ;
		mysql_query('SET CHARACTER SET '.$this->CharacterEncoding, $this->Connection) ;
		mysql_set_charset($this->CharacterEncoding, $this->Connection) ;
		/*
		*/
	}
	function SqlConcat($list)
	{
		if(count($list) == 0)
			return ;
		if(count($list) == 1)
			return $list[0] ;
		$sql = "CONCAT(" ;
		for($i=0; $i<count($list) ; $i++)
		{
			if($i > 0)
			{
				$sql .= ", " ;
			}
			$sql .= $list[$i] ;
		}
		$sql .= ")" ;
		return $sql ;
	}
	function SqlToDateTime($expr)
	{
		return "TIMESTAMP(".$expr.")" ;
	}
	function SqlToTimestamp($expr)
	{
		return "UNIX_TIMESTAMP(".$expr.")" ;
	}
	function SqlAddSeconds($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' SECOND)' ;
	}
	function SqlAddMinutes($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' MINUTE)' ;
	}
	function SqlAddHours($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' HOUR)' ;
	}
	function SqlAddDays($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' DAY)' ;
	}
	function SqlAddMonths($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' MONTH)' ;
	}
	function SqlAddYears($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return 'DATE_ADD('.$expr.', INTERVAL '.$val.' YEAR)' ;
	}
	function SqlDateDiff($expr1, $expr2)
	{
		return "TIME_TO_SEC(TIMEDIFF(".$expr1.", ".$expr2."))" ;
	}
	function SqlReplace($expr, $search, $replace, $start=0)
	{
		return "replace($expr, $search, $replace)" ;
	}
	function SqlLength($expr)
	{
		return "LENGTH($expr)" ;
	}
	function SqlSubstr($expr, $start, $length=0)
	{
		$str = "SUBSTR($expr, $start)" ;
		if($length > 0)
		{
			$str = "SUBSTR($expr, $start, $length)" ;
		}
		return $str ;
	}
	function SqlIndexOf($expr, $search, $start=0)
	{
		$str = "INSTR($expr, $search)" ;
		if($start > 0)
		{
			$str = "INSTR(substr($expr, $start), $search)" ;
		}
		return $str ;
	}
	function SqlNow()
	{
		return "NOW()" ;
	}
	function SqlIsNull($expr)
	{
		return "$expr IS NULL" ;
	}
	function SqlStrToDateTime($dateName)
	{
		return 'TIMESTAMP('.$dateName.')' ;
	}
	function SqlStrToDate($dateName)
	{
		return 'TIMESTAMP('.$dateName.')' ;
	}
	function SqlDatePart($dateName)
	{
		return 'DATE('.$dateName.')' ;
	}
	function SqlTimePart($dateName)
	{
		return 'TIME('.$dateName.')' ;
	}
	function SqlDateToStr($dateName)
	{
		return 'DATE_FORMAT('.$dateName.', \'%Y-%m-%d\')' ;
	}
	function SqlDateTimeToStr($dateName)
	{
		return 'DATE_FORMAT('.$dateName.', \'%Y-%m-%d %H:%i:%s\')' ;
	}
	function SqlDateToStrFr($dateName, $includeHour=0)
	{
		$format = '%d/%m/%Y' ;
		if($includeHour)
			$format .= ' %H:%i:%s' ;
		return 'DATE_FORMAT('.$dateName.', \''.$format.'\')' ;
	}
	function SqlToInt($expression)
	{
		return 'CONVERT ('.$expression.', SIGNED)' ;
	}
	function SqlToDouble($expression)
	{
		return 'CONVERT('.$expression.', DECIMAL(18, 3))' ;
	}
	function SqlToString($expression)
	{
		return 'CAST ('.$expression.' AS STRING)' ;
	}
	function CallStoredProcSql($procName, $params=array())
	{
		$sql = '' ;
		$sql .= 'CALL '.$procName.'(' ;
		$i = 0 ;
		foreach($params as $name => $value)
		{
			if($i > 0)
				$sql .= ', ' ;
			$sql .= $this->EscapeRowValue($value) ;
			$i++ ;
		}
		$sql .= ')' ;
		return $sql ;
	}
	function GetNextAutoIncValue($tableName)
	{
		$value = $this->FetchSqlValue(
			"SELECT AUTO_INCREMENT id
FROM information_schema.TABLES WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = :tableName",
			array("schema" => $this->ConnectionParams["schema"], "tableName" => $tableName),
			"id"
		) ;
		return $value ;
	}
	function OpenStoredProcCnx()
	{
		$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
		$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
		$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
		try
		{
			$this->StoredProcConnection = mysql_connect($server, $user, $password, false, 65536) ;
			if($this->StoredProcConnection !== false)
			{
				$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
				$OK = mysql_select_db($schema, $this->StoredProcConnection) ;
				if($OK === false)
				{
					$this->SetConnectionException(mysql_error($this->StoredProcConnection)) ;
					mysql_close($this->StoredProcConnection) ;
					$this->StoredProcConnection = false ;
				}
			}
			else
			{
				$this->SetConnectionException(mysql_error()) ;
			}
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		return ($this->StoredProcConnection !== false) ;
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
			$this->StoredProcQuery = mysql_query($this->CallStoredProcSql($procName, $params), $this->StoredProcConnection) ;
		}
		catch(Exception $ex)
		{
		}
		if($this->StoredProcQuery == false)
		{
			$this->SetConnectionException(mysql_error($this->StoredProcConnection)) ;
		}
		return $this->StoredProcQuery ;
	}
	function CloseStoredProc(& $res)
	{
		if($this->StoredProcQuery !== false)
		{
			mysql_free_result($this->StoredProcQuery) ;
			$this->StoredProcQuery = false ;
		}
		mysql_close($this->StoredProcConnection) ;
		$this->StoredProcConnection = false ;
		return 1 ;
	}
	function EscapeTableName($tableName)
	{
		return "`".$tableName."`" ;		
	}
	function EscapeVariableName($varName)
	{
		return "`".$varName."`" ;		
	}
	function EscapeFieldName($tableName, $fieldName)
	{
		return "`".$tableName."`.`".$fieldName."`" ;
	}
	function EscapeRowValue($rowValue)
	{
		// echo $rowValue."<br>" ;
		return "'".mysql_real_escape_string($rowValue)."'" ;
		// return "convert(cast(convert('".mysql_real_escape_string($rowValue)."' using  latin1) as binary) using utf8)" ;
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
			$this->Connection = mysql_connect($server, $user, $password) ;
			if(! $this->Connection)
			{
				$res = 0 ;
				$this->SetConnectionException(mysql_error()) ;
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
			$OK = mysql_select_db($schema, $this->Connection) ;
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
		return mysql_error($this->Connection) ;
	}
	function SetConnectionExceptionFromCnx()
	{
		return $this->SetConnectionException($this->ConnectionErrString()) ;
	}
	function & OpenQuery($sql, $params=array())
	{
		$res = false ;
		if(! $this->InitConnection())
		{
			return $res ;
		}
		$this->FixCharacterEncoding() ;
		$this->ClearConnectionException() ;
		$this->CaptureQuery($sql, $params) ;
		$sql = $this->PrepareSql($sql, $params) ;
		try
		{
			$res = mysql_unbuffered_query($sql, $this->Connection) ;
			// $res = mysql_unbuffered_query($sql, $this->Connection) ;
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
		if($res == false)
		{
			$this->AutoFinalConnection() ;
		}
		return $res ;
	}
	function ReadQuery(&$res)
	{
		$row = false;
		try
		{
			$row = mysql_fetch_assoc($res) ;
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
				$OK = mysql_free_result($res) ;
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
	function LimitSqlRowsReq($sql, $params=array(), $start=0, $limit=1000, $extra='')
	{
		$limit_clause = 'LIMIT '.intval($start).', '.intval($limit) ;
		if(stripos($sql, ' LIMIT ') === false)
		{
			return $sql.' '.$limit_clause ;
		}
		return 'select * from ('.$sql.') MAIN_REQ '.$limit_clause ;
	}
	public function CreateColumnDefinition()
	{
		return new \Pv\DB\SqlDef\Column\Mysql() ;
	}
	protected function ParamsColumnDefinitions($tableName, $schema='')
	{
		return array() ;
	}
	public function SqlColumnDefinitions($tableName, $schema='')
	{
		$requestTableName = $tableName ;
		$tableName = strtoupper($tableName) ;
		return 'DESCRIBE '.$this->EscapeTableName($requestTableName) ;
	}
	public function CreateTableDefinition()
	{
		return new \Pv\DB\SqlDef\Table\Mysql() ;
	}
	public function SqlTableDefinitions($schema='')
	{
		return 'SHOW TABLE STATUS' ;
	}
	function SqlEncrypt1($expression, $key)
	{
		return 'TO_BASE64(AES_ENCRYPT('.$expression.', '.$key.'))' ;
	}
	function SqlDecrypt1($expression, $key)
	{
		return 'AES_DECRYPT(FROM_BASE64('.$expression.'), '.$key.')' ;
	}
	function CloseCnx()
	{
		if(! $this->Connection || ! is_resource($this->Connection))
		{
			return 1 ;
		}
		try
		{
			$res = (mysql_close($this->Connection)) ? 1 : 0 ;
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