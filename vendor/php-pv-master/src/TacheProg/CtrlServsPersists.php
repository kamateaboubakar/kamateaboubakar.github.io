<?php

namespace Pv\TacheProg ;

class CtrlServsPersists extends \Pv\TacheCtrl\TacheCtrl
{
	public $DelaiTransition = 0 ;
	public function ActiveServPersist($nom)
	{
		if($this->Etat == null)
		{
			return ;
		}
		$index = array_search($nom, $this->Etat->ServsPersistsDesact) ;
		if($index === false)
		{
			return ;
		}
		array_splice($this->Etat->ServsPersistsDesact, $index, 1) ;
		$this->SauveEtat() ;
	}
	public function DesactiveServPersist($nom)
	{
		if($this->Etat == null)
		{
			return ;
		}
		$index = array_search($nom, $this->Etat->ServsPersistsDesact) ;
		if($index !== false)
		{
			return ;
		}
		$this->Etat->ServsPersistsDesact[] = $nom ;
		$this->SauveEtat() ;
	}
	protected function CreeEtat()
	{
		return new \Pv\ServicePersist\EtatCtrl() ;
	}
	protected function CreeActionParDefaut()
	{
		return new \Pv\ActionCtrl\DemarrSvcsPersInact() ;
	}
	protected function ChargeActions()
	{
		$this->InsereAction("demarre", new \Pv\ActionCtrl\DemarreSvcPers()) ;
		$this->InsereAction("arrete", new \Pv\ActionCtrl\ArreteSvcPers()) ;
		$this->InsereAction("demarre_tous", new \Pv\ActionCtrl\DemarrTousSvcsPers()) ;
		$this->InsereAction("arrete_tous", new \Pv\ActionCtrl\ArretTousSvcsPers()) ;
	}
	public function RemplitTableauDonnees(& $tabl)
	{
	}
}