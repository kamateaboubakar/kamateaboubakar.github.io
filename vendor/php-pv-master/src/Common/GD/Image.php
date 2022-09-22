<?php

namespace Pv\Common\GD ;

class Image extends Control
{
	public $_Handle ;
	public $_Width ;
	public $_Height ;
	public $_Left ;
	public $_Top ;
	public $Background ;
	public $_FilePath ;
	public static function & Create($Width=0, $Height=0)
	{
		$img = new \Pv\Common\GD\Image(uniqid()) ;
		$img->_Width = $Width ;
		$img->_Height = $Height ;
		$img->_FilePath = '' ;
		return $img ;
	}
	public function Init()
	{
		parent::Init() ;
		$this->_Top = 0 ;
		$this->_Left = 0 ;
	}
	public function Open()
	{
		$this->Background = \Pv\Common\GD\Background::Create($this) ;
		$this->OpenHandle() ;
	}
	public function OpenHandle()
	{
		$this->_Handle = imagecreatetruecolor($this->_Width, $this->_Height) ;
	}
	public function OpenFromFile($FilePath)
	{
		$this->Background = new \Pv\Common\GD\Background($this) ;
		$this->OpenHandleFromFile($FilePath) ;
	}
	public function OpenHandleFromFile($FilePath)
	{
		global $\Pv\Common\GD\Manipulator ;
		$this->_FilePath = $FilePath ;
		$this->_Handle = $\Pv\Common\GD\Manipulator->LoadHandleFromFile($this->_FilePath) ;
		$this->_Width = 0 ;
		$this->_Height = 0 ;
		$this->SetPropertiesFromHandle() ;
	}
	public function SetPropertiesFromHandle()
	{
		if($this->_Handle)
		{
			$Dims = $GLOBALS['\Pv\Common\GD\Manipulator']->getDimensionsFromFile($this->_FilePath) ;
			$this->_Width = $Dims[0] ;
			$this->_Height = $Dims[1] ;
		}
	}
	public function SaveToFile($FilePath = '')
	{
		if($FilePath == '')
		{
			$FilePath = $this->_FilePath ;
		}
		$GLOBALS['\Pv\Common\GD\Manipulator']->SaveHandleToFile($this->_Handle, $FilePath) ;
	}
	public function CloseHandle()
	{
		if(! $this->_Handle)
		{
			return ;
		}
		imagedestroy($this->_Handle) ;
	}
	public function Close()
	{
		$this->CloseHandle() ;
	}
	public function Draw()
	{
		$this->DrawBackground() ;
	}
	public function DrawBackground()
	{
		if(! $this->Background)
		{
			return ;
		}
		$this->Background->Draw() ;
	}
	public function Show($format="jpeg")
	{
		if($format == "jpg" or ! in_array($format, array('gif', 'png', 'jpeg', 'jpg', 'ico')))
		{
			$format = "jpeg" ;
		}
		header("Content-type:image/$format") ;
		$GLOBALS['\Pv\Common\GD\Manipulator']->CallOutputSub($this->_Handle, $format) ;
	}
	public function getWidth()
	{
		return $this->_Width ;
	}
	public function getHeight()
	{
		return $this->_Height ;
	}
	public function Rescale($Scale)
	{
		if(! $this->_Handle)
		{
			return ;
		}
		$this->_Handle = $GLOBALS['\Pv\Common\GD\Manipulator']->Rescale($this->_Handle, $Scale) ;
		$this->SetPropertiesFromHandle() ;
	}
	public function Resize($Width, $Height)
	{
		if(! $this->_Handle)
		{
			return ;
		}
		$this->_Handle = $GLOBALS['\Pv\Common\GD\Manipulator']->Resize($this->_Handle, $Width, $Height) ;
		$this->SetPropertiesFromHandle() ;
	}
	public function Adjust($Width, $Height)
	{
		list($NewWidth, $NewHeight) = $GLOBALS["\Pv\Common\GD\Manipulator"]->getAdjustedDimensions($this->_Handle, $Width, $Height) ;
		if(! $NewWidth or ! $NewHeight)
		{
			return ;
		}
		$this->_Handle = $GLOBALS["\Pv\Common\GD\Manipulator"]->Resize($this->_Handle, $NewWidth, $NewHeight) ;
		$this->SetPropertiesFromHandle() ;
	}
	public function Wrap($Width, $Height, $BgColor=array(255, 255, 255))
	{
		if(! $this->_Handle)
			return ;
		list($NewWidth, $NewHeight) = $GLOBALS["\Pv\Common\GD\Manipulator"]->getAdjustedDimensions($this->_Handle, $Width, $Height) ;
		$Left = 0 ;
		$Top = 0 ;
		if($NewWidth < $Width)
		{
			$Left = \Pv\Misc::bcdiv($Width - $NewWidth, 2) ;
		}
		if($NewHeight < $Height)
		{
			$Top = \Pv\Misc::bcdiv($Height - $NewHeight, 2) ;
		}
		$this->_Handle = $GLOBALS["\Pv\Common\GD\Manipulator"]->Crop($this->_Handle, $Left, $Top, $Width, $Height, $BgColor) ;
		$this->SetPropertiesFromHandle() ;
	}
	public function TextOut($string, $x=0, $y=0, $BgColor=array(0, 0, 0), $font_index=8)
	{
		$color = imagecolorallocate($this->_Handle, $BgColor[0], $BgColor[1], $BgColor[2]) ;
		imagestring($this->_Handle, $font_index, $x, $y, $string, $color);
	}
	public function PasteImageFromPath($image_path, $x=0, $y=0)
	{
		global $\Pv\Common\GD\Manipulator ;
		$Handle = $\Pv\Common\GD\Manipulator->LoadHandleFromFile($image_path) ;
		if(! $Handle)
		{
			return ;
		}
		list($w, $h) = $\Pv\Common\GD\Manipulator->getDimensions($Handle) ;
		imagecopymerge($this->_Handle, $Handle, $x, $y, 0, 0, $w, $h, 100) ;
		imagedestroy($Handle) ;
	}
	public function ApplyPatternFromPath($image_path)
	{
		global $\Pv\Common\GD\Manipulator ;
		$Handle = $\Pv\Common\GD\Manipulator->LoadHandleFromFile($image_path) ;
		if(! $Handle)
		{
			return ;
		}
		list($w, $h) = $\Pv\Common\GD\Manipulator->getDimensions($Handle) ;
		for($i=0; $i < $this->getWidth(); $i += $w)
		{
			for($j=0; $j < $this->getHeight(); $j += $h)
			{
				$x = $i ;
				$y = $j ;
				imagecopymerge($this->_Handle, $Handle, $x, $y, 0, 0, $w, $h, 100) ;
			}
		}
		imagedestroy($Handle) ;
	}
	public function TextOutTTF($string, $x=0, $y=0, $size=18, $angle=0, $BgColor=array(0, 0, 0), $font_name="arial")
	{
		$color = imagecolorallocate($this->_Handle, $BgColor[0], $BgColor[1], $BgColor[2]) ;
		$font_path = $font_name.'.ttf' ;
		$font_file_name = $font_path ;
		if(file_exists($font_path))
		{
			putenv('GDFONTPATH=' . realpath('.'));
			$font_file_name = $font_name ;
		}
		elseif(is_dir($win_font_path = 'C:/WINDOWS/Fonts'))
		{
			$font_file_name = $win_font_path.'/'.$font_path ;
		}
		imagettftext($this->_Handle, $x, $y, $size, $angle, $color, $font_file_name, $string);
		// exit ;
	}
	public function setLeft($Left)
	{
		$this->_Left = $Left ;
	}
	public function setTop($Top)
	{
		$this->_Top = $Top ;
	}
	public function getLeft()
	{
		return $this->_Left ;
	}
	public function getTop()
	{
		return $this->_Top ;
	}
}