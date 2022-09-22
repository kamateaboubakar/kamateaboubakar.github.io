<?php

namespace Pv\ZoneWeb\Tache ;

class Tache extends \Pv\Objet\Objet
{
	public $NomElementGest ;
	public $GestParent ;
	protected $Etat ;
	public $DelaiExecution = 1 ; // En heures
	protected $TerminerExecution = 0 ;
	public function InitConfig()
	{
		parent::InitConfig() ;
		$this->Etat = new \Pv\ServicePersist\Etat() ;
	}
	public function ObtientEtat()
	{
		return $this->Etat ;
	}
	public function ObtientCheminFichier()
	{
		return $this->GestParent->ObtientCheminDossierTaches()."/".$this->NomElementGest.".dat" ;
	}
	protected function ObtientCtnBrutEtat()
	{
		$cheminFichier = $this->ObtientCheminFichier() ;
		if(! file_exists($cheminFichier))
			return "" ;
		$fh = fopen($cheminFichier, "r") ;
		$ctn = '' ;
		if($fh !== false)
		{
			while(! feof($fh))
			{
				$ctn .= fgets($fh, 256) ;
			}
			fclose($fh) ;
		}
		else
		{
			return false ;
		}
		return $ctn ;
	}
	protected function SauveEtat()
	{
		$fh = fopen($this->ObtientCheminFichier(), "w") ;
		if($fh != false)
		{
			fputs($fh, serialize($this->Etat)) ;
			fclose($fh) ;
		}
		else
		{
			return 0 ;
		}
		return 1 ;
	}
	public function InitEtat()
	{
		$this->Etat->PID = getmypid() ;
		$this->Etat->TimestmpDebutSession = date("U") ;
		$this->Etat->Statut = \Pv\ServicePersist\Etat::ETAT_DEMARRE ;
		$this->Etat->TimestmpCapt = date("U") ;
		return $this->SauveEtat() ;
	}
	public function ActualiseEtat()
	{
		$this->Etat->TimestmpCapt = date("U") ;
		return $this->SauveEtat() ;
	}
	protected function ChargeEtat()
	{
		$ctn = $this->ObtientCtnBrutEtat() ;
		if($ctn === false)
		{
			return 0 ;
		}
		if($ctn != '')
		{
			$this->Etat = unserialize($ctn) ;
		}
		return 1 ;
	}
	public function AdopteGest($nom, & $gest)
	{
		$this->NomElementGest = $nom ;
		$this->GestParent = & $gest ;
	}
	public function & ZoneParent()
	{
		return $this->GestParent->ZoneParent ;
	}
	public function EstPret()
	{
		if(! $this->Etat->EstDefini())
		{
			$this->ChargeEtat() ;
		}
		if($this->Etat->Statut == \Pv\ServicePersist\Etat::ETAT_DEMARRE)
		{
			return 1 ;
		}
		$timestampAtteint = $this->Etat->TimestmpFinSession + ($this->DelaiExecution * 3600) ;
		$ok = 0 ;
		if(($this->Etat->Statut == \Pv\ServicePersist\Etat::ETAT_STOPPE || $this->Etat->Statut == \Pv\ServicePersist\Etat::ETAT_NON_DEFINI) && date("U") >= $timestampAtteint)
		{
			$ok = 1 ;
		}
		return $ok ;
	}
	public function Demarre()
	{
		if(! $this->EstPret())
		{
			return ;
		}
		$ok = $this->InitEtat() ;
		if(! $ok)
		{
			return ;
		}
		$this->TerminerExecution = 1 ;
		$this->ExecuteInstructions() ;
		if($this->TerminerExecution)
		{
			$this->TermineExecution() ;
		}
		exit ;
	}
	public function TermineExecution()
	{
		$this->Etat->PID = 0 ;
		$this->Etat->Statut = \Pv\ServicePersist\Etat::ETAT_STOPPE ;
		$this->Etat->TimestmpCapt = date("U") ;
		$this->Etat->TimestmpFinSession = date("U") ;
		$this->SauveEtat() ;
	}
	public function Arrete()
	{
		$processMgr = \Pv\Common\ProcessManager\ProcessManager::Current() ;
		if($this->Etat->PID == 0)
		{
			return ;
		}
		$processMgr->KillProcessList(array($this->Etat->PID)) ;
	}
	public function Appelle($params=array())
	{
		return $this->GestParent->LanceTache($this->NomElementGest, $params) ;
	}
	protected function ExecuteInstructions()
	{
	}
}