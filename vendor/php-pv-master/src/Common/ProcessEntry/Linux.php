<?php

namespace Pv\Common\ProcessEntry ;

class Linux extends ProcessEntry
{
	function ImportFromPsEfEntry($process_data)
	{
		if(strlen($process_data) > 48)
		{
			$this->UID = trim(substr($process_data, 0, 8)) ;
			$this->PID = trim(substr($process_data, 8, 6)) ;
			$this->PPID = trim(substr($process_data, 14, 8)) ;
			$this->C = trim(substr($process_data, 22, 2)) ;
			$this->STIME = trim(substr($process_data, 24, 5)) ;
			$this->TTY = trim(substr($process_data, 30, 9)) ;
			$this->TIME = trim(substr($process_data, 39, 9)) ;
			$this->CMD = trim(substr($process_data, 48)) ;
		}
		else
		{
			$this->ImportFromNotSet() ;
		}
	}
}