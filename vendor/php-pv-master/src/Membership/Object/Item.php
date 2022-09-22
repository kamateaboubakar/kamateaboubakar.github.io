<?php

namespace Pv\Membership\Object ;

class Item extends ObjectBase
{
	public $ParentObject = null ;
	public $ItemName = "" ;
	public function __construct(& $parent)
	{
		$this->ParentObject = & $parent ;
		$this->InitConfig($parent) ;
	}
	protected function InitConfig(& $parent)
	{
	}
}