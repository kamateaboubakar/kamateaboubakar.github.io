<?php

namespace Rpa2p\TacheProg ;

class Planificateur extends TacheProg
{
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::TACHE_PLANIF ;
	}
	protected function ChemFichLock()
	{
		return dirname(realpath($_SERVER["argv"][0]))."/~".$this->NomElementApplication.".lock" ;
	}
	public function CalculsEnCours()
	{
		return file_exists($this->ChemFichLock()) ;
	}
	protected function ExecuteSession()
	{
		file_put_contents($this->ChemFichLock(), getmypid()) ;
		$statusCode = $this->ApplicationParent->InstallePlanifExecs() ;
		unlink($this->ChemFichLock()) ;
		if(! empty($statusCode) && $statusCode > 0)
		{
			exit($statusCode) ;
		}
	}
}
