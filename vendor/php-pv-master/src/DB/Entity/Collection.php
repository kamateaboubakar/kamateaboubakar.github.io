<?php

namespace Pv\DB\Entity ;

class Collection
{
	public $ParentDatabase ;
	public $ItemClassName = "" ;
	public $FetchAllStoredProcName = "" ;
	public $FetchRangeStoredProcName = "" ;
	public $FetchRangeOffsetParamName = "0" ;
	public $FetchRangeMaxParamName = "1" ;
	public $FetchTotalStoredProcName = "" ;
	public $FetchAllSqlText = "" ;
	public $FetchTotalSqlText = "" ;
	public $FetchRangeSqlText = "" ;
	public function __construct(& $parent)
	{
		$this->InitConfig($parent) ;
	}
	protected function InitConfig(& $parent)
	{
		$this->ParentDatabase = & $parent ;
	}
	public function TotalItems()
	{
	}
	public function AllItems()
	{
	}
	public function RangeItems($offset, $max)
	{
	}
	public function LoopItems($max)
	{
	}
}