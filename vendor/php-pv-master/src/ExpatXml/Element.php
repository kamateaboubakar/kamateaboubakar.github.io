<?php

namespace Pv\ExpatXml ;

class Element
{
	public $Name = "" ;
	public $ElementName = "" ;
	public $ElementType = "" ;
	public $ElementValue = "" ;
	public $Elements = array() ;
	public $RegisterElements = 1 ;
	public function AddElement($type, $name, $value)
	{
		if(! $this->RegisterElements)
			return ;
		$element = new ExpatXmlElement() ;
		$element->ElementType = $type ;
		$element->ElementName = $name ;
		$element->ElementValue = $value ;
		$this->Elements[] = $element ;
	}
	public function GetElementsByName($name)
	{
		if(! $this->RegisterElements)
			return ;
		$elements = array() ;
		for($i=0; $i<count($this->Elements); $i++)
		{
			if($this->Elements[$i]->ElementName == $name)
			{
				$elements[] = $this->Elements[$i] ;
			}
		}
		return $elements ;
	}
	public function GetElementsByType($type)
	{
		if(! $this->RegisterElements)
			return ;
		$elements = array() ;
		for($i=0; $i<count($this->Elements); $i++)
		{
			if($this->Elements[$i]->ElementType == $type)
			{
				$elements[] = $this->Elements[$i] ;
			}
		}
		return $elements ;
	}
	public function GetElementsByValue($value)
	{
		if(! $this->RegisterElements)
			return ;
		$elements = array() ;
		for($i=0; $i<count($this->Elements); $i++)
		{
			if($this->Elements[$i]->ElementValue == $value)
			{
				$elements[] = $this->Elements[$i] ;
			}
		}
		return $elements ;
	}
}