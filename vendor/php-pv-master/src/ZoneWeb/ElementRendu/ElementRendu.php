<?php

namespace Pv\ZoneWeb\ElementRendu ;

class ElementRendu extends \Pv\Objet\Objet
{
	public $UrlsReferantsSurs = array() ;
	public $HotesReferantsSurs = array() ;
	public $RefererHoteLocal = 0 ;
	public $RefererUrlLocale = 0 ;
	public $ScriptsReferantsSurs = array() ;
	public $RefererScriptLocal = 0 ;
	public $NecessiteMembreConnecte = 0 ;
	public $Privileges = array() ;
	public $MessageMalRefere = "<p>Ce composant n'est pas bien refere. Il ne peut etre affiche</p>" ;
	public $MessageInaccessible = "<p>Vous n'avez pas les droits necessaires pour afficher ce composant.</p>" ;
	public $MaxRendus = 0 ;
	public $MessageMaxRendusAtteint = "<p>Vous avez atteint le maximum de rendus autoris&eacute;s</p>" ;
	public $DelaiExpirRendu = 300 ;
	public $InfosRendu = array() ;
	protected function RestaureInfosRendu()
	{
		if(! in_array($_SESSION[$this->IDInstanceCalc."_InfosRendu"], $_SESSION))
		{
			$this->InfosRendu = array() ;
			return ;
		}
		$this->InfosRendu = unserialize($_SESSION[$this->IDInstanceCalc."_InfosRendu"]) ;
	}
	protected function SauveInfosRendu()
	{
		$_SESSION[$this->IDInstanceCalc."_InfosRendu"] = serialize($this->InfosRendu) ;
	}
	protected function InsereInfoRenduEnCours()
	{
		if($this->MaxRendus == 0)
		{
			return ;
		}
		$info = new \Pv\ZoneWeb\ElementRendu\Info() ;
		$info->TimestampDebut = date("U") ;
		$info->Index = count($this->InfosRendu) ;
		$this->InfosRendu[] = & $info ;
	}
	protected function RetireInfoRenduEnCours()
	{
		if($this->MaxRendus == 0)
		{
			return ;
		}
		array_splice($this->InfosRendu, $info->Index, 1) ;
	}
	protected function VideRendusExpires()
	{
		$indexes = array() ;
		foreach($this->InfosRendu as $i => $info)
		{
			if($info->TimestampDebut + $this->DelaiExpirRendu <= date("U"))
			{
				$indexes[] = $i ;
			}
		}
		if(count($indexes) > 0)
		{
			$infosRendu = $this->InfosRendu ;
			$this->InfosRendu = array() ;
			foreach($infosRendu as $i => $info)
			{
				if(in_array($i, $indexes))
				{
					continue ;
				}
				$info->Index = count($this->InfosRendu) ;
				$this->InfosRendu[] = $info ;
			}
		}
	}
	public function MaxRendusAtteint()
	{
		if($this->MaxRendus <= 0)
		{
			return 0 ;
		}
		$ok = 0 ;
		$this->RestaureInfosRendu() ;
		$this->VideRendusExpires() ;
		if(count($this->InfosRendu) > $this->MaxRendus)
		{
			$ok = 1 ;
		}
		$this->SauveInfosRendu() ;
		return $ok ;
	}
	public function ImpressionEnCours()
	{
		return $this->EstPasNul($this->ZoneParent) && $this->ZoneParent->ImpressionEnCours() ;
	}
	public function EstAccessible()
	{
		if(! $this->NecessiteMembreConnecte)
		{
			return 1 ;
		}
		return $this->ZoneParent->PossedePrivileges($this->Privileges) ;
	}
	public function EstBienRefere()
	{
		return 1 ;
	}
	protected function RenduInaccessible()
	{
		$ctn = $this->MessageInaccessible ;
		return $ctn ;
	}
	protected function RenduMalRefere()
	{
		$ctn = $this->MessageMalRefere ;
		return $ctn ;
	}
}