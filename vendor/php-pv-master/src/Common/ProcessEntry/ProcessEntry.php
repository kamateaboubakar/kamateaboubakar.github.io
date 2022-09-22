<?php

namespace Pv\Common\ProcessEntry ;

class ProcessEntry
{
	var $UID ;
	var $PID ;
	var $PPID ;
	var $C ;
	var $STIME ;
	var $TTY ;
	var $TIME ;
	var $CMD ;
	function ImportFromNotSet()
	{
		$this->UID = false ;
		$this->PID = false ;
		$this->PPID = false ;
		$this->C = false ;
		$this->STIME = false ;
		$this->TTY = false ;
		$this->TIME = false ;
		$this->CMD = false ;
	}
	public static function NotSet()
	{
		$notSetEntry = new ProcessEntry() ;
		$notSetEntry->ImportFromNotSet() ;
		return $notSetEntry ;
	}
}