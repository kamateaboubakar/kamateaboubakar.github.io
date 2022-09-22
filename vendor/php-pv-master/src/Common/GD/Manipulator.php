<?php

namespace Pv\Common\GD ;

$GLOBALS['GDFormats'] = array(
	'gif' => 'gif',
	'jpg' => 'jpeg',
	'jpeg' => 'jpeg',
	'gd' => 'gd',
	'gd2' => 'gd2',
	'png' => 'png',
	'xbm' => 'xbm',
	'xpm' => 'xpm',
	'wbmp' => 'wbmp'
) ; 

class Manipulator extends Control
{
	function Init()
	{
		parent::Init() ;
	}
	function getSub($Format)
	{
		global $GDFormats ;
		$Sub = '' ;
		$Format = strtolower($Format) ;
		if(isset($GDFormats[$Format]))
			$Sub = $GDFormats[$Format] ;
		return $Sub ;
	}
	function HasImageFormat($FilePath)
	{
		return ($this->getFileFormat($FilePath) != '') ;
	}
	function getFileExt($FilePath)
	{
		$FileExt = "" ;
		if(preg_match('/\.([a-zA-Z0-9]+)$/', $FilePath, $Ext))
		{
			$FileExt = $Ext[1] ;
		}
		return $FileExt ;
	}
	function getFileName($FilePath)
	{
		return basename($FilePath) ;
	}
	function getFileFormat($FilePath)
	{
		$FileExt = $this->getFileExt($FilePath) ;
		$Format = $this->getSub($FileExt) ;
		return $Format ;
	}
	function getDimensions($Handle)
	{
		return array(imagesx($Handle), imagesy($Handle)) ;
	}
	function getDimensionsFromFile($FilePath)
	{
		/*
		$Handle = $this->LoadHandleFromFile($FilePath) ;
		if(! $Handle)
		{
			return array(0, 0) ;
		}
		$Dimensions = $this->getDimensions($Handle) ;
		imagedestroy($Handle) ;
		*/
		if(! file_exists($FilePath))
		{
			return array(0, 0) ;
		}
		if($this->getFileFormat($FilePath) == '')
		{
			return array(0, 0) ;
		}
		// echo $FilePath.'<br />' ;
		$Dimensions = @getimagesize($FilePath) ;
		return $Dimensions ;
	}
	function getWidthFromFile($FilePath)
	{
		$Dims = $this->getDimensionsFromFile($FilePath) ;
		return $Dims[0] ;
	}
	function getHeightFromFile($FilePath)
	{
		$Dims = $this->getDimensionsFromFile($FilePath) ;
		return $Dims[1] ;
	}
	function getSizeFromFile($FilePath)
	{
		$Dims = $this->getDimensionsFromFile($FilePath) ;
		return $Dims[1]*$Dims[0] ;
	}
	function CallLoadFileSub($FilePath)
	{
		$Sub = "imagecreatefrom" ;
		$Format = $this->getFileFormat($FilePath) ;
		$Result = NULL ;
		if(! file_exists($FilePath))
		{
			return $Result ;
		}
		if(function_exists($Sub.$Format))
		{
			if(PHP_VERSION >= "7")
			{
				$Result = @call_user_func($Sub.$Format, $FilePath) ;
			}
			else
			{
				$Result = call_user_func($Sub.$Format, $FilePath) ;
			}
		}
		return $Result ;
	}
	function CallSaveFileSub($Handle, $FilePath)
	{
		$Sub = "image" ;
		$Format = $this->getFileFormat($FilePath) ;
		$Result = NULL ;
		if(function_exists($Sub.$Format))
		{
			$Result = call_user_func($Sub.$Format, $Handle, $FilePath) ;
		}
		return $Result ;
	}
	function CallOutputSub($Handle, $Format)
	{
		$Sub = "image" ;
		$Result = NULL ;
		if(function_exists($Sub.$Format))
		{
			$Result = call_user_func($Sub.$Format, $Handle) ;
		}
		return $Result ;
	}
	function LoadHandleFromFile($FilePath)
	{
		return $this->CallLoadFileSub($FilePath) ;
	}
	function UnloadHandle($Handle)
	{
		if($Handle)
		{
			if(is_resource($Handle))
			{
				imagedestroy($Handle) ;
			}
		}
	}
	function SaveHandleToFile($Handle, $FilePath)
	{
		return $this->CallSaveFileSub($Handle, $FilePath) ;
	}
	function Rescale($Handle, $Scale, $BgColor=array(255, 255, 255))
	{
		list($Width, $Height) = $this->getDimensions($Handle) ;
		$NewWidth = $Width * $Scale ; $NewHeight = $Height * $Scale ;
		return $this->Resize($Handle, $NewWidth, $NewHeight, $BgColor) ;
	}
	function Resize($Handle, $Width, $Height, $BgColor=array(255, 255, 255))
	{
		list($OldWidth, $OldHeight) = $this->getDimensions($Handle) ;
		if($Width == 0)
		{
			$Width = $OldWidth ;
		}
		if($Height == 0)
		{
			$Height = $OldHeight ;
		}
		$NewHandle = imagecreatetruecolor($Width, $Height) ;
		$BgColor_h = imagecolorallocate($NewHandle, $BgColor[0], $BgColor[1], $BgColor[2]) ;
		imagefilledrectangle($NewHandle, 0, 0, $Width, $Height, $BgColor_h) ;
		imagecopyresampled($NewHandle, $Handle, 0, 0, 0, 0, $Width, $Height, $OldWidth, $OldHeight) ;
		return $NewHandle ;
	}
	function Crop($Handle, $Left=0, $Top=0, $Width=0, $Height=0, $BgColor=array(255, 255, 255))
	{
		list($OldWidth, $OldHeight) = $this->getDimensions($Handle) ;
		if($Width == 0)
		{
			$Width = $OldWidth ;
		}
		if($Height == 0)
		{
			$Height = $OldHeight ;
		}
		list($NewWidth, $NewHeight) = $this->getAdjustedDimensions($Handle, $Width, $Height) ;
		$NewHandle = imagecreatetruecolor($Width, $Height) ;
		$BgColor_h = imagecolorallocate($NewHandle, $BgColor[0], $BgColor[1], $BgColor[2]) ;
		imagefilledrectangle($NewHandle, 0, 0, $Width, $Height, $BgColor_h) ;
		imagecopyresampled($NewHandle, $Handle, $Left, $Top, 0, 0, $NewWidth, $NewHeight, $OldWidth, $OldHeight) ;
		return $NewHandle ;
	}
	function CopyFile($FilePathSource, $FilePathDest)
	{
		$Handle = $this->LoadHandleFromFile($FilePathSource) ;
		if(!$Handle)
			return ;
		$this->SaveHandleToFile($Handle, $FilePathDest) ;
		imagedestroy($Handle) ;
	}
	function CopyRescaledFile($FilePathSource, $FilePathDest, $Scale)
	{
		$Handle = $this->LoadHandleFromFile($FilePathSource) ;
		if(!$Handle)
			return ;
		$NewHandle = $this->Rescale($Handle, $Scale) ;
		$this->SaveHandleToFile($NewHandle, $FilePathDest) ;
		imagedestroy($Handle) ;
		imagedestroy($NewHandle) ;
	}
	function CopyResizedFile($FilePathSource, $FilePathDest, $Width, $Height)
	{
		$Handle = $this->LoadHandleFromFile($FilePathSource) ;
		if(!$Handle)
			return ;
		$NewHandle = $this->Resize($Handle, $Width, $Height) ;
		$this->SaveHandleToFile($NewHandle, $FilePathDest) ;
		imagedestroy($Handle) ;
		imagedestroy($NewHandle) ;
	}
	function CopyAdjustedFile($FilePathSource, $FilePathDest, $Width, $Height)
	{
		$Handle = $this->LoadHandleFromFile($FilePathSource) ;
		if(! $Handle)
			return ;
		list($NewWidth, $NewHeight) = $this->getAdjustedDimensions($Handle, $Width, $Height) ;
		$NewHandle = $this->Resize($Handle, $NewWidth, $NewHeight) ;
		$this->SaveHandleToFile($NewHandle, $FilePathDest) ;
		imagedestroy($Handle) ;
		imagedestroy($NewHandle) ;
	}
	function CopyWrappedFile($FilePathSource, $FilePathDest, $Width, $Height, $BgColor=array(255, 255, 255))
	{
		$Handle = $this->LoadHandleFromFile($FilePathSource) ;
		if(! $Handle)
			return ;
		list($NewWidth, $NewHeight) = $this->getAdjustedDimensions($Handle, $Width, $Height) ;
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
		$NewHandle = $this->Crop($Handle, $Left, $Top, $Width, $Height, $BgColor) ;
		$this->SaveHandleToFile($NewHandle, $FilePathDest) ;
		imagedestroy($Handle) ;
		imagedestroy($NewHandle) ;
	}
	function getAdjustedDimensions($Handle, $Width, $Height)
	{
		list($OldWidth, $OldHeight) = $this->getDimensions($Handle) ;
		if($OldWidth == 0 or $OldHeight == 0)
		{
			return array(0, 0) ;
		}
		if($Width == 0)
		{
			$Width = $OldWidth ;
		}
		if($Height == 0)
		{
			$Height = $OldHeight ;
		}
		$ScaleWidth = $OldWidth / $Width ;
		$ScaleHeight = $OldHeight / $Height ;
		$NewWidth = $Width ;
		$NewHeight = $Height ;
		if($OldHeight / $ScaleWidth <= $Height)
		{
			$NewHeight = intval($OldHeight / $ScaleWidth) ;
			$NewWidth = intval($OldWidth / $ScaleWidth) ;
		}
		elseif($OldWidth / $ScaleHeight <= $Width)
		{
			$NewWidth = intval($OldWidth / $ScaleHeight) ;
			$NewHeight = intval($OldHeight / $ScaleHeight) ;
		}
		return array($NewWidth, $NewHeight) ;
	}
	function getAdjustedDimensionsFromFile($filePath, $width, $height)
	{
		$Handle = $this->LoadHandleFromFile($filePath) ;
		if(!$Handle)
			return array(0, 0) ;
		$dims = $this->getAdjustedDimensions($Handle, $width, $height) ;
		imagedestroy($Handle) ;
		return $dims ;
	}
	function getAdjustedDimsFromFile($filePath, $width, $height)
	{
		return $this->getAdjustedDimensionsFromFile($filePath, $width, $height) ;
	}
}

$GLOBALS['\Pv\Common\GD\Manipulator'] = new \Pv\Common\GD\Manipulator("\Pv\Common\GD\Manipulator") ;