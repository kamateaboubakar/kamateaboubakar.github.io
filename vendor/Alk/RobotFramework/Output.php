<?php

namespace Alk\RobotFramework ;

class Output
{
	protected $fileName ;
	public $errorCode = -1 ;
	public $xmlContent = "" ;
	public $errorMessage = 'fileName not set' ;
	public function __construct($fileName="")
	{
		$this->fileName = $fileName ;
		$this->parse() ;
	}
	public function loadXml($content)
	{
		$this->xmlContent = $content ;
		$this->parse() ;
	}
	public function loadFile($fileName)
	{
		$this->fileName = $fileName ;
		$this->parse() ;
	}
	public function errorFound()
	{
		return ($this->errorCode != 0) ;
	}
	public function name()
	{
		if($this->data == null)
		{
			return "" ;
		}
		return $this->data->suite->name ;
	}
	public function id()
	{
		if($this->data == null)
		{
			return "" ;
		}
		return $this->data->suite->name ;
	}
	public function status()
	{
		if($this->errorFound())
		{
			return "ERROR" ;
		}
		if($this->data->statistics->total->stat->fail == 1)
		{
			return "FAIL" ;
		}
		return "PASS" ;
	}
	public function pass()
	{
		if($this->errorFound())
		{
			return false ;
		}
		return ($this->data->statistics->total->stat->fail == 0) ;
	}
	public function fail()
	{
		if($this->errorFound())
		{
			return false ;
		}
		return ($this->data->statistics->total->stat->fail > 0) ;
	}
	public function passCount()
	{
		return $this->data->statistics->total->stat->pass ;
	}
	public function failCount()
	{
		return $this->data->statistics->total->stat->fail ;
	}
	public function tests()
	{
		$arr = array() ;
		if(! isset($this->data) || ! isset($this->data->suite))
		{
			return $arr ;
		}
		foreach($this->data->suite->tests as $i => $test)
		{
			$arr[] = $test ;
		}
		return $arr ;
	}
	public function startdate($format="d/m/Y H:i:s")
	{
		return date($format, strtotime($this->data->suite->status->starttime)) ;
	}
	public function elapsed()
	{
		return $this->data->suite->status->elapsed ;
	}
	protected function clearError()
	{
		$this->errorCode = 0 ;
		$this->errorMessage = "" ;
		$this->data = new \StdClass() ;
		$this->data->generator = "" ;
		$this->data->generated = "" ;
		$this->data->rpa = "" ;
		$this->data->schemaversion = "" ;
		$this->data->suite = new \StdClass() ;
		$this->data->suite->tests = array() ;
		$this->data->suite->doc = "" ;
		$this->data->suite->source = "" ;
		$this->data->suite->id = "" ;
		$this->data->suite->status = new \StdClass() ;
		$this->data->suite->status->status = "NONE" ;
		$this->data->suite->status->starttime = "" ;
		$this->data->suite->status->endtime = "" ;
		$this->data->statistics = new \StdClass() ;
		$this->data->statistics->total = new \StdClass() ;
		$this->data->statistics->total->stat = new \StdClass() ;
		$this->data->statistics->total->stat->fail = 0 ;
		$this->data->statistics->total->stat->pass = 0 ;
		$this->data->statistics->total->stat->skip = 0 ;
		$this->data->statistics->tags = array() ;
		$this->data->statistics->suites = array() ;
		$this->data->errors = array() ;
	}
	protected function defineError($code, $msg)
	{
		$this->errorCode = $code ;
		$this->errorMessage = $msg ;
	}
	protected function elapsedTime($row)
	{
		$t1 = $row->starttime ;
		$t2 = $row->endtime ;
		return (strtotime($t2) - strtotime($t1)) ;
	}
	protected function parse()
	{
		$this->clearError() ;
		$xmlDoc = new \DomDocument() ;
		if($this->fileName != "")
		{
			$ok = $xmlDoc->load($this->fileName) ;
			if(! $ok)
			{
				$this->defineError(1, "File ".$this->fileName." not correct") ;
				return ;
			}
		}
		elseif($this->xmlContent != "")
		{
			$ok = $xmlDoc->loadXML($this->xmlContent) ;
			if(! $ok)
			{
				$this->defineError(1, "Content not correct") ;
				return ;
			}
		}
		else
		{
			$this->defineError(1, "No filename/Content set") ;
			return ;
		}
		$docNode = & $xmlDoc->documentElement ;
		$this->data->generator = $docNode->getAttribute("generator") ;
		$this->data->generated = $docNode->getAttribute("generated") ;
		$this->data->rpa = $docNode->getAttribute("rpa") ;
		$this->data->schemaversion = $docNode->getAttribute("schemaversion") ;
		
		foreach($docNode->childNodes as $i => $mainNode)
		{
			switch($mainNode->nodeName)
			{
				case "suite" :
				{
					$this->data->suite->id = $mainNode->getAttribute("id") ;
					$this->data->suite->name = $mainNode->getAttribute("name") ;
					foreach($mainNode->childNodes as $j => $nodeTemp)
					{
						switch ($nodeTemp->nodeName)
						{
							case "test" :
							{
								$obj = new \StdClass() ;
								$obj->id = $nodeTemp->getAttribute("id") ;
								$obj->name = $nodeTemp->getAttribute("name") ;
								$obj->kws = array() ;
								$obj->status = "" ;
								$obj->starttime = "" ;
								$obj->endtime = "" ;
								$nodes = $nodeTemp->childNodes ;
								foreach($nodes as $m => $nodeTemp3)
								{
									switch($nodeTemp3->nodeName)
									{
										case "kw" :
										{
											$statusNodes = $nodeTemp3->getElementsByTagName("status") ;
											$kw = new \StdClass() ;
											$kw->name = $nodeTemp3->getAttribute("name") ;
											if(count($statusNodes) >= 1)
											{
												$kw->status = $statusNodes[0]->getAttribute("status") ;
												$kw->starttime = $statusNodes[0]->getAttribute("starttime") ;
												$kw->endtime = $statusNodes[0]->getAttribute("endtime") ;
												$kw->elapsed = $this->elapsedTime($kw) ;
												$obj->kws[] = $kw ;
											}
										}
										break ;
										case "status" :
										{
											$obj->status = $nodeTemp3->getAttribute("status") ;
											$obj->starttime = $nodeTemp3->getAttribute("starttime") ;
											$obj->endtime = $nodeTemp3->getAttribute("endtime") ;
											$obj->elapsed = $this->elapsedTime($obj) ;
											if($obj->status == "FAIL")
											{
												$this->defineError(2, $nodeTemp3->textContent) ;
											}
										}
										break ;
									}
								}
								$this->data->suite->tests[] = $obj ;
							}
							break ;
							case "doc" :
							{
								$this->data->suite->doc = $nodeTemp->textContent ;
							}
							break ;
							case "status" :
							{
								$this->data->suite->status->status = $nodeTemp->getAttribute("status") ;
								$this->data->suite->status->starttime = $nodeTemp->getAttribute("starttime") ;
								$this->data->suite->status->endtime = $nodeTemp->getAttribute("endtime") ;
								$this->data->suite->status->elapsed = $this->elapsedTime($this->data->suite->status) ;
							}
							break ;
						}
					}
				}
				break ;
				case "statistics" :
				{
					foreach($mainNode->childNodes as $i => $nodeTemp)
					{
						switch ($nodeTemp->nodeName)
						{
							case "total" :
							{
								foreach($nodeTemp->getElementsByTagName("stat") as $k => $nodeTemp2)
								{
									$this->data->statistics->total->stat->fail = $nodeTemp2->getAttribute("fail") ;
									$this->data->statistics->total->stat->pass = $nodeTemp2->getAttribute("pass") ;
									$this->data->statistics->total->stat->skip = $nodeTemp2->getAttribute("skip") ;
								}
							}
							break ;
							case "tag" :
							{
							}
							break ;
							case "suite" :
							{
								foreach($nodeTemp->getElementsByTagName("stat") as $k => $nodeTemp2)
								{
									$this->data->statistics->total->stat->fail = $nodeTemp2->getAttribute("fail") ;
									$this->data->statistics->total->stat->pass = $nodeTemp2->getAttribute("pass") ;
									$this->data->statistics->total->stat->skip = $nodeTemp2->getAttribute("skip") ;
								}
							}
							break ;
						}
					}
				}
				break ;
				case "errors" :
				{
					foreach($mainNode->childNodes as $i => $nodeTemp)
					{
						switch ($nodeTemp->nodeName)
						{
							case "msg" :
							{
								$this->defineError(1, $nodeTemp->textContent) ;
							}
							break ;
						}
					}
				}
				break ;
			}
		}
	}
}
