<?php

namespace Pv\DB\SqlDef\Table ;

class Table extends \Pv\DB\SqlDef\SqlDef
{
	public $Schema = "" ;
	public function ImportConfigFromRowValue($name, $value)
	{
		$success = parent::ImportConfigFromRowValue($name, $value) ;
		if($success)
			return 1 ;
		$success = 1 ;
		switch(strtoupper($name))
		{
			case "NAME" :
			{
				$this->Name = $value ;
			}
			break ;
			default :
			{
				$success = 0 ;
			}
			break ;
		}
		return $success ;
	}
}