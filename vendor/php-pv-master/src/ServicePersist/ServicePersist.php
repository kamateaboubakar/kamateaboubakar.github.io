<?php

namespace Pv\ServicePersist ;

class ServicePersist extends \Pv\ProgrammeApp\ProgrammeApp
{
	public $Arreter = 0 ;
	public $MaxSessions = 0 ;
	public $TotalSessions = 0 ;
	public $DelaiAttente = 5 ;
	public $DelaiBoucle = 30 ;
	public $DelaiEtatInactif = 120 ;
	public $LimiterDelaiBoucle = 0 ;
	public $VerifFichEtat = 1 ;
	public $Etat ;
	public $EnregEtat = 1 ;
	public $ForcerArret = 1 ;
	public $VerifSurPresenceProc = 0 ;
	protected $NaturePlateforme = "console" ;
	public function NatureElementApplication()
	{
		return "service_persistant" ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Etat = new \Pv\ServicePersist\Etat() ;
		register_shutdown_function(array(& $this, "ConfirmEtatArrete")) ;
	}
	public function ObtientChemFicEtat()
	{
		// print $this->NomElementApplication.' : '.get_class($this)."\n" ;
		return $this->ApplicationParent->ObtientChemRelRegServsPersists()."/".$this->NomElementApplication.".dat" ;
	}
	public function DemarreService()
	{
		$this->ArreteService() ;
		$this->LanceProcessus() ;
	}
	public function ArreteService()
	{
		$this->DetecteEtat() ;
		if($this->Etat->PID != 0 && $this->Etat->PID != getmypid())
		{
			$this->ConfirmEtatArrete(true) ;
			$processMgr = \Pv\Common\ProcessManager\ProcessManager::Current() ;
			$processMgr->KillProcessIDs($processMgr->KillProcessList(array($this->Etat->PID)), $this->ForcerArret) ;
		}
	}
	public function EstDemarre()
	{
		// echo get_class($this).' : '.$this->Etat->Statut." - ".(date("U") - $this->Etat->TimestmpCapt)." et ".$this->DelaiEtatInactif."\n" ;
		if(! $this->VerifFichEtat)
		{
			return true ;
		}
		return $this->Etat->Statut == \Pv\ServicePersist\Etat::ETAT_DEMARRE && date("U") - $this->Etat->TimestmpCapt <= $this->DelaiEtatInactif + $this->DelaiAttente ;
	}
	public function DetecteEtat()
	{
		$this->ChargeEtat() ;
	}
	public function ChargeEtat()
	{
		if($this->EstNul($this->ApplicationParent))
		{
			return ;
		}
		$chemFicEtat = $this->ObtientChemFicEtat() ;
		if(! file_exists($chemFicEtat))
		{
			return ;
		}
		$fh = fopen($chemFicEtat, "r") ;
		$erreur = "" ;
		$ctn = "" ;
		if(is_resource($fh))
		{
			while(! feof($fh))
			{
				$ctn .= fgets($fh) ;
			}
			fclose($fh) ;
		}
		else
		{
			$erreur = "Impossible de lire le fichier ".$chemFicEtat ;
		}
		if($erreur != '')
		{
			echo $erreur ;
			exit ;
		}
		if($ctn != "")
		{
			$this->Etat = unserialize($ctn) ;
			if($this->Etat == false)
			{
				$this->Etat = new \Pv\ServicePersist\Etat() ;
			}
		}
	}
	protected function SauveEtat()
	{
		$chemFicEtat = $this->ObtientChemFicEtat() ;
		$erreur = "" ;
		$fh = fopen($chemFicEtat, "w") ;
		if(is_resource($fh))
		{
			$this->Etat->TimestmpCapt = date("U") ;
			if(! fputs($fh, serialize($this->Etat)))
			{
				$erreur = "Impossible d'enregistrer l'etat du service ".$this->NomElementApplication." dans ".$chemFicEtat ;
			}
			fclose($fh) ;
		}
		else
		{
			$erreur = "Impossible d'ouvrir le fichier ".$chemFicEtat ;
		}
		if($erreur != '')
		{
			echo $erreur ;
			exit ;
		}
	}
	protected function FixeTempsExec($nouvDelai)
	{
		$ancDelai = $this->DelaiMaxExec() ;
		set_time_limit($nouvDelai) ;
		return $ancDelai ;
	}
	protected function ProcPresent()
	{
		$cmd = $this->ObtientCmdExecProg() ;
		$processMgr = \Pv\Common\ProcessManager\ProcessManager::Current() ;
		$entries = $processMgr->LocateByName($cmd) ;
		return (count($entries) == 1) ;
	}
	public function Verifie()
	{
		return 1 ;
	}
	public function EstServiceDemarre()
	{
		$this->DetecteEtat() ;
		if(! $this->EstDemarre() || ($this->VerifSurPresenceProc == 1 && ! $this->ProcPresent()))
		{
			return 0 ;
		}
		return 1 ;
	}
	public function EstDisponible()
	{
		return 1 ;
	}
	protected function ConfirmEtatDebutSession()
	{
		$this->Etat->TimestmpDebutSession = date("U") ;
		$this->Etat->TimestmpFinSession = 0 ;
		$this->SauveEtat() ;
	}
	protected function ConfirmEtatFinSession()
	{
		$this->Etat->TimestmpFinSession = date("U") ;
		$this->SauveEtat() ;
	}
	protected function RepeteBoucle()
	{
		$this->TotalSessions = 0 ;
		while(! $this->Arreter)
		{
			if($this->LimiterDelaiBoucle)
				$oldTimeLimit = $this->FixeTempsExec($this->DelaiBoucle) ;
			$this->PrepareSession() ;
			$this->ExecuteSession() ;
			$this->TermineSession() ;
			if($this->LimiterDelaiBoucle)
				$this->FixeTempsExec($oldTimeLimit) ;
			$this->TotalSessions++ ;
			if($this->MaxSessions > 0 && $this->TotalSessions >= $this->MaxSessions)
			{
				break ;
			}
			if($this->DelaiAttente > 0)
			{
				sleep($this->DelaiAttente) ;
			}
			$this->SauveEtat() ;
		}
	}
	protected function ExecuteSession()
	{
	}
	protected function PrepareSession()
	{
	}
	protected function TermineSession()
	{
	}
	protected function PrepareEnvironnement()
	{
	}
	protected function ConfirmEtatDemarre()
	{
		$this->Etat->PID = getmypid() ;
		$this->Etat->CompteSysteme = exec("whoami") ;
		$this->Etat->TimestmpCapt = date("U") ;
		$this->Etat->Statut = \Pv\ServicePersist\Etat::ETAT_DEMARRE ;
		$this->SauveEtat() ;
	}
	public function ConfirmEtatArrete($forcer=false)
	{
		$this->DetecteEtat() ;
		/*
		echo $this->Etat->PID." : ".getmypid()."\n" ;
		exit ;
		*/
		if($forcer == false && $this->Etat->PID != getmypid())
			return ;
		$this->Etat->PID = 0 ;
		$this->Etat->TimestmpCapt = date("U") ;
		$this->Etat->Statut = \Pv\ServicePersist\Etat::ETAT_STOPPE ;
		$this->SauveEtat() ;
	}
	protected function DemarreExecution()
	{
		parent::DemarreExecution() ;
		$this->ConfirmEtatDemarre() ;
	}
	public function Execute()
	{
		if(! $this->Plateforme->EstDisponible() || ! $this->EstDisponible())
		{
			return ;
		}
		$this->DemarreExecution() ;
		$this->PrepareEnvironnement() ;
		$this->RepeteBoucle() ;
		$this->TermineExecution() ;
	}
}