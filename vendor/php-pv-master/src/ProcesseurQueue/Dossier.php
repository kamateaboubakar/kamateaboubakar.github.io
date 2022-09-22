<?php

namespace Pv\ProcesseurQueue ;

class Dossier extends \Pv\ProcesseurQueue\ProcesseurQueue
{
	public $CheminAbsoluDossier ;
	protected $Flux ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		register_shutdown_function(array(& $this, 'FermeFlux'), array()) ;
	}
	protected function ChargeElements()
	{
		if($this->CheminAbsoluDossier == '' || ! is_dir($this->CheminAbsoluDossier))
		{
			return ;
		}
		if($this->OuvreFlux() !== false)
		{
			while(count($this->ElementsBruts) < $this->MaxElements && ($nomFichier = readdir($this->Flux)) !== false)
			{
				if($nomFichier == '.' || $nomFichier == '..')
				{
					continue ;
				}
				if($this->AccepteFichier($nomFichier))
				{
					$this->ElementsBruts[] = $this->CheminAbsoluDossier.'/'.$nomFichier ;
				}
			}
			$this->FermeFlux() ;
		}
	}
	protected function VideElements()
	{
		foreach($this->ElementsBruts as $i => $cheminFichier)
		{
			unlink($cheminFichier) ;
		}
	}
	protected function OuvreFlux()
	{
		$this->Flux = opendir($this->CheminAbsoluDossier) ;
		return ($this->Flux != false) ;
	}
	public function FermeFlux()
	{
		if(is_resource($this->Flux))
		{
			closedir($this->Flux) ;
			$this->Flux = false ;
		}
	}
	protected function AccepteFichier($nomFichier)
	{
		return 1 ;
	}
}