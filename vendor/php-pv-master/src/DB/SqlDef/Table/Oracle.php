<?php

namespace Pv\DB\SqlDef\Table ;

class Oracle extends \Pv\DB\SqlDef\Table\Table
{
	public function ImportConfigFromRowValue($name, $value)
	{
		$success = parent::ImportConfigFromRowValue($name, $value) ;
		if($success)
			return 1 ;
		$success = 1 ;
		switch(strtoupper($name))
		{
			case "TABLE_NAME" :
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