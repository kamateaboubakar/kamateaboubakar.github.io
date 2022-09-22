<?php

namespace Pv\TacheCtrl ;

class TacheCtrl extends \Pv\TacheProg\TacheProg
{
	protected $NaturePlateforme = "console" ;
	public $Message = "La tache est terminee" ;
	public $Actions = array() ;
	public $ActionParDefaut ;
	public $Etat ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->ArgsParDefaut["action"] = "" ;
		$this->ArgsParDefaut["serv_persist"] = "" ;
		$this->Etat = $this->CreeEtat() ;
	}
	protected function CreeEtat()
	{
		return new \Pv\ActionCtrl\Etat() ;
	}
	protected function CreeActionParDefaut()
	{
		return new \Pv\ActionCtrl\ActionCtrl() ;
	}
	protected function CreeDeclenchParDefaut()
	{
		return new \Pv\DeclenchTache\Toujours() ;
	}
	public function InscritAction($nom, & $action)
	{
		$this->Actions[$nom] = & $action ;
		$action->AdopteTacheCtrl($nom, $this) ;
	}
	public function & InsereActionParDefaut(& $action)
	{
		$this->ActionParDefaut = & $action ;
		return $action ;
	}
	public function & InsereAction($nom, & $action)
	{
		$this->InscritAction($nom, $action) ;
		return $action ;
	}
	public function Execute()
	{
		$this->ChargeEtat() ;
		$this->ActionParDefaut = $this->CreeActionParDefaut() ;
		$this->ActionParDefaut->AdopteTacheCtrl('par_defaut', $this) ;
		$this->ChargeActions() ;
		parent::Execute() ;
	}
	protected function ChargeActions()
	{
	}
	public function ChemFichEtat()
	{
		if($this->ApplicationParent->ChemRelRegServsPersists == '')
		{
			return null ;
		}
		return dirname(__FILE__)."/../../".$this->ApplicationParent->ChemRelRegServsPersists. DIRECTORY_SEPARATOR . $this->NomElementApplication.".dat" ;
	}
	protected function LitEtat()
	{
		$cheminFich = $this->ChemFichEtat() ;
		if($cheminFich === null)
		{
			return null ;
		}
		if(! file_exists($cheminFich))
		{
			return $this->CreeEtat() ;
		}
		$fh = fopen($cheminFich, "r") ;
		$ctn = '' ;
		if(is_resource($fh))
		{
			$ctn = '' ;
			while(! feof($fh))
			{
				$ctn .= fread($fh, 1024) ;
			}
			fclose($fh) ;
		}
		if($ctn != '')
		{
			$etat = unserialize($ctn) ;
			if(! is_object($etat))
			{
				$etat = $this->CreeEtat() ;
			}
			return $etat ;
		}
		return $this->CreeEtat() ;
	}
	protected function ChargeEtat()
	{
		$this->Etat = $this->LitEtat() ;
	}
	public function SauveEtat()
	{
		$this->Etat->TimestmpCapt = date("U") ;
		$cheminFich = $this->ChemFichEtat() ;
		if($cheminFich === null)
		{
			return false ;
		}
		$fh = fopen($cheminFich, "w") ;
		if(is_resource($fh))
		{
			fputs($fh, serialize($this->Etat)) ;
			fclose($fh) ;
		}
		else
		{
			return false ;
		}
		return true ;
	}
	public function InsereActionAttente($nom, $params=array())
	{
		$codeErreur = '' ;
		if($this->Etat === null)
		{
			return 'fich_actions_attente_introuvable' ;
		}
		$this->Etat->ActionsAttente[] = array($nom, $params) ;
		$ok = $this->SauveEtat() ;
		if($ok == false)
		{
			return 'impossible_ecrire_fich_actions_attente' ;
		}
		return '' ;
	}
	protected function ExecuteSession()
	{
		if($this->Etat != null)
		{
			if(count($this->Etat->ActionsAttente) > 0)
			{
				foreach($this->Etat->ActionsAttente as $i => $infos)
				{
					$this->Actions[$infos[0]]->ExecuteArgs($infos[1]) ;
				}
				$this->Etat->ActionsAttente = array() ;
				$this->SauveEtat() ;
			}
		}
		if($this->Args["action"] != "" && isset($this->Actions[$this->Args["action"]]))
		{
			$this->Actions[$this->Args["action"]]->ExecuteArgs($this->Args) ;
		}
		else
		{
			$this->ActionParDefaut->ExecuteArgs($this->Args) ;
		}
		$this->SauveEtat() ;
		// print_r($this->Etat) ;
		echo $this->Message."\n" ;
	}
}