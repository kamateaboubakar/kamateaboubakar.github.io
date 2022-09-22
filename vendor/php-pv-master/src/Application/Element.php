<?php

namespace Pv\Application ;

class Element extends \Pv\Objet\Objet
{
	public $ApplicationParent = null ;
	public $NomElementApplication = "" ;
	public $NomIntegrationParent = "" ;
	public $CheminFichierRelatif = "" ;
	public $AccepterTousChemins = 0 ;
	public $Titre ;
	public $CheminIcone ;
	public $CheminMiniature ;
	public $Description ;
	public $DelaiExecution = 0 ;
	public $DebutExecution = 0 ;
	public $FinExecution = 0 ;
	public $TempsExecution = 0 ;
	public $DelaiExecutionPrec = 0 ;
	public $Integrations = array() ;
	public function NatureElementApplication()
	{
		return "base" ;
	}
	public function IntegrationParent()
	{
		return $this->ApplicationParent->ObtientIntegration($this->NomIntegrationParent) ;
	}
	public function ObtientCheminIcone()
	{
		if($this->CheminIcone != '')
			return $this->CheminIcone ;
		return $this->ApplicationParent->CheminIconeElem;
	}
	public function ObtientCheminMiniature()
	{
		if($this->CheminMiniature != '')
			return $this->CheminMiniature ;
		return $this->ApplicationParent->CheminMiniatureElem ;
	}
	public function ObtientTitre()
	{
		if($this->Titre != '')
			return $this->Titre ;
		return ucfirst($this->NomElementApplication) ;
	}
	public function ObtientDescription()
	{
		return $this->Description ;
	}
	public function Traduit($nomExpr, $params=array(), $valParDefaut='', $nomTrad='')
	{
		return $this->ApplicationParent->Traduit($nomExpr, $params, $valParDefaut, $nomTrad) ;
	}
	public function ActiveTraducteur($nomTrad)
	{
		return $this->ApplicationParent->ActiveTraducteur($nomTrad) ;
	}
	public function AdopteApplication($nom, & $application)
	{
		$this->ApplicationParent = & $application ;
		$this->NomElementApplication = $nom ;
		// print get_class($this)."<br>" ;
	}
	public function EstActif($cheminFichierAbsolu, $cheminFichierElementActif)
	{
		if($this->AccepterTousChemins)
		{
			return 1 ;
		}
		$cheminFichier = realpath($cheminFichierAbsolu.DIRECTORY_SEPARATOR.$this->CheminFichierRelatif) ;
		// echo $cheminFichier.' : '.$cheminFichierElementActif."<br>\n" ;
		
		// echo get_class($this).' : '.$cheminFichierAbsolu.DIRECTORY_SEPARATOR.$this->CheminFichierRelatif.' hhh<br>' ;
		$ok = ($this->CorrigeChemin($cheminFichier) == $this->CorrigeChemin($cheminFichierElementActif)) ? 1 : 0 ;
		// echo $cheminFichier.' : '.$cheminFichierElementActif." = ".$ok."<br>\n" ;
		return $ok ;
	}
	public function ObtientCheminFichierRelatif()
	{
		return realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$this->CheminFichierRelatif) ;
	}
	protected function DemarreExecution()
	{
		if($this->DelaiExecution > 0)
		{
			$this->DelaiExecutionPrec = @set_time_limit($this->DelaiExecution) ;
			$this->DebutExecution = date("U") ;
		}
	}
	public function ObtientTempsExecution()
	{
		return $this->FinExecution - $this->DebutExecution ;
	}
	protected function TermineExecution()
	{
		@set_time_limit($this->DelaiExecutionPrec) ;
		$this->FinExecution = date("U") ;
	}
	public function Execute()
	{
		$this->DemarreExecution() ;
		$this->TermineExecution() ;
	}
}