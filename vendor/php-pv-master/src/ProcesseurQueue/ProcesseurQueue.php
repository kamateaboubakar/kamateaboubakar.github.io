<?php

namespace Pv\ProcesseurQueue ;

class ProcesseurQueue extends \Pv\ServicePersist\ServicePersist
{
	public $MaxElements = 20 ;
	public $ElementsBruts = array() ;
	public $ElementActif ;
	protected function CreeElement()
	{
		return new \Pv\ProcesseurQueue\Element() ;
	}
	protected function ExecuteSession()
	{
		do
		{
			$this->ElementActif = null ;
			$this->ElementsBruts = array() ;
			$this->ChargeElements() ;
			$this->SauveEtat() ;
			foreach($this->ElementsBruts as $i => $elemBrut)
			{
				$this->ElementActif = $this->CreeElement() ;
				$this->ElementActif->Index = $i ;
				$this->ElementActif->ImporteContenu($elemBrut) ;
				$this->TraiteElementActif() ;
				$this->SauveEtat() ;
			}
			$this->VideElements() ;
		}
		while(count($this->ElementsBruts) > 0) ;
	}
	protected function TraiteElementActif()
	{
	}
	protected function ChargeElements()
	{
	}
	protected function VideElements()
	{
		$this->ElementActif = null ;
		$this->ElementsBruts = array() ;
	}
}