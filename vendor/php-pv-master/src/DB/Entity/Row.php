<?php

namespace Pv\DB\Entity ;

class Row
{
	public $RawData = array() ;
	public $StoreData = 0 ;
	public $ParentDatabase = null ;
	public $AutoMapColumns = 0 ;
	public function SetParentDatabase(& $database)
	{
		$this->ParentDatabase = & $database ;
	}
	public function ToRawData()
	{
		return array() ;
	}
	public function ToEditData()
	{
		return array() ;
	}
	public function ToKeyData()
	{
		return array() ;
	}
	public function EncodeExprName($valeur)
	{
		$result = $valeur ;
		$result = str_replace(array(' ', "\r", "\n", "\t"), '_', $result) ;
		$result = preg_replace_callback('/(_| |\-)([a-z0-9])/i', create_function('$matches', 'return strtoupper($matches[2]) ;'), $result) ;
		return $result ;
	}
	public function EncodeAttrName($valeur)
	{
		$result = $valeur ;
		$result = ucfirst($this->EncodeExpressionVariable($result)) ;
		return $result ;
	}
	protected function MapFromRow($row)
	{
		if(! $this->AutoMapColumns)
		{
			return ;
		}
		foreach($row as $name => $val)
		{
			$attrName = $this->EncodeAttrName($name) ;
			if(property_exists($this, $attrName))
			{
				$this->$attrName = $val ;
			}
		}
	}
	public function ImportConfigFromRow($row)
	{
		if($this->StoreData)
		{
			$this->RawData = $row ;
		}
		$this->MapFromRow($row) ;
		$this->UpdateConfigBeforeImport() ;
		foreach($row as $colName => $colValue)
		{
			$this->ImportConfigFromRowValue($colName, $colValue) ;
		}
		$this->UpdateConfigAfterImport() ;
	}
	public function UpdateConfigBeforeImport()
	{
	}
	public function UpdateConfigAfterImport()
	{
	}
	protected function ImportConfigFromRowValue($name, $value)
	{
		$success = 1 ;
		switch(strtoupper($name))
		{
			default :
			{
				$success = 0 ;
			}
			break ;
		}
		return $success ;
	}
}