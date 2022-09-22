<?php

namespace Pv\DB\SqlDef\Column ;

class Mysql extends \Pv\DB\SqlDef\Column\Column
{
	public function ImportConfigFromRow($row)
	{
		parent::ImportConfigFromRow($row) ;
		if(isset($row["Type"]))
		{
			$type_attrs = explode("(", $row["Type"]) ;
			if(isset($type_attrs[1]))
			{
				$type_attrs[1] = str_replace(")", '', $type_attrs[1]) ;
				$type_attrs[1] = -1 ;
			}
			else
			{
				$type_attrs[1] = -1 ;
			}
			$this->Type = $type_attrs[0] ;
			$this->MaxLength = $type_attrs[1] ;
			$this->Name = $row["Field"] ;
			$this->IsNull = ($row['Null'] == 'YES') ? 1 : 0 ;
			$this->IsKey = ($row['Key'] == 'PRI') ? 1 : 0 ;
			$this->DefaultValue = $row["Default"] ;
			$this->Extra = $row["Extra"] ;
		}
	}
}