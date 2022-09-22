<?php

namespace Pv\ExpatXml ;

class Node extends ExpatXmlElement
{
	public $RegisterChildNodes = 1 ;
	public $RegisterAttributes = 1 ;
	public $RegisterContents = 1 ;
	public $ElementID = "" ;
	public $Attributes = array() ;
	public $ChildNodes = array() ;
	public $Content = "" ;
	public function GetAttribute($name)
	{
		return (isset($this->Attributes[$name])) ? $this->Attributes[$name] : false ;
	}
	public function ChildNodeToHash()
	{
		$hash = array() ;
		foreach($this->ChildNodes as $i => $childNode)
		{
			$hash[$childNode->Name] = $childNode->Content ;
		}
		return $hash ;
	}
	public function AddContent($content)
	{
		$this->AddElement('content', 'content', $content) ;
		if($this->RegisterContents)
			$this->Content .= $content ;
	}
	public function AddNode($node)
	{
		$this->AddElement('node', $node->Name, $node) ;
		if($this->RegisterChildNodes)
			$this->ChildNodes[] = $node ;
	}
	public function AddChildNode($node)
	{
		$this->AddNode($node) ;
	}
	public function AddAttributes($attributes=array())
	{
		foreach($attributes as $name => $value)
		{
			$this->AddElement('attribute', $name, $value) ;
		}
		if($this->RegisterAttributes)
			$this->Attributes = array_merge($this->Attributes, $attributes) ;
	}
	public function GetElementById($id)
	{
		$node = false ;
		for($i=0; $i<count($this->ChildNodes); $i++)
		{
			if(isset($this->ChildNodes[$i]->Attributes["ID"]) && $this->ChildNodes[$i]->Attributes["ID"] == $id)
			{
				$node = $this->ChildNodes[$i] ;
			}
			if($node == false)
			{
				$node = $this->ChildNodes[$i]->GetElementById($id) ;
			}
			if($node != false)
			{
				break ;
			}
		}
		return $node ;
	}
	public function ChildNodeCount()
	{
		return count($this->ChildNodes) ;
	}
	public function GetChildNodesByName($name)
	{
		$result = array() ;
		for($i=0; $i<count($this->ChildNodes); $i++)
		{
			if(isset($this->ChildNodes[$i]->Attributes["NAME"]) && $this->ChildNodes[$i]->Attributes["NAME"] == $name)
			{
				$result[] = $this->ChildNodes[$i] ;
			}
		}
		return $result ;
	}
	public function GetChildNodesByTagName($name)
	{
		$name = strtoupper($name) ;
		$result = array() ;
		for($i=0; $i<count($this->ChildNodes); $i++)
		{
			if($this->ChildNodes[$i]->Name == $name)
			{
				$result[] = $this->ChildNodes[$i] ;
			}
		}
		return $result ;
	}
	public function GetFirstNodeByTagName($name)
	{
		$name = strtoupper($name) ;
		$result = null ;
		for($i=0; $i<count($this->ChildNodes); $i++)
		{
			if($this->ChildNodes[$i]->Name == $name)
			{
				$result = $this->ChildNodes[$i] ;
				break ;
			}
		}
		return $result ;
	}
}