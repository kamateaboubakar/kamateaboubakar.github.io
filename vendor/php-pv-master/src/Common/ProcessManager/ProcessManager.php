<?php

namespace Pv\Common\ProcessManager ;

if(! defined("PROCESS_MANAGER_GENERATE_CURRENT"))
{
	define('PROCESS_MANAGER_GENERATE_CURRENT', 1) ;
}

class ProcessManager
{
	var $LastCommand = "" ;
	var $LastResponse = "" ;
	var $LastException = "" ;
	var $ReadTimeout = 20 ;
	var $LastLimitFound = false ;
	public static function & Current()
	{
		$osProcessMgr = null ;
		if(PHP_OS == "WINNT" || PHP_OS == "WIN32")
		{
			$osProcessMgr = new Windows() ;
		}
		else
		{
			$osProcessMgr = new Linux() ;
		}
		return $osProcessMgr ;
	}
	function FixReadTimeout(&$handle)
	{
		stream_set_timeout($handle, $this->ReadTimeout) ;
	}
	function clearLastCommand()
	{
		$this->setLastCommand("") ;
		$this->LastException = "" ;
	}
	function setLastCommand($cmd)
	{
		$this->LastCommand = $cmd ;
	}
	function clearLastResponse()
	{
		$this->setLastResponse("") ;
	}
	function setLastResponse($cmd)
	{
		$this->LastResponse = $cmd ;
	}
	function RunAsyncCommandString($cmd)
	{
		return $cmd ;
	}
	function RunAsync($cmd)
	{
		return ($this->Run($cmd, false) !== false) ;
	}
	public function OpenPipes($cmd)
	{
		
	}
	function & OpenProcessExecution($cmd, $mode='r')
	{
		$handle = false ;
		try
		{
			$handle = @popen($cmd, $mode) ;
		}
		catch(Exception $ex)
		{
			$this->LastException = $ex->getMessage() ;
		}
		return $handle ;
	}
	function ReadProcessExecution(&$handle)
	{
		if(! $handle || feof($handle))
			return false ;
		$this->FixReadTimeout($handle) ;
		return fgets($handle);
	}
	function ReadProcessExecutionUntilEOF(&$handle)
	{
		return $this->ReadProcessExecutionUntil($handle, "") ;
	}
	function ReadProcessExecutionUntil(&$handle, $limit="")
	{
		$res = "" ;
		$this->LastLimitFound = false ;
		while(($line = $this->ReadProcessExecution($handle)) !== false)
		{
			$res .= $line ;
			if($limit == "")
			{
				continue ;
			}
			$posLimit = strpos($res, $limit) ;
			if($posLimit !== false)
			{
				$res = substr($res, $posLimit + strlen($limit), strlen($res) - $posLimit) ;
				$this->LastLimitFound = true ;
				break ;
			}
		}
		return $res ;
	}
	function WriteProcessExecution(&$handle, $ctn)
	{
		if(! $handle)
			return false ;
		return fwrite($handle, $ctn) ;
	}
	function CloseProcessExecution(& $handle)
	{
		if(! $handle)
			return false ;
		pclose($handle) ;
		return true ;
	}
	function Run($cmd, $synchronous=true)
	{
		$handle = false ;
		$ctn = false ;
		$realCmd = ($synchronous) ? $cmd : $this->RunAsyncCommandString($cmd) ;
		$handle = $this->OpenProcessExecution($realCmd) ;
		if($handle)
		{
			$ctn = "" ;
			if($synchronous)
			{
				while(($entry = $this->ReadProcessExecution($handle)) !== false)
				{
					$ctn .= $entry ;
				}
			}
			$this->CloseProcessExecution($handle) ;
		}
		return $ctn ;
	}
	function Query($cmd)
	{
		return $this->Run($cmd, true) ;
	}
	function __construct()
	{
	}
	function \Pv\Common\ProcessManager\ProcessManager()
	{
		$this->__construct() ;
	}
	function ExecuteCommand($Cmd)
	{
		$resp = $this->Query($Cmd) ;
		return $resp ;
	}
	function BeginCapture($Cmd)
	{
		$this->clearLastCommand() ;
		$this->clearLastResponse() ;
	}
	function EndCapture($cmd, $res)
	{
		$this->setLastCommand($cmd) ;
		$this->setLastResponse($res) ;
	}
	function CaptureCommand($cmd)
	{
		$this->BeginCapture($cmd) ;
		$res = $this->ExecuteCommand($cmd) ;
		$this->EndCapture($cmd, $res) ;
		return $res ;
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
		return $process_entry ;
	}
	function FetchAll()
	{
		return $this->LocateByName("") ;
	}
	function LocateByName($name='')
	{
		$Cmd = $this->LocateByNameCommand($name) ;
		$Res = $this->CaptureCommand($Cmd) ;
		$processList = $this->ExtractProcessEntries($Res, $Cmd) ;
		$results = array() ;
		foreach($processList as $i => $processEntry)
		{
			if(strpos($processEntry->CMD, $name) === false)
				continue ;
			$results[] = $processEntry ;
		}
		return $results ;
	}
	function LocateByNameCommand($name='')
	{
		return "" ;
	}
	function KillProcessList($pid_list=array())
	{
	}
	function KillProcessCommand($pids, $force=0)
	{
		if($pids == "")
		{
			return "" ;
		}
		return "" ;
	}
	function KillProcessEntries($ProcessEntries=array())
	{
		$this->CaptureCommand($this->KillProcessCommand($ProcessEntries)) ;
	}
	function ExtractProcessListFromEntries($processEntries)
	{
		$pid_list = array() ;
		// print_r($processEntries) ;
		if(is_array($processEntries))
		{
			foreach($processEntries as $i => $entry)
			{
				$pid_list[] = $entry->PID ;
			}
		}
		return $pid_list ;
	}
	function Start($cmd)
	{
		return $this->RunAsync($cmd) ;
	}
	function Restart($cmd)
	{
		$processList = $this->FetchAll() ;
		$processEntries = $this->LocateInto($processList, $cmd) ;
		$ok = true ;
		if(count($processEntries))
		{
			$ok = $this->KillProcessEntries($processEntries) ;
		}
		return $this->Start($cmd) ;
	}
	function LocateInto(& $processList, $cmd)
	{
		$processEntries = array() ;
		for($i=0; $i<count($processList); $i++)
		{
			if(strpos($processList[$i]->CMD, $cmd) !== false)
			{
				$processEntries[] = $processList[$i] ;
			}
		}
		return $processEntries ;
	}
}

if(PROCESS_MANAGER_GENERATE_CURRENT)
{
	$GLOBALS["ProcessManager"] = new \Pv\Common\ProcessManager\Linux() ;
}