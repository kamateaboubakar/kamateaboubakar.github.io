<?php

namespace Pv\DB\Connection ;

class Connection
{
/**
* mixed Connection Resource ID, depends on the database type.
* @var mixed $Connection
*/
	var $Connection = false ;
/**
* Array of Connection Parameters. The keys are : user, password, server, schema.
* @var array $ConnectionParams
*/
	var $ConnectionParams = array() ;
/**
* Array of Field strutures. The keys are the name of the tables.
* @var array $FieldsCache
*/
	var $FieldsCache = array() ;
/**
* Close automatically the connection when a query has been closed. Use them if you want to run one query by connection request.
* @var bool $AutoCloseConnection
*/
	var $AutoCloseConnection = true ;
/**
* Connection Exception Message.
* @var string $ConnectionException
*/
	var $ConnectionException = "" ;
/**
* Prefix for parameters.
* @var string $ParamPrefix
*/
	var $ParamPrefix = ":" ;
/**
* Pattern for parameters name, used to identify them into sql string
* @var string $ParamPattern
*/
	var $ParamPatternName = "[0-9a-z\_]" ;
/**
* Prefix used for creating parameter values used for inserting or updating rows in a table.
* @var string $NewValuePrefix
*/
	var $NewValuePrefix = 'NEW' ;
/**
 Nom de la clé du tableau des expressions
 @var string $ExprKeyName
*/
	var $ExprKeyName = "__EXPRS" ;
/**
 Modèle qui correspond au nom de la colonne dans chaque expression
 @var string $ExprKeyName
*/
	var $ExprParamPattern = "<SELF>" ;
/**
* Enable Sql Profiler for the capture of different sqls executed.
* @var bool $EnableSqlProfiler
*/
	var $EnableSqlProfiler = false ;
/**
* The file path where the differents sqls captured has been saved. You must leave it blank if you want to store it into a variable (more slow when sql number increase).
* @var string $SqlProfilerOutputFile
*/
	var $SqlProfilerOutputFile = '' ;
/**
* String variable which store different sqls captured when no file output has been set.
* @var string $SqlProfilerOutput
*/
	var $SqlProfilerOutput = '' ;
/**
* Error string when an exception has been raised during storing the sql captured.
* @var string $SqlProfilerError
*/
	var $SqlProfilerError = '' ;
/**
* Connection string when an exception has been raised during storing the sql captured.
* @var array $LastConnectionString
*/
	var $LastConnectionString = "" ;
/**
* Error string when an exception has been raised during storing the sql captured.
* @var array $LastSqlText
*/
	var $LastSqlText = "" ;
/**
* Error string when an exception has been raised during storing the sql captured.
* @var array $LastSqlParams
*/
	var $LastSqlParams = array() ;
/**
* Nom de la marque de la base de donnees supportee
* @var string $VendorName
*/
	var $VendorName = "" ;
/**
* Nom de la version minimum de marque de la base de donnees supportee.
* @var string $VendorMinVersion
*/
	var $VendorMinVersion = "" ;
/**
* Nom de la version maximum de marque de la base de donnees supportee.
* @var string $VendorMaxVersion
*/
	var $VendorMaxVersion = "" ;
	
	var $AutoUpperTableName = 1 ;
	
	var $StoredProcUseCursor = 1 ;
	
	var $AutoSetCharacterEncoding = 0 ;
	
	var $MustSetCharacterEncoding = 0 ;
	var $SetCharacterEncodingOnFetch = 0 ;
	
	var $CharacterEncodingFixed = 0 ;
	
	var $CharacterEncoding = "utf-8" ;
	
	public function __destruct()
	{
		if(! $this->AutoCloseConnection && $this->Connection != false)
		{
			$this->FinalConnection() ;
		}
	}
	public function ShutdownScript()
	{
		if(! $this->AutoCloseConnection)
			$this->FinalConnection() ;
	}
	public function RegisterShutdownScript()
	{
		if($this->AutoCloseConnection)
		{
			register_shutdown_function(array($this, 'ShutdownScript')) ;
		}
	}
	public function ImportConfigFromNode(& $node)
	{
	}
/**
* Capture SQL texts, their results and the exception generated.
* @access protected
* @param string $sql Sql text captured.
* @param string $resType Resource, result of the sql's execution.
* @param string $exceptionMsg Exception found when executing sql.
*/
	function LaunchSqlProfiler($sql, $resType="", $exceptionMsg="")
	{
		if(! $this->EnableSqlProfiler)
		{
			return ;
		}
		$entry = date("Y-m-d H:i:s")."\t".$sql."\t".$resType."\t".$exceptionMsg."\n" ;
		if($this->SqlProfilerOutputFile == "")
		{
			$this->SqlProfilerOutput .= $entry ;
		}
		else
		{
			try
			{
				$fh = fopen($this->SqlProfilerOutput, "a") ;
				if($fh !== false)
				{
					fputs($fh, $entry) ;
					fclose($fh) ;
				}
			}
			catch(Exception $ex)
			{
				$this->SqlProfilerError = $ex->getMessage() ;
			}
		}
	}
/**
* Add the NewValuePrefix to all the keys of the rowData. Used to generate the new row datas for updates or insertions
* @access protected
* @param array Array representing a row of some table.
* @return array The input paameters with keys updated.
*/
	function ApplyNewValuePrefix(& $rowData)
	{
		$newValue = array() ;
		foreach($rowData as $fieldName => $fieldValue)
		{
			if($fieldName == $this->ExprKeyName)
			{
				$newValue[$fieldName] = $fieldValue ;
				continue ;
			}
			$newValue[$this->NewValuePrefix.$fieldName] = $fieldValue ;
		}
		return $newValue ;
	}
/**
* Clear the connection exception message. It is used before beginning an operation which can raise an exception.
* @access protected
*/
	function ClearConnectionException()
	{
		$this->SetConnectionException("") ;
	}
/**
* Set the connection exception message with the new message.
* @access protected
* @param string $exceptionMsg
*/
	function SetConnectionException($exceptionMsg="")
	{
		$this->ConnectionException = $exceptionMsg ;
	}
/**
* Escape the table name to prevent the special caracters into the table.
* @access public
* @param string The table name to escape
* @return string The table name escaped
*/
	function EscapeTableName($tableName)
	{
		return "" ;
	}
/**
* Escape the field name to prevent the special caracters in the field's name.
* @access public
* @param string The table name containing the field name
* @param string The field name to escape
* @return string
*/
	function EscapeFieldName($tableName, $fieldName)
	{
		return "" ;
	}
/**
* Escape the variable name so the name is interpreted as a string.
* @access public
* @param string The variable name to escape
* @return string
*/
	function EscapeVariableName($varName)
	{
		return "" ;
	}
/**
* Escape into sql string value the value.
* @access public
* @param string The value to escape
* @return string
*/
	function EscapeRowValue($rowValue)
	{
		return "" ;
	}
/**
* Return a 'false' condition that can be used into a sql table
* @access public
* @return string
*/
	function FalseCond()
	{
		return "1 = 0" ;
	}
/**
* Return a 'true' condition that can be used into a sql table
* @access public
* @return string
*/
	function TrueCond()
	{
		return "1 = 1" ;
	}
/**
* Prepare a sql text to be executed, testing the sql and binding parameters.
* @access public
* @param string $sql Sql text to be prepared.
* @param array $params List of parameters to bind.
* @return mixed
*/
	public function SortSqlParams($params)
	{
		uksort($params, array($this, "SortSqlParamSpec")) ;
		return $params ;
	}
	public function SortSqlParamSpec($param1, $param2)
	{
		return strlen("$param1") < strlen("$param2") ;
	}
	public function GetParamListFromValues($prefix, $values)
	{
		$params = array() ;
		$i = 0 ;
		foreach($values as $name => $val)
		{
			$params[$prefix.$i] = $val ;
			$i++ ;
		}
		return $params ;
	}
	public function ExtractExprFromParams($params, $separator=",")
	{
		$ctn = '' ;
		foreach($params as $paramName => $val)
		{
			if($ctn != '')
			{
				$ctn .= $separator ;
			}
			$ctn .= $this->ParamPrefix.$paramName ;
		}
		return $ctn ;
	}
	function & PrepareSql($sql, $params=array(), $realParamNames=array())
	{
		$sql_res = $sql ;
		$params = $this->SortSqlParams($params) ;
		// print_r($params) ;
		foreach($params as $n => $v)
		{
			$sql_res = str_replace($this->ParamPrefix.$n, $this->EscapeRowValue($v), $sql_res) ;
		}
		return $sql_res ;
	}
/**
* Open the connection resource and return if the operation succeeds.
* @access protected
* @return bool
*/
	function OpenCnx()
	{
		$this->ClearConnectionException() ;
	}
/**
* Close the connection resource and return if the operation succeeds.
* @access protected
* @return bool
*/
	protected function CloseCnx()
	{
	}
/**
* Open a new query by executing the sql text to the database server.
* @access public
* @param string $sql SQL text to execute
* @param array $params Parameters
* @return mixed
*/
	public function & OpenQuery($sql, $params=array())
	{
		$this->LaunchSqlProfiler($sql) ;
		$res = null ;
		return $res ;
	}
/**
* Fetch the fields from the query.
* @access public
* @param resource $res Query Reader resource
* @return array|false
*/
	public function ColumnsQuery(&$res)
	{
		if($res === false)
		{
			return false ;
		}
		return array() ;
	}
/**
* Fetch the next row present in the Query Reader.
* @access public
* @param resource $res Query Reader resource
* @return array|false
*/
	function ReadQuery(&$res)
	{
		return false;
	}
/**
* Close the Query Reader.
* @access public
* @param resource $res Query Reader resource
* @return bool
*/
	function CloseQuery(&$res)
	{
		$this->AutoFinalConnection() ;
	}
	public function SqlColumnDefinitions($tableName, $schema='')
	{
		return "" ;
	}
	public function FetchTableFields($tableName, $schema='')
	{
		return $this->FetchColumnDefinitions($tableName, $schema) ;
	}
	protected function ParamsColumnDefinitions($tableName, $schema='')
	{
		return array('table_name' => $tableName) ;
	}
	public function FetchColumnDefinitions($tableName, $schema='')
	{
		$fields = array() ;
		$tableParams = $this->ParamsColumnDefinitions($tableName, $schema) ;
		$res = $this->OpenQuery($this->SqlColumnDefinitions($tableName, $schema), $tableParams) ;
		if($res !== false)
		{
			while(($row = $this->ReadQuery($res)) != false)
			{
				$column = $this->CreateColumnDefinition($row) ;
				$column->ImportConfigFromRow($row) ;
				if($column->Name != '')
				{
					$fields[$column->Name] = $column ;
				}
			}
			$this->CloseQuery($res) ;
		}
		return $fields ;
	}
	protected function CreateColumnDefinition()
	{
		return new \Pv\DB\SqlDef\Column\Column() ;
	}
	protected function ParamsTableDefinitions($schema='')
	{
		return array() ;
	}
	public function SqlTableDefinitions($schema='')
	{
		return '' ;
	}
	public function FetchTableDefinitions($schema='')
	{
		$tables = array() ;
		$params = $this->ParamsTableDefinitions($schema) ;
		$res = $this->OpenQuery($this->SqlTableDefinitions($schema), $params) ;
		if($res !== false)
		{
			while(($row = $this->ReadQuery($res)) != false)
			{
				$tableDefinition = $this->CreateTableDefinition($row) ;
				$tableDefinition->ImportConfigFromRow($row) ;
				if($tableDefinition->Name != '')
				{
					$tables[$tableDefinition->Name] = $tableDefinition ;
				}
			}
			$this->CloseQuery($res) ;
		}
		return $tables ;
	}
	public function CreateTableDefinition()
	{
		return new \Pv\DB\SqlDef\Table\Table() ;
	}
/**
* Save the field structure for a table
* @access protected
* @param string $tableName Table name, must be present in the database
* @return bool
*/
	function StoreFieldsCache($tableName, $schema='')
	{
		$requestTableName = $tableName ;
		$tableName = strtoupper($tableName) ;
		if(isset($this->FieldsCache[$tableName]))
		{
			return 1 ;
		}
		$fields = $this->FetchColumnDefinitions($requestTableName, $schema='') ;
		if(count($fields))
		{
			$this->FieldsCache[$tableName] = $fields ;
			return 1 ;
		}
		return 0 ;
	}
/**
* Extract the parameters names which are used in a Sql statement
* @access protected
* @param string $sql Sql statement to find
* @return array
*/
	function ExtractParamsFromSql($sql, $params=array())
	{
		preg_match_all('/'.preg_quote($this->ParamPrefix).'('.$this->ParamPatternName.'+)/i', $sql, $match, PREG_PATTERN_ORDER) ;
		$paramKeys = (isset($match[1])) ? $match[1] : array() ;
		$i = 0 ;
		// print_r($params) ;
		while($i < count($paramKeys))
		{
			$paramKey = $paramKeys[$i] ;
			if(! isset($params[$paramKey]))
			{
				array_splice($paramKeys, $i, 1) ;
			}
			else
			{
				$i++ ;
			}
		}
		return $paramKeys;
	}
/**
* Extract the parameters names which are used in a Sql statement
* @access protected
* @param string $sql Sql statement to parse.
* @param array $realParamNames real parameter names.
* @param string $paramSymbol Symbol to use instead of the parameter names.
* @return array
*/
	function ReplaceParamsToSql($sql, $realParamNames, $paramSymbol="?")
	{
		$result = $sql ;
		foreach($realParamNames as $i => $paramKey)
		{
			$result = str_replace($this->ParamPrefix.$paramKey, $paramSymbol, $result) ;
		}
		return $result ;
	}
	function ExtractParamValues($realParamNames, $params=array())
	{
		$result = array() ;
		foreach($realParamNames as $i => $paramKey)
		{
			$result[] = $params[$paramKey] ;
		}
		return $result ;
	}
	function RemoveExprKeyEntry($rowData)
	{
		if(isset($rowData[$this->ExprKeyName]))
		{
			unset($rowData[$this->ExprKeyName]) ;
		}
		return $rowData ;
	}
	function AutoFinalConnection()
	{
		if($this->AutoCloseConnection)
		{
			$this->FinalConnection() ;
		}
	}
	function ExtractRow($tableName, $rowData, $includeExprs=true)
	{
		$requestTableName = $tableName ;
		$tableName = strtoupper($tableName) ;
		if(! isset($this->FieldsCache[$tableName]))
		{
			if(! $this->StoreFieldsCache($requestTableName))
			{
				return array() ;
			}
		}
		$row = array() ;
		if($includeExprs)
		{
			$row[$this->ExprKeyName] = (isset($rowData[$this->ExprKeyName])) ? $rowData[$this->ExprKeyName] : array() ;
		}
		// print_r($this->FieldsCache) ;
		foreach($this->FieldsCache[$tableName] as $fieldName => $fieldData)
		{
			$val = null ;
			$valDefined = 0 ;
			if(array_key_exists($fieldName, $rowData))
			{
				$val = $rowData[$fieldName] ;
				$valDefined = 1 ;
			}
			else
			{
				if(array_key_exists(strtoupper($fieldName), $rowData))
				{
					$val = $rowData[strtoupper($fieldName)] ;
					$valDefined = 1 ;
				}
				else
				{
					if(array_key_exists(strtolower($fieldName), $rowData))
					{
						$val = $rowData[strtolower($fieldName)] ;
						$valDefined = 1 ;
					}
					elseif($includeExprs)
					{
						if(array_key_exists($fieldName, $row[$this->ExprKeyName]))
						{
							$val = $row[$this->ExprKeyName][$fieldName] ;
							$valDefined = 1 ;
						}
						elseif(array_key_exists(strtoupper($fieldName), $row[$this->ExprKeyName]))
						{
							$val = $row[$this->ExprKeyName][strtoupper($fieldName)] ;
							$valDefined = 1 ;
						}
						elseif(array_key_exists(strtolower($fieldName), $row[$this->ExprKeyName]))
						{
							$val = $row[$this->ExprKeyName][strtolower($fieldName)] ;
							$valDefined = 1 ;
						}
					}
				}
			}
			if($valDefined)
			{
				$row[$fieldName] = $val ;
			}
		}
		return $row ;
	}
	function ExtractWhereFromRow($tableName, $params=array())
	{
		if(! isset($this->FieldsCache[$tableName]))
		{
			return null ;
		}
		$row = $this->ExtractRow($tableName, $params, true) ;
		$where = "" ;
		foreach($row as $fieldName => $v)
		{
			if($where == "")
			{
				$where .= " and " ;
			}
			$exprParamValue = $this->ParamPrefix.$this->NewValuePrefix.$fieldName ;
			$currentValue = (isset($rowData[$this->ExprKeyName][$fieldName])) ?
				$rowData[$this->ExprKeyName][$fieldName] : $this->ExprParamPattern ;
			$currentValue = str_ireplace($this->ExprParamPattern, $exprParamValue, $currentValue) ;
			$where .= $this->EscapeFieldName($tableName, $fieldName).'='.$currentValue ;
		}
		return $where ;
	}
	function ExtractSqlMatchRows($tableName, $params=array())
	{
		$sql = "select * from ".$this->EscapeTableName($tableName) ;
		$where = $this->ExtractWhereFromRow($tableName, $params) ;
		if(empty($where))
		{
			$where = $this->FalseCond() ;
		}
		return $sql." where ".$where ;
	}
	function MatchRows($tableName, $params=array(), $onlyFirst=false)
	{
		$sql = $this->ExtractSqlMatchRows($tableName, $params) ;
		return $this->FetchSqlRows($sql, $params, $onlyFirst) ;
	}
	function MatchFirstRow($tableName, $params=array())
	{
		$rows = $this->MatchRows($tableName, $params, true) ;
		$res = null ;
		if($rows !== null)
		{
			$res = array() ;
			if(count($res))
			{
				$res = $rows[0] ;
			}
		}
		return $res ;
	}
	function MatchFirstValue($tableName, $params=array(), $fieldName='')
	{
		$firstRow = $this->MatchFirstRow($tableName, $params) ;
		$res = null ;
		if($firstRow !== null)
		{
			$res = "" ;
			if(count($firstRow))
			{
				if(isset($firstRow[$fieldName]))
				{
					$res = $firstRow[$fieldName] ;
				}
				else
				{
					$res = null ;
				}
			}
		}
		return $res ;
	}
	function RunSql($sql, $params=array())
	{
		$ok = false ;
		$this->MustSetCharacterEncoding = 1 ;
		$res = $this->OpenQuery($sql, $this->EncodeParams($params)) ;
		if($res)
		{
			$ok = true ;
			$this->CloseQuery($res) ;
		}
		return $ok ;
	}
	function FetchSqlEntities($sql, $params=array(), $entityClassName="", $onlyFirst=false)
	{
		$entities = null ;
		if(! class_exists($entityClassName))
		{
			die("La classe ".$entityClassName." n'existe pas") ;
		}
		$res = $this->OpenQuery($sql, $params) ;
		if($res !== false)
		{
			$entities = array() ;
			while(($row = $this->ReadQuery($res)) != false)
			{
				$entity = new $entityClassName() ;
				$entity->SetParentDatabase($this) ;
				$entity->ImportConfigFromRow($row) ;
				$entities[] = $entity ;
				if($onlyFirst)
				{
					break ;
				}
			}
			$this->CloseQuery($res) ;
		}
		return $entities ;
	}
	function FetchSqlEntity($sql, $params=array(), $entityClassName='')
	{
		$res = null ;
		$firstEntity = $this->FetchSqlEntities($sql, $params, $entityClassName, true) ;
		if($firstEntity !== null)
		{
			if(count($firstEntity))
			{
				$res = $firstEntity[0] ;
			}
		}
		return $res ;
	}
	function EncodeParams($params=array())
	{
		$results = array() ;
		foreach($params as $name => $value)
		{
			$results[$name] = $this->EncodeParamValue($value) ;
		}
		return $results ;
	}
	function FetchSqlRows($sql, $params=array(), $onlyFirst=false)
	{
		$rows = null ;
		$this->MustSetCharacterEncoding = $this->SetCharacterEncodingOnFetch ;
		$res = $this->OpenQuery($sql, $this->EncodeParams($params)) ;
		if($res !== false)
		{
			$rows = array() ;
			while(($row = $this->ReadQuery($res)) != false)
			{
				$rows[] = $row ;
				if($onlyFirst)
				{
					break ;
				}
			}
			$this->CloseQuery($res) ;
		}
		return $rows ;
	}
	function FetchSqlValue($sql, $params=array(), $fieldName='')
	{
		$res = null ;
		$firstRow = $this->FetchSqlRow($sql, $params) ;
		if($firstRow !== null)
		{
			$res = '' ;
			// print_r($firstRow) ;
			if(count($firstRow))
			{
				if(isset($firstRow[$fieldName]))
				{
					$res = $firstRow[$fieldName] ;
				}
				elseif(is_int($fieldName) || $fieldName == '')
				{
					$fieldName = intval($fieldName) ;
					$fieldList = array_keys($firstRow) ;
					$res = (isset($fieldList[$fieldName])) ? $fieldList[$fieldName] : null ;
				}
				else
				{
					$res = null ;
				}
			}
		}
		return $res ;
	}
	function FetchSqlRow($sql, $params=array())
	{
		$res = null ;
		$firstRow = $this->FetchSqlRows($sql, $params, true) ;
		if($firstRow !== null)
		{
			$res = array() ;
			if(count($firstRow))
			{
				$res = $firstRow[0] ;
			}
		}
		return $res ;
	}
	function LimitSqlRows($sql, $params=array(), $start=0, $limit=1000, $extra='')
	{
		return $this->FetchSqlRows($this->LimitSqlRowsReq($sql, $params, $start, $limit, $extra), $params) ;
	}
	function FetchRangeRows($fieldNames, $where, $other, $start=0, $limit=1000)
	{
	}
	function LimitSqlRowsReq($sql, $params=array(), $start=0, $limit=1000, $extra='')
	{
		return 'select * from ('.$sql.') MAIN_REQ' ;
	}
	function CountSqlRows($sql, $params=array())
	{
		$row = $this->FetchSqlRow($this->CountSqlRowsReq($sql, $params), $params);
		$total = -1 ;
		if($row !== null)
		{
			$total = (isset($row["TOTAL"])) ? $row["TOTAL"] : 0 ;
		}
		return $total ;
	}
	function CountSqlRowsReq($sql, $params=array())
	{
		return 'select count(0) TOTAL from ('.$sql.') MAIN_REQ' ;
	}
	function RunStoredProc($procName, $params=array())
	{
		$ok = 0 ;
		$this->StoredProcUseCursor = 0 ;
		$this->MustSetCharacterEncoding = 1 ;
		$res = $this->OpenStoredProc($procName, $this->EncodeParams($params)) ;
		if($res !== false)
		{
			$ok = 1 ;
			$this->CloseStoredProc($res) ;
		}
		return $ok ;
	}
	function FetchStoredProcRows($procName, $params=array(), $onlyFirst=false)
	{
		$rows = null ;
		$this->StoredProcUseCursor = 1 ;
		$res = $this->OpenStoredProc($procName, $this->EncodeParams($params)) ;
		if($res !== false)
		{
			$rows = array() ;
			while(($row = $this->ReadQuery($res)) != false)
			{
				$rows[] = $row ;
				if($onlyFirst)
				{
					break ;
				}
			}
			$this->CloseStoredProc($res) ;
		}
		return $rows ;
	}
	function FetchStoredProcEntities($procName, $params=array(), $entityClassName='', $onlyFirst=false)
	{
		$entities = null ;
		if(! class_exists($entityClassName))
		{
			die("La classe ".$entityClassName." n'existe pas") ;
		}
		$this->StoredProcUseCursor = 1 ;
		$res = $this->OpenStoredProc($procName, $this->EncodeParams($params)) ;
		if($res !== false)
		{
			$entities = array() ;
			while(($row = $this->ReadQuery($res)) != false)
			{
				$entity = new $entityClassName() ;
				$entity->SetParentDatabase($this) ;
				$entity->ImportConfigFromRow($row) ;
				$entities[] = $entity ;
				if($onlyFirst)
				{
					break ;
				}
			}
			$this->CloseStoredProc($res) ;
		}
		return $entities ;
	}
	function FetchStoredProcEntity($procName, $params=array(), $entityClassName='')
	{
		$res = null ;
		$firstEntity = $this->FetchStoredProcEntities($procName, $params, $entityClassName, true) ;
		if($firstEntity !== null)
		{
			$res = array() ;
			if(count($firstEntity))
			{
				$res = $firstEntity[0] ;
			}
		}
		return $res ;
	}
	function FetchStoredProcRow($procName, $params=array())
	{
		$res = null ;
		$firstRow = $this->FetchStoredProcRows($procName, $params, true) ;
		if($firstRow !== null)
		{
			$res = array() ;
			if(count($firstRow))
			{
				$res = $firstRow[0] ;
			}
		}
		return $res ;
	}
	function FetchStoredProcValue($procName, $params=array(), $columnName='')
	{
		$res = null ;
		$firstRow = $this->FetchStoredProcRow($procName, $params, true) ;
		if($firstRow !== null)
		{
			$res = '' ;
			if(count($firstRow))
			{
				if(isset($firstRow[$columnName]))
				{
					$res = $firstRow[$columnName] ;
				}
				elseif(is_int($columnName) || $columnName == '')
				{
					$columnName = intval($columnName) ;
					$columnList = array_keys($firstRow) ;
					$res = (isset($columnList[$columnName])) ? $columnList[$columnName] : null ;
				}
				else
				{
					$res = null ;
				}
			}
		}
		return $res ;
	}
	function & OpenStoredProc($procName, $params=array())
	{
		$procSql = $this->CallStoredProcSql($procName, $params) ;
		$procParams = $this->CallStoredProcParams($procName, $params) ;
		$res = $this->OpenQuery($procSql, $procParams) ;
		return $res ;
	}
	function CloseStoredProc(& $res)
	{
		$this->CloseQuery($res) ;
	}
	function CallStoredProcSql($procName, $params=array())
	{
	}
	function CallStoredProcParams($procName, $params=array())
	{
		return $this->RemoveExprKeyEntry($params) ;
	}
	function InsertRow($tableName, $rowData)
	{
		$ok = 0 ;
		$rowData = $this->ExtractRow($tableName, $rowData) ;
		if($rowData)
		{
			$newRowData = $this->ApplyNewValuePrefix($rowData) ;
			$insertFieldList = $this->InsertRowFieldList($tableName, $rowData) ;
			$insertValueList = $this->InsertRowValueList($tableName, $rowData) ;
			if($insertFieldList != '' && $insertValueList != '')
			{
				// print_r($newRowData) ;
				$this->MustSetCharacterEncoding = 1 ;
				$sql = 'insert into '.$this->EscapeTableName($tableName).' ('.$insertFieldList.') values ('.$insertValueList.')' ;
				$res = $this->OpenQuery($sql, $this->EncodeParams($this->RemoveExprKeyEntry($newRowData))) ;
				if($res !== false)
				{
					$this->CloseQuery($res) ;
					$ok = 1 ;
				}
			}
			else
			{
				$this->SetConnectionException("La chaine d'insertion n'a pas ete construite pour la table $tableName") ;
			}
		}
		else
		{
			$this->SetConnectionException("Aucune valeur ne correspond a un champ de la table $tableName") ;
		}
		return $ok ;
	}
	function InsertRowFieldList($tableName, &$rowData)
	{
		$res = '' ;
		foreach($rowData as $fieldName => $fieldValue)
		{
			if($fieldName == $this->ExprKeyName)
				continue ;
			if($res != '')
			{
				$res .= ', ' ;
			}
			$res .= $this->EscapeFieldName($tableName, $fieldName) ;
		}
		return $res ;
	}
	function InsertRowValueList($tableName, &$rowData)
	{
		$res = '' ;
		foreach($rowData as $fieldName => $fieldValue)
		{
			if($fieldName == $this->ExprKeyName)
				continue ;
			if($res != '')
			{
				$res .= ', ' ;
			}
			$exprParamValue = $this->ParamPrefix.$this->NewValuePrefix.$fieldName ;
			$currentValue = (isset($rowData[$this->ExprKeyName][$fieldName])) ?
				$rowData[$this->ExprKeyName][$fieldName] : $this->ExprParamPattern ;
			$currentValue = str_ireplace($this->ExprParamPattern, $exprParamValue, $currentValue) ;
			$res .= $currentValue ;
		}
		return $res ;
	}
	function UpdateRow($tableName, $rowData, $where, $whereParams=array())
	{
		$ok = 0 ;
		$rowData = $this->ExtractRow($tableName, $rowData) ;
		// print_r($rowData) ;
		$newRowData = $this->ApplyNewValuePrefix($rowData) ;
		if($rowData)
		{
			$updateList = $this->UpdateRowList($tableName, $rowData) ;
			if($updateList != '')
			{
				$sql = 'update '.$this->EscapeTableName($tableName).' set '.$updateList.' where '.$where ;
				$this->MustSetCharacterEncoding = 1 ;
				$res = $this->OpenQuery($sql, $this->EncodeParams($this->RemoveExprKeyEntry(array_merge($newRowData, $whereParams)))) ;
				if($res !== false)
				{
					$this->CloseQuery($res) ;
					$ok = 1 ;
				}
			}
			else
			{
				$this->SetConnectionException("La chaine de mise a jour n'a pas ete construite pour la table $tableName") ;
			}
		}
		else
		{
			$this->SetConnectionException("Aucune valeur ne correspond a un champ de la table $tableName") ;
		}
		return $ok ;
	}
	function UpdateRowList($tableName, $rowData)
	{
		$res = '' ;
		foreach($rowData as $fieldName => $fieldValue)
		{
			if($fieldName == $this->ExprKeyName)
				continue ;
			if($res != '')
			{
				$res .= ', ' ;
			}
			$exprParamValue = $this->ParamPrefix.$this->NewValuePrefix.$fieldName ;
			$currentValue = (isset($rowData[$this->ExprKeyName][$fieldName])) ?
				$rowData[$this->ExprKeyName][$fieldName] : $this->ExprParamPattern ;
			$currentValue = str_ireplace($this->ExprParamPattern, $exprParamValue, $currentValue) ;
			$res .= $this->EscapeFieldName($tableName, $fieldName).'='.$currentValue ;
		}
		return $res ;
	}
	function DeleteRow($tableName, $where, $whereParams=array())
	{
		$ok = 0 ;
		$this->MustSetCharacterEncoding = 1 ;
		$sql = 'delete from '.$this->EscapeTableName($tableName).' where '.$where ;
		$res = $this->OpenQuery($sql, $this->EncodeParams($whereParams)) ;
		if($res !== false)
		{
			$this->CloseQuery($res) ;
			$ok = 1 ;
		}
		return $ok ;
	}
	function InsertRows($Rows=array())
	{
		
	}
	function InitConnection()
	{
		if($this->Connection)
		{
			return 1 ;
		}
		$this->CharacterEncodingFixed = 0 ;
		return $this->OpenCnx() ;
		// return ($this->Connection !== false) ;
	}
	function FinalConnection()
	{
		$this->CharacterEncodingFixed = 0 ;
		return ($this->CloseCnx()) ;
	}
	function FixCharacterEncoding()
	{
		if($this->MustSetCharacterEncoding && $this->AutoSetCharacterEncoding && ! $this->CharacterEncodingFixed && $this->CharacterEncoding != "")
		{
			$this->ExecFixCharacterEncoding() ;
			$this->CharacterEncodingFixed = 1 ;
		}
	}
	function ExecFixCharacterEncoding()
	{
		
	}
	function SqlNow()
	{
		return 'null' ;
	}
	function SqlConcat($list)
	{
		return join (" + ", $list) ;
	}
	function SqlToDateTime($expr)
	{
		return "null" ;
	}
	function SqlToTimestamp($expr)
	{
		return "null" ;
	}
	function SqlDateDiff($expr1, $expr2)
	{
		return "null" ;
	}
	function SqlReplace($expr, $search, $replace, $start=0)
	{
		return "null" ;
	}
	function SqlSubstr($expr, $start, $length=0)
	{
		return "null" ;
	}
	function SqlLength($expr)
	{
		return "LENGTH($expr)" ;
	}
	function SqlIndexOf($expr, $search, $start=0)
	{
		return "null" ;
	}
	function SqlIsNull($expr)
	{
		return 'null' ;
	}
	function SqlStrToDateTime($dateName)
	{
	}
	function SqlStrToDate($dateName)
	{
	}
	function SqlDatePart($dateName)
	{
	}
	function SqlAddSeconds($expr, $val)
	{
		if($val == 0)
		{
			return $expr ;
		}
		return $expr ;
	}
	function SqlAddMinutes($expr, $val)
	{
	}
	function SqlAddHours($expr, $val)
	{
	}
	function SqlAddDays($expr, $val)
	{
	}
	function SqlAddMonths($expr, $val)
	{
	}
	function SqlAddYears($expr, $val)
	{
	}
	function SqlDateToStr($dateName)
	{
	}
	function SqlDateTimeToStr($dateName)
	{
	}
	function SqlStrToDateFr($dateName, $includeHour=0)
	{
	}
	function SqlToInt($expression)
	{
	}
	function SqlToDouble($expression)
	{
	}
	function SqlToString($expression)
	{
	}
	function SqlEncrypt1($expression, $key)
	{
		return $expression ;
	}
	function SqlDecrypt1($expression, $key)
	{
		return $expression ;
	}
	function SqlEncrypt2($expression, $key)
	{
		return $expression ;
	}
	function SqlDecrypt2($expression, $key)
	{
		return $expression ;
	}
	function SqlEncrypt3($expression, $key)
	{
		return $expression ;
	}
	function SqlDecrypt3($expression, $key)
	{
		return $expression ;
	}
	function SqlEncrypt4($expression, $key)
	{
		return $expression ;
	}
	function SqlDecrypt4($expression, $key)
	{
		return $expression ;
	}
	public function EncodeHtmlEntity($value)
	{
		return htmlentities($value) ;
	}
	public function EncodeHtmlAttr($value)
	{
		return htmlspecialchars($value) ;
	}
	public function EncodeUrl($value)
	{
		return urlencode($value) ;
	}
	public function DecodeRowValue($value)
	{
		return $value ;
	}
	public function EncodeParamValue($value)
	{
		return $value ;
	}
	protected function CaptureQuery($sql, $params=array())
	{
		$this->LastSqlText = $sql ;
		$this->LastSqlParams = $params ;
	}
	public function __construct()
	{
		$this->RegisterShutdownScript() ;
		$this->InitConnectionParams() ;
	}
	public function InitConnectionParams()
	{
	}
	public function & CreateEntity($entityClassName)
	{
		$entity = new $entityClassName() ;
		$entity->SetParentDatabase($this) ;
		return $entity ;
	}
	public function & CreateEntityCollection($entityCollectionClassName)
	{
		$entityCollection = new $entityCollectionClassName($this) ;
		return $entityCollection ;
	}
}