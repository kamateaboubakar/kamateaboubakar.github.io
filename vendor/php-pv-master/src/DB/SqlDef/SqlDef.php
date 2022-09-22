<?php

namespace Pv\DB\SqlDef ;

class SqlDef
{
	public $Name = "" ;
	public $Type = "" ;
	public $MaxLength = 0 ;
	public $DefaultValue = "" ;
	public function ImportConfigFromRow($row)
	{
		foreach($row as $name => $value)
		{
			$this->ImportConfigFromRowValue($name, $value) ;
		}
	}
	public function ImportConfigFromRowValue($name, $value)
	{
		return 0 ;
	}
}