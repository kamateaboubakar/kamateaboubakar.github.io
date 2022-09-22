<?php

namespace Pv\Common\ProcessManager ;

class Linux extends ProcessManager
{
	function RunAsyncCommandString($cmd)
	{
		return $cmd.' >/dev/null 2>&1 &' ;
	}
	function ExtractProcessEntries($list='', $exceptCmd='')
	{
		$process_entries = array() ;
		$process_list_data = explode("\n", $list) ;
		foreach($process_list_data as $i => $process_data)
		{
			$process_entry = $this->ExtractProcessEntry($process_data) ;
			if($process_entry)
			{
				if($exceptCmd != "" && strpos($process_entry->CMD, $exceptCmd) === true)
				{
					continue ;
				}
				$process_entries[] = $process_entry ;
			}
		}
		return $process_entries ;
	}
	function ExtractProcessEntry($process_data)
	{
		$process_entry = null ;
		$process_data = trim($process_data) ;
		if($process_data != "")
		{
			if(strlen($process_data) > 48)
			{
				$process_entry = new \Pv\Common\ProcessEntry\Linux() ;
				$process_entry->ImportFromPsEfEntry($process_data) ;
			}
		}
		return $process_entry ;
	}
	function LocateByName($name='')
	{
		$Cmd = $this->LocateByNameCommand($name) ;
		$Res = $this->CaptureCommand($Cmd) ;
		$processList = $this->ExtractProcessEntries($Res, $Cmd) ;
		$results = array() ;
		foreach($processList as $i => $processEntry)
		{
			if($name != "")
			{
				if(strpos($processEntry->CMD, $name) === false)
					continue ;
			}
			$results[] = $processEntry ;
		}
		return $results ;
	}
	function LocateByNameCommand($name='')
	{
		return 'ps -ef' ;
	}
	function KillProcessCommand($pids, $force=0)
	{
		if(is_array($pids))
		{
			$pids = join(" ", $pids) ;
		}
		if($pids == "")
		{
			return "" ;
		}
		return 'kill'.(($force) ? ' -9' : '').' '.$pids ;
	}
	function KillProcessList($pid_list=array())
	{
		if(! is_array($pid_list))
		{
			return "" ;
		}
		return join(" ", $pid_list) ;
	}
	function KillProcessEntries($ProcessEntries=array())
	{
		$this->CaptureCommand($this->KillProcessEntriesCommand($ProcessEntries)) ;
	}
	function KillProcessIDs($pids=array())
	{
		$this->CaptureCommand($this->KillProcessCommand($pids)) ;
	}
	function KillProcessEntriesCommand($ProcessEntries)
	{
		return $this->KillProcessCommand($this->ExtractProcessListFromEntries($ProcessEntries)) ;
	}
}