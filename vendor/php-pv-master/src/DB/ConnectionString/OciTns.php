<?php

namespace Pv\DB\ConnectionString ;

class OciTns
{
	public $Protocol = 'TCP' ;
	public $Port = '1521' ;
	public $Host = '' ;
	public function __construct($host)
	{
		$this->Host = $host ;
	}
}