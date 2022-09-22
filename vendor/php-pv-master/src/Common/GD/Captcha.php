<?php

namespace Pv\Common\GD ;

class Captcha extends Layer
{
	public $CharCount = 6 ;
	public $Codes = "ressERDFtgt673TGgj5huyTHNJkMASs51uqz8921Oo5b6132kMPnVcxWw" ;
	protected $_Text ;
	public $MinFontSize = 24 ;
	public $MaxFontSize = 30 ;
	static $InstanceCount = 0 ;
	static $CaseInsensitive = 1 ;
	public $SessionName = "CaptchaText" ;
	public function Init()
	{
		\Pv\Common\GD\Captcha::$InstanceCount++ ;
		parent::Init() ;
	}
	public static function & Create($Width=0, $Height=0)
	{
		$name = "\Pv\Common\GD\Captcha_".\Pv\Common\GD\Captcha::$InstanceCount ;
		$img = new \Pv\Common\GD\Captcha($name) ;
		$img->_Width = $Width ;
		$img->_Height = $Height ;
		return $img ;
	}
	public function DrawSubmittedText()
	{
		$textColor = imagecolorallocate($this->_Handle, 0, 0, 255);
		$charLeft = 10 ;
		$charWidth = 15 ;
		$charTop = 8 ;
		$this->_Text = "" ;
		for($i=0; $i<$this->CharCount; $i++)
		{
			$charFont = rand($this->MinFontSize, $this->MaxFontSize) ;
			$charIndex = rand(0, strlen($this->Codes) - 1) ;
			$this->_Text .= $this->Codes[$charIndex] ;
			imagestring($this->_Handle, $charFont, $charLeft, $charTop, $this->Codes[$charIndex], $textColor) ;
			$charLeft += $charWidth ;
		}
		$this->Store() ;
	}
	public function Store()
	{
		$_SESSION[$this->Name.$this->SessionName] = $this->_Text ;
	}
	public function Draw()
	{
		$this->DrawBackground() ;
		$this->DrawSubmittedText() ;
		$this->Store() ;
	}
	public function ConfirmSubmittedText($text)
	{
		if(! isset($_SESSION[$this->Name.$this->SessionName]))
			return 0 ;
		$ok = 0 ;
		if($this->CaseInsensitive)
		{
			$ok = (strtolower($_SESSION[$this->Name.$this->SessionName]) == strtolower($text)) ;
		}
		else
		{
			$ok = ($_SESSION[$this->Name.$this->SessionName] == $text) ;
		}
		$this->ClearSubmittedText() ;
		return $ok ;
	}
	public function ClearSubmittedText()
	{
		unset($_SESSION[$this->Name.$this->SessionName]) ;
	}
}