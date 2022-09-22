<?php

namespace Pv\ProgrammeApp ;

class ProgrammeApp extends \Pv\Application\Element
{
	public $Plateforme = null ;
	protected $NaturePlateforme = "" ;
	public $ArgsParDefaut = array() ;
	public $Args = array() ;
	public $SortieDansFichier = 0 ;
	protected function CreePlateforme()
	{
		$platf = new \Pv\PlateformeProc\Console() ;
		switch(strtoupper($this->NaturePlateforme))
		{
			case "WEB" :
			case "NAVIGATEUR" :
			case "BROWSER" :
			case "HTTP" :
				{ $platf = new \Pv\PlateformeProc\Http() ; }
			break ;
			case "CONSOLE" :
			case "SHELL" :
			case "DOS" :
				{ $platf = new \Pv\PlateformeProc\Console() ; }
			break ;
			case "INDEF" :
			case "UNDEF" :
			case "INDEFINI" :
				{ $platf = new \Pv\PlateformeProc\Indef() ; }
			break ;
		}
		return $platf ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Plateforme = $this->CreePlateforme() ;
	}
	protected function DetecteArgs()
	{
		$this->Args = $this->ArgsParDefaut ;
		$args = $this->Plateforme->RecupArgs() ;
		foreach($this->Args as $nom => $val)
		{
			if(isset($args[$nom]))
				$this->Args[$nom] = $args[$nom] ;
		}
	}
	public function EstActif($cheminFichierAbsolu, $cheminFichierElementActif)
	{
		$this->DetecteArgs() ;
		return parent::EstActif($cheminFichierAbsolu, $cheminFichierElementActif) ;
	}
	public function LanceProcessus()
	{
		return $this->Plateforme->LanceProcessusProg($this) ;
	}
	public function TermineProcessus()
	{
		return $this->Plateforme->TermineProcessusProg($this) ;
	}
}