<?php

namespace Pv\DB\SqlDef\Column ;

class Oracle extends \Pv\DB\SqlDef\Column\Column
{
	public function ImportConfigFromRow($row)
	{
		parent::ImportConfigFromRow($row) ;
		if(isset($row["COLUMN_NAME"]))
		{
			$this->Name = $row["COLUMN_NAME"] ;
			$this->IsNull = (($row['NULLABLE'] == 'Y') ? 1 : 0) ? 1 : 0 ;
			$this->DefaultValue = $row["DATA_DEFAULT"] ;
			$this->MaxLength = $row["DATA_LENGTH"] ;
			$this->Type = $row["DATA_TYPE"] ;
			$this->IsKey = $row["IS_KEY"] ;
		}
	}
}