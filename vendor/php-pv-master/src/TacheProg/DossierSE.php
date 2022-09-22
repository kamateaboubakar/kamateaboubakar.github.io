<?php

namespace Pv\TacheProg ;

class DossierSE extends \Pv\TacheProg\TacheProg
{
	public $CheminAbsoluDossier ;
	protected $Flux ;
	public $AutoSupprFichier = 1 ;
	public $AnnuleSupprFichier = 0 ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		register_shutdown_function(array(& $this, 'FermeFlux'), array()) ;
	}
	protected function ExecuteSession()
	{
		if($this->CheminAbsoluDossier == '' || ! is_dir($this->CheminAbsoluDossier))
		{
			return ;
		}
		if($this->OuvreFlux() !== false)
		{
			while(($nomFichier = readdir($this->Flux)) !== false)
			{
				if($nomFichier == '.' || $nomFichier == '..')
				{
					continue ;
				}
				if($this->AccepteFichier($nomFichier))
				{
					$this->AnnuleSupprFichier = 0 ;
					$this->TraiteFichier($nomFichier) ;
					if($this->AutoSupprFichier && ! $this->AnnuleSupprFichier)
					{
						$cheminFichier = $this->CheminAbsoluFichier.'/'.$nomFichier ;
						if(! is_dir($cheminFichier))
						{
							unlink($cheminFichier) ;
						}
					}
				}
			}
			$this->FermeFlux() ;
		}
	}
	protected function TraiteFichier($nomFichier)
	{
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