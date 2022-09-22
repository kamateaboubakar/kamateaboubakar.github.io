<?php

namespace Pv\Common\GD ;

class Layer extends Image
{
	public static function & CreateLayer(& $Parent)
	{
		$layer = new Layer(uniqid()) ;
		$layer->_Parent = & $Parent ;
		return $layer ;
	}
	public function getParentWidth()
	{
		return $this->_Parent->getWidth() ;
	}
	public function getParentHeight()
	{
		return $this->_Parent->getHeight() ;
	}
}