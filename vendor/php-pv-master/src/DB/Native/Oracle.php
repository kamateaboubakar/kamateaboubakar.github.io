<?php

namespace Pv\DB\Native ;

class Oracle extends \Pv\DB\Connection\Connection
{
/**
* Nom de la marque de la base de donnees supportee (ORACLE)
* @var string $VendorName
*/
	var $VendorName = "ORACLE" ;
/**
* Nom de la version minimum de la base de donnees ORACLE supportee.
* @var string $VendorMinVersion
*/
	var $VendorMinVersion = "6" ;
/**
* Nom de la version maximum de la base de donnees ORACLE supportee.
* @var string $VendorMaxVersion
*/
	var $VendorMaxVersion = "11" ;
/**
* Attributs complementaires de connexion dans le TNS.
* @var array TnsConnectDataParams
*/
	var $TnsConnectDataParams = array() ;
	var $TnsGlobalParams = array() ;
	var $TnsAddressListParams = array() ;
	var $TnsAddresses = array() ;
	public $StoredProcCursorName = 'CursorResult' ;
	public $StoredProcQueryActive = false ;
	public $StoredProcCursor = false ;
	public $StoredProcQuery = false ;
	public $OracleCharacterSet = "AL32UTF8" ;
	// public $OracleCharacterSet = "AL32UTF8" ;
	public function ImportConfigFromNode(& $node)
	{
		parent::ImportConfigFromNode($node) ;
		if(isset($node["attrs"]))
		{
			foreach($node["attrs"] as $name => $value)
			{
				if(stripos($name, 'CONNECTDATA') === 0)
				{
					$this->TnsConnectDataParams[str_replace('CONNECTDATA', '', $name)] = $value ;
				}
			}
		}
	}
	function EscapeTableName($tableName)
	{
		return "\"".$tableName."\"" ;		
	}
	function EscapeVariableName($varName)
	{
		return "\"".$varName."\"" ;		
	}
	function EscapeFieldName($tableName, $fieldName)
	{
		return "\"".$tableName."\".\"".$fieldName."\"" ;
	}
	function EscapeRowValue($rowValue)
	{
		return "q['".str_replace("'", "''", $rowValue)."']" ;
	}
	function OpenCnx()
	{
		$this->ClearConnectionException() ;
		$OK = $this->ConnectCnx() ;
		return $OK ;
	}
	function ExtractTNSName($server, $schema)
	{
		$tnsName = '' ;
		if($schema != '')
		{
			if(preg_match('/^([A-Z0-9_\.\-]+)/', $server, $host_match))
			{
				$port = (isset($this->ConnectionParams["port"])) ? $this->ConnectionParams["port"] : 1521 ;
				if(preg_match('/(^\d+)$/', $server, $port_match))
				{
					$port = $port_match[1] ;
				}
				$serviceName = (isset($this->TnsConnectDataParams["SID"])) ? "SID" : "SERVICE_NAME" ;
				$tnsName = '(DESCRIPTION =
(ADDRESS_LIST ='."\n" ;
				if(count($this->TnsAddresses))
				{
					foreach($this->TnsAddresses as $i => $tnsAddress)
					{
						$tnsName .= '(ADDRESS = (PROTOCOL = '.$tnsAddress->Protocol.')(HOST = '.$tnsAddress->Host.')(PORT = '.$tnsAddress->Port.'))'."\n" ;
					}
				}
				if($server != '')
				{
					$tnsName .= '(ADDRESS = (PROTOCOL = TCP)(HOST = '.$host_match[1].')(PORT = '.$port.'))'."\n" ;
				}
				foreach($this->TnsAddressListParams as $n => $v)
				{
					$tnsName .= '('.$n.' = '.$v.')'."\n" ;
				}
				$tnsName .= ')'."\n" ;
				foreach($this->TnsGlobalParams as $n => $v)
				{
					$tnsName .= '('.$n.' = '.$v.')'."\n" ;
				}
				$tnsName .= '(CONNECT_DATA =
('.$serviceName.' = '.$schema.')'."\n" ;
				foreach($this->TnsConnectDataParams as $n => $v)
				{
					if($n == "SID")
						continue ;
					$tnsName .= '('.$n.' = '.$v.')'."\n" ;
				}
				$tnsName .= ')'."\n" ;
				$tnsName .= ')'."\n" ;
			}
			else
			{
				$tnsName = $server.'/'.$schema ;
			}
		}
		else
		{
			$tnsName = $server ;
		}
		// echo $tnsName ;
		return $tnsName ;
	}
	function ConnectCnx()
	{
		$res = 0 ;
		try
		{
			$server = (isset($this->ConnectionParams["server"])) ? $this->ConnectionParams["server"] : "localhost" ;
			$schema = (isset($this->ConnectionParams["schema"])) ? $this->ConnectionParams["schema"] : "" ;
			$tnsName = $this->ExtractTNSName($server, $schema) ;
			$user = (isset($this->ConnectionParams["user"])) ? $this->ConnectionParams["user"] : "root" ;
			$password = (isset($this->ConnectionParams["password"])) ? $this->ConnectionParams["password"] : "" ;
			$this->LastConnectionString = $user.":".$password."@".$tnsName."\n" ;
			if($this->OracleCharacterSet == '')
			{
				$this->Connection = oci_connect($user, $password, $tnsName) ;
			}
			else
			{
				$this->Connection = oci_connect($user, $password, $tnsName, $this->OracleCharacterSet) ;
			}
			if(! $this->Connection)
			{
				$res = 0 ;
				$this->SetConnectionExceptionFromOciError(oci_error()) ;
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
	function & PrepareSql($sql, $params=array(), $realParamNames=array())
	{
		$this->CaptureQuery($sql, $params) ;
		$stmt = false ;
		try
		{
			$stmt = oci_parse($this->Connection, $sql) ;
			if($stmt !== false)
			{
				// $params = $this->SortSqlParam($params) ;
				// print $sql.' '.print_r($params, true)."\n" ;
				foreach($params as $n => $v)
				{
					// echo $params[$n]."\n" ;
					oci_bind_by_name($stmt, $this->ParamPrefix.$n, $params[$n]) ;
				}
				if($this->StoredProcQueryActive && $this->StoredProcUseCursor)
				{
					$this->StoredProcCursor = oci_new_cursor($this->Connection) ;
					oci_bind_by_name($stmt, $this->ParamPrefix.$this->StoredProcCursorName, $this->StoredProcCursor, -1, OCI_B_CURSOR);
				}
			}
			else
			{
				$this->SetConnectionExceptionFromOciError(oci_error($this->Connection)) ;
			}
		}
		catch(Exception $ex)
		{
		}
		return $stmt ;
	}
	function & OpenQuery($sql, $params=array())
	{
		$stmt = false ;
		if(! $this->InitConnection())
		{
			return $stmt ;
		}
		$this->ClearConnectionException() ;
		$stmt = $this->PrepareSql($sql, $params) ;
		if($stmt === false)
		{
			return $stmt ;
		}
		$res = false ;
		try
		{
			$res = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS) ;
			$exceptionMsg = "" ;
			if(! $res)
			{
				// print $sql ;
				// print_r(array($sql, $params)) ;
				$exceptionMsg = $this->ReadErrorMsg(oci_error($stmt)) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, strval($res), $exceptionMsg) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		if($res && ! $stmt)
		{
			return $res ;
		}
		elseif($res && $stmt)
		{
			return $stmt ;
		}
		else
		{
			if($stmt !== false)
			{
				oci_free_statement($stmt) ;
				$stmt = false ;
			}
			$this->AutoFinalConnection() ;
			return $stmt ;
		}
	}
	function SetConnectionExceptionFromOciError($errorData)
	{
		$this->SetConnectionException($this->ReadErrorMsg($errorData)) ;
	}
	function ReadErrorMsg($errorData)
	{
		$result = '' ;
		if(isset($errorData["message"]))
		{
			$result .= $errorData["message"] ;
		}
		return $result ;
	}
	public function ColumnsQuery(&$res)
	{
		if($res === false)
		{
			return false ;
		}
		$cols = array() ;
		$colCount = oci_num_fields($res) ;
		for($i=1; $i<=$colCount; $i++)
		{
			$cols[] = oci_field_name($res, $i) ;
		}
		return $cols ;
	}
	function ReadQuery(&$res)
	{
		$row = false;
		try
		{
			$row = oci_fetch_array($res, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS) ;
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
				$OK = oci_free_statement($res) ;
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
	function CallStoredProcSql($procName, $params=array())
	{
		$sql = '' ;
		$sql .= 'BEGIN '.$procName.'(' ;
		$i = 0 ;
		foreach($params as $name => $value)
		{
			if($name != $this->ExprKeyName)
			{
				$paramString = "" ;
				if($i > 0)
				{
					$sql .= ', ' ;
				}
				if(isset($params[$this->ExprKeyName][$name]))
				{
					$paramString = str_replace(
						$this->ExprParamPattern,
						$this->ParamPrefix.$name,
						$params[$this->ExprKeyName][$name]
					) ;
				}
				else
				{
					$paramString = $this->ParamPrefix.$name ;
				}
				$i++ ;
				$sql .= $paramString ;
			}
		}
		if($this->StoredProcUseCursor)
		{
			if($i > 0)
			{
				$sql .= ', ' ;
			}
			$sql .= $this->ParamPrefix.$this->StoredProcCursorName ;
		}
		$sql .= ') ;' ;
		$sql .= 'END ;' ;
		// echo $sql ;
		return $sql ;
	}
	function & OpenStoredProc($procName, $params=array())
	{
		$this->StoredProcQueryActive = true ;
		$this->StoredProcQuery = parent::OpenStoredProc($procName, $params) ;
		if($this->StoredProcUseCursor)
		{
			oci_execute($this->StoredProcCursor, OCI_DEFAULT) ;
			return $this->StoredProcCursor ;
		}
		return $this->StoredProcQuery ;
	}
	function CloseStoredProc(& $res)
	{
		$this->StoredProcQueryActive = false ;
		$this->StoredProcQuery = false ;
		$ok = $this->CloseQuery($res) ;
		$this->StoredProcCursor = false ;
		return $ok ;
	}
	function CallStoredProcSqlInto($procName, $params=array(), $outParams=array())
	{
		$sql = '' ;
		$sql .= 'BEGIN '.$procName.'(' ;
		$i = 0 ;
		foreach($params as $name => $value)
		{
			if($name != $this->ExprKeyName)
			{
				$paramString = "" ;
				if($i > 0)
				{
					$sql .= ', ' ;
				}
				if(isset($params[$this->ExprKeyName][$name]))
				{
					$paramString = str_ireplace(
						$this->ExprParamPattern,
						$this->ParamPrefix.$name,
						$params[$this->ExprKeyName][$name]
					) ;
				}
				else
				{
					$paramString = $this->ParamPrefix.$name ;
				}
				$i++ ;
				$sql .= $paramString ;
			}
		}
		foreach($outParams as $j => $outName)
		{
			if($j > 0 || $i > 0)
			{
				$sql .= ", " ;
			}
			$sql .= $this->ParamPrefix.$outName ;
		}
		$sql .= ') ;' ;
		$sql .= 'END ;' ;
		// echo $sql ;
		return $sql ;
	}
	function & PrepareSqlInto($sql, & $outResults, $params=array())
	{
		$this->CaptureQuery($sql, $params) ;
		$stmt = false ;
		try
		{
			$stmt = oci_parse($this->Connection, $sql) ;
			if($stmt !== false)
			{
				// print $sql.' '.print_r($params, true)."\n" ;
				foreach($params as $n => $v)
				{
					oci_bind_by_name($stmt, $this->ParamPrefix.$n, $params[$n]) ;
				}
				foreach($outResults as $n => $v)
				{
					oci_bind_by_name($stmt, $this->ParamPrefix.$n, $outResults[$n], 255) ;
				}
			}
			else
			{
				$this->SetConnectionExceptionFromOciError(oci_error($this->Connection)) ;
			}
		}
		catch(Exception $ex)
		{
		}
		return $stmt ;
	}
	function FetchStoredProcInto($storedProc, $params=array(), $outParams=array())
	{
		$stmt = false ;
		if(! $this->InitConnection())
		{
			return $stmt ;
		}
		$this->ClearConnectionException() ;
		$outResults = \Pv\Misc::array_fill_keys($outParams, "") ;
		$sql = $this->CallStoredProcSqlInto($storedProc, $params, $outParams) ;
		$stmt = $this->PrepareSqlInto($sql, $outResults, $params) ;
		if($stmt === false)
		{
			return $stmt ;
		}
		$res = false ;
		try
		{
			$res = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS) ;
			$exceptionMsg = "" ;
			if(! $res)
			{
				$exceptionMsg = $this->ReadErrorMsg(oci_error($stmt)) ;
				$this->SetConnectionException($exceptionMsg) ;
				$res = false ;
			}
			$this->LaunchSqlProfiler($sql, strval($res), $exceptionMsg) ;
		}
		catch(Exception $ex)
		{
			$this->SetConnectionException($ex->getMessage()) ;
		}
		if($stmt !== false)
		{
			oci_free_statement($stmt) ;
			$stmt = false ;
		}
		$this->AutoFinalConnection() ;
		if($res)
		{
			return $outResults ;
		}
		else
		{
			return false ;
		}
	}
	function LimitSqlRowsReq($sql, $params=array(), $start=0, $limit=1000, $extra='')
	{
		$sql = 'select * from 
	(
		select * from (
			select MAIN_REQ.*, ROWNUM ROW_POS from ('.$sql.') MAIN_REQ
		) where ROW_POS < '.($start + 1 + $limit).'
	) where ROW_POS >= '.($start + 1) ;
		return $sql ;
	}
	public function CreateColumnDefinition()
	{
		return new \Pv\DB\SqlDef\Column\Oracle() ;
	}
	public function SqlColumnDefinitions($tableName, $schema='')
	{
		return 'select t1.*, case when t2.COLUMN_NAME IS NULL THEN 0 ELSE 1 END IS_KEY from (
select * from cols WHERE UPPER(cols.table_name) =UPPER(:table_name)
) t1
left join (
SELECT cols.CONSTRAINT_NAME CONSTRAINT_NAME, COLUMN_NAME FROM all_constraints cons, all_cons_columns cols WHERE cols.table_name =:table_name AND cons.constraint_type = \'P\' AND cons.constraint_name = cols.constraint_name AND cons.owner = cols.owner ORDER BY cols.position
) t2
on t1.COLUMN_NAME = t2.COLUMN_NAME' ;
	}
	protected function ParamsColumnDefinitions($tableName, $schema='')
	{
		return array('table_name' => $tableName) ;
	}
	public function CreateTableDefinition()
	{
		return new \Pv\DB\SqlDef\Table\Oracle() ;
	}
	public function ParamsTableDefinitions($schema='')
	{
		if($schema == '' && isset($this->ConnectionParams["user"]))
			$schema = $this->ConnectionParams["user"] ;
		return array("schema" => $schema) ;
	}
	public function SqlTableDefinitions($schema='')
	{
		return 'select * from SYS.all_tables where owner=:schema' ;
	}
	function CloseCnx()
	{
		if(! $this->Connection)
		{
			return 1 ;
		}
		try
		{
			$res = (oci_close($this->Connection)) ? 1 : 0 ;
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
	function SqlConcat($list)
	{
		$sql = '' ;
		for($i=0; $i<count($list) ; $i++)
		{
			if($i > 0)
			{
				$sql .= " || " ;
			}
			$sql .= $list[$i] ;
		}
		return $sql ;
	}
	function SqlAddDays($expr, $val)
	{
		return "cast(".$expr." as date) + ".$val ;
	}
	function SqlAddHours($expr, $val)
	{
		return "cast(".$expr." as date) + (".$val." / 24)" ;
	}
	function SqlAddMinutes($expr, $val)
	{
		return "cast(".$expr." as date) + (".$val." / (24 * 60))" ;
	}
	function SqlAddSeconds($expr, $val)
	{
		return "cast(".$expr." as date) + (".$val." / (24 * 60 * 60))" ;
	}
	function SqlAddMonths($expr, $val)
	{
		return "add_months(cast(".$expr." as date), ".$val.")" ;
	}
	function SqlAddYears($expr, $val)
	{
		return "add_months(cast(".$expr." as date), ".$val." * 12)" ;
	}
	function SqlDateDiff($expr1, $expr2)
	{
		return "(cast(".$expr1." as date) - cast(".$expr2." as date)) * 24*60*60" ;
	}
	function SqlToInt($expr1)
	{
		return "CAST(".$expr1." AS INTEGER)" ;
	}
	function SqlToDouble($expr1)
	{
		return "CAST(".$expr1." AS DECIMAL)" ;
	}
	function SqlNow()
	{
		return "SYSDATE" ;
	}
	function SqlDateExpr($dateValue)
	{
		
	}
	function SqlIndexOf($expr, $search, $start=0)
	{
		if($start == 0)
			return "instr($expr, $search)" ;
		return "instr($expr, $search, $start)" ;
	}
	function SqlDateToStr($dateName)
	{
		return "TO_CHAR(".$dateName.", 'YYYY-MM-DD HH24:MI:SS')" ;
	}
}