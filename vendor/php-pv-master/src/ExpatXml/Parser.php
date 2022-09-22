<?php

namespace Pv\ExpatXml ;

class Parser
{
	public $XmlObject = null;
	public $Output = array();
	public $Charset = 'ISO-8859-1';
	public $EncodeUtf8 = 0 ;
	public $ErrorString = "" ;
	public $CheckFilePath = true ;
	public $CaseInsensitive = 1 ;
	public $SkipWhiteSpaces = 1 ;
	public $AttrsCaseSensitive = 0 ;
	public $NameCaseSensitive = 1 ;
	protected function InitOutput($path="")
	{
		$this->Output = new ExpatXmlDocument();
		$this->Output->Path = $path ;
		$this->ErrorString = "" ;
		$this->init_Parser() ;
	}
	protected function init_Parser()
	{
		$this->XmlObject = xml_Parser_create($this->Charset);
		xml_Parser_set_option($this->XmlObject, XML_OPTION_SKIP_WHITE, $this->SkipWhiteSpaces) ;
		xml_Parser_set_option($this->XmlObject, XML_OPTION_CASE_FOLDING, $this->CaseInsensitive) ;
		xml_Parser_set_option($this->XmlObject, XML_OPTION_TARGET_ENCODING, $this->Charset) ;
		xml_set_object($this->XmlObject, $this);
		xml_set_character_data_handler($this->XmlObject, 'DataHandler');   
		xml_set_element_handler($this->XmlObject, "StartHandler", "EndHandler");
	}
	public function ParseFile($path)
	{
		$this->InitOutput($path) ;
		try
		{
			if($this->CheckFilePath && ! file_exists($path)) {
				$this->ErrorString = "The Path $path doesn't exists on this server" ;
				return false ;
			}
			if (!($fp = fopen($path, "r"))) {
				$this->ErrorString = "Cannot open XML data file: $path" ;
				return false;
			}
			while (($data = fread($fp, 4096)) != false) {	
				if($this->EncodeUtf8)
					$data = utf8_encode($data) ;
				$this->ParseData($data, feof($fp)) ;
			}
		}
		catch(Exception $ex)
		{
			$this->ErrorString = $ex->getMessage() ;
		}
		return $this->Output;
	}
	public function ParseContent($ctn)
	{
		$this->InitOutput() ;
		$this->ParseData($ctn, true) ;
		return $this->Output;
	}
	public function ParseError()
	{
		return $this->ErrorString ;
	}
	protected function ParseData($data, $eof=true)
	{
		// echo $data ;
		if(! $this->XmlObject)
		{
			$this->init_Parser() ;
		}
		if(! xml_Parse($this->XmlObject, $data, $eof))
		{
			$this->ErrorString = sprintf(
				"XML error: %s at line %d",
				xml_error_string(xml_get_error_code($this->XmlObject)),
				xml_get_current_line_number($this->XmlObject)
			) ;
			xml_Parser_free($this->XmlObject);
			$this->XmlObject = false ;
		}
	}
	public function DecodeAttrs($attrString, $encoding='ISO-8859-1')
	{
		$xmlData = '<?xml version="1.0" encoding="'.$encoding.'"><element '.$attrString.' />' ;
		$node = $this->ParseContent($xmlData) ;
		$attrs = null ;
		if(isset($node[0]))
		{
			$attrs = array() ;
			if(isset($node[0]['attrs']))
			{
				$attrs = $node[0]['attrs'] ;
			}
		}
		return $attrs ;
	}
	public function EncodeAttrs($attrs)
	{
		$ctn = '' ;
		$i = 0 ;
		foreach($attrs as $name => $value)
		{
			if($i > 0)
				$ctn .= ' ' ;
			$ctn .= htmlentities($name).'="'.htmlentities($value).'"' ;
			$i++ ;
		}
		return $ctn ;
	}
	public function StartHandler($Parser, $name, $attribs)
	{
		$element = $this->Output->CreateNode() ;
		$element->Name = $name ;
		if($this->AttrsCaseSensitive)
			$attribs = array_map("strtoupper", $attribs) ;
		$element->AddAttributes($attribs) ;
		$this->Output->AddNode($element);
	}
	public function DataHandler($Parser, $data)
	{
		if(! empty($data) || trim($data) === 0 || trim($data) === "") {
			$_output_idx = count($this->Output->ChildNodes) - 1;
			$this->Output->ChildNodes[$_output_idx]->Content .= $data;
		}
	}
	public function EndHandler($Parser, $name)
	{
		if(count($this->Output->ChildNodes) > 1)
		{
			$_data = array_pop($this->Output->ChildNodes);
			$_data->Name = $name ;
			$_output_idx = count($this->Output->ChildNodes) - 1;
			$this->Output->ChildNodes[$_output_idx]->AddNode($_data);
		}
	}
}