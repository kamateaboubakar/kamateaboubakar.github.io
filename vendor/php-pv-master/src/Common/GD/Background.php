<?php

namespace Pv\Common\GD ;

class Background extends Control
{
	public $Color ;
	public $_ColorHandle ;
	public $_Parent ;
	function & Create(& $Parent)
	{
		$bg = new Background(uniqid()) ;
		$bg->_Parent = & $Parent ;
		return $bg ;
	}
	function Init()
	{
		$this->Color = new \Pv\Common\GD\Color(255, 255, 255) ;
	}
	function Draw()
	{
		$this->_ColorHandle = imagecolorallocate($this->_Parent->_Handle, $this->Color->R, $this->Color->G, $this->Color->B) ;
		imagefilledrectangle($this->_Parent->_Handle, 0, 0, $this->_Parent->_Width, $this->_Parent->_Height, $this->_ColorHandle) ;
	}
}