<?php

namespace Pv\Common\ProcessPipe ;

class ProcessPipe
{
	const InputNo = 0 ;
	const OutputNo = 1 ;
	const ErrNo = 2 ;
	protected $ProcessRes = false ;
	protected $Pipes = array() ;
	public $ErrorFilePath ;
	public $StoreError = true ;
	public $EnvData = array() ;
	public $CurrentDirPath = null ;
	public $LastLimitFound = false ;
	public $ReadTimeout = 2 ;
	public $ReadMaxLength = 1024 ;
	function FixReadTimeout(&$handle)
	{
		stream_set_timeout($handle, $this->ReadTimeout) ;
	}
	public function Open($cmd, $params=array())
	{
		$descriptorSpec = array(
			self::InputNo => array("pipe", "r"),
			self::OutputNo => array("pipe", "w"),
		);
		if($this->StoreError)
		{
			if($this->ErrorFilePath != "")
			{
				$descriptorSpec[self::ErrNo] = array("file", $this->ErrorFilePath, "a") ;
			}
			else
			{
				$descriptorSpec[self::ErrNo] = array("pipe", "w") ;
			}
		}
		$this->ProcessRes = @proc_open($cmd, $descriptorSpec, $this->Pipes, $this->CurrentDirPath, $this->EnvData) ;
		return ($this->ProcessRes !== false) ;
	}
	public function Write($ctn)
	{
		if(! $this->ProcessRes)
			return false ;
		$this->FixReadTimeout($this->Pipes[self::InputNo]) ;
		$ok = fwrite($this->Pipes[self::InputNo], $ctn) ;
		fflush($this->Pipes[self::InputNo]) ;
		return $ok ;
	}
	public function ReadHandle(&$handle)
	{
		// stream_set_blocking($handle, false) ;
		$this->CloseInput() ;
		if(! $this->ProcessRes || feof($handle))
			return false ;
		$this->FixReadTimeout($handle) ;
		// $line = $this->fGetsPending($handle, $this->ReadMaxLength) ;
		$line = fread($handle, $this->ReadMaxLength) ;
		// echo $line ;
		return $line ;
	}
	public function ReadHandleUntil(&$readHandle, $limit="")
	{
		$res = "" ;
		$this->LastLimitFound = false ;
		while(($line = $this->ReadHandle($readHandle)) !== false)
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
			if($line == '')
				break ;
		}
		return $res ;
	}
	public function Read()
	{
		return $this->ReadHandle($this->Pipes[self::OutputNo]) ;
	}
	public function ReadUntil($limit)
	{
		return $this->ReadHandleUntil($this->Pipes[self::OutputNo], $limit) ;
	}
	public function ReadError()
	{
		return $this->ReadHandleUntil($this->Pipes[self::ErrNo], "") ;
	}
	public function GetError()
	{
		return $this->ReadHandleUntil($this->Pipes[self::ErrNo], "") ;
	}
	public function CloseErr()
	{
		if(! isset($this->Pipes[self::ErrNo]))
			return true ;
		if(! $this->Pipes[self::ErrNo])
			return false ;
		$ok = fclose($this->Pipes[self::ErrNo]) ;
		$this->Pipes[self::ErrNo] = false ;
		return $ok ;
	}
	public function CloseOutput()
	{
		if(! $this->Pipes[self::OutputNo])
			return false ;
		$ok = fclose($this->Pipes[self::OutputNo]) ;
		$this->Pipes[self::OutputNo] = false ;
		return $ok ;
	}
	public function CloseInput()
	{
		if(! $this->Pipes[self::InputNo])
			return false ;
		$ok = fclose($this->Pipes[self::InputNo]) ;
		$this->Pipes[self::InputNo] = false ;
		return $ok ;
	}
	public function ReadUntilEOF()
	{
		return $this->ReadUntil("") ;
	}
	public function Close()
	{
		if(! $this->ProcessRes)
			return false ;
		$pipeCount = count($this->Pipes) ;
		$this->CloseInput() ;
		$this->CloseOutput() ;
		$this->CloseErr() ;
		proc_close($this->ProcessRes) ;
	}
}