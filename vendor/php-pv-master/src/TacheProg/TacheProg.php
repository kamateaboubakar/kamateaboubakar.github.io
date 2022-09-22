<?php

namespace Pv\TacheProg ;

class TacheProg extends \Pv\ProgrammeApp\ProgrammeApp
{
	public $Declenchs = array() ;
	public $DeclenchParDefaut ;
	public $ToujoursExecuter = 1 ;
	public $TypeDeclenchParDefaut = "" ;
	public function NatureElementApplication()
	{
		return "tache_programmee" ;
	}
	protected function CreeDeclenchParDefaut()
	{
		$declench = null ;
		if($this->TypeDeclenchParDefaut != "")
		{
			switch(strtolower($this->TypeDeclenchParDefaut))
			{
				case "jour" :
				case "day" :
				case "daily" :
				case "journalier" :
				{
					$declench = new \Pv\DeclenchTache\Jour() ;
				}
				break ;
				case "semaine" :
				case "hebdo" :
				case "weekly" :
				case "week" :
				{
					$declench = new \Pv\DeclenchTache\Semaine() ;
				}
				break ;
				case "mois" :
				case "month" :
				case "monthly" :
				{
					$declench = new \Pv\DeclenchTache\Mois() ;
				}
				break ;
			}
		}
		if($declench != null)
		{
			return $declench ;
		}
		return new \Pv\DeclenchTache\Indef() ;
	}
	public function DelaiAtteint()
	{
		$ok = 0 ;
		$declenchs = $this->Declenchs ;
		$declenchDefaut = ($this->ToujoursExecuter == 1) ? new \Pv\DeclenchTache\Toujours() : $this->CreeDeclenchParDefaut() ;
		array_splice($declenchs, 0, 0, array($declenchDefaut)) ;
		foreach($declenchs as $i => $declench)
		{
			if($declench->DelaiTacheAtteint($this))
			{
				$ok = 1 ;
				break ;
			}
		}
		return $ok ;
	}
	public function Execute()
	{
		if(! $this->Plateforme->EstDisponible() || ! $this->DelaiAtteint())
		{
			return ;
		}
		$this->DemarreExecution() ;
		$this->ExecuteSession() ;
		$this->TermineExecution() ;
	}
	protected function ExecuteSession()
	{
	}
}