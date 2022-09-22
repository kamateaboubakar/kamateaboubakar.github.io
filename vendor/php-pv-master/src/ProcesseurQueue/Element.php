<?php

namespace Pv\ProcesseurQueue ;

class Element
{
	public $Index = -1 ;
	public $ContenuBrut ;
	public function ImporteContenu($ctnBrut)
	{
		$this->ContenuBrut = $ctnBrut ;
		$this->ImporteContenuInt() ;
	}
	protected function ImporteContenuInt()
	{
	}
}