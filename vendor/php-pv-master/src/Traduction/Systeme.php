<?php

namespace Pv\Traduction ;

class Systeme extends \Pv\Objet\Objet
{
	public $Traducteurs = array() ;
	public $NomTraducteurActif ;
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeTraducteurs() ;
		$this->DetecteTraducteurActif() ;
	}
	protected function DetecteTraducteurActif()
	{
		if(count($this->Traducteurs) > 0)
		{
			$nomTrads = array_keys($this->Traducteurs) ;
			$this->NomTraducteurActif = $nomTrads[0] ;
		}
	}
	protected function ChargeTraducteurs()
	{
	}
	public function TraducteurActif()
	{
		$nomTrad = $this->NomTraducteurActif ;
		$traducteur = new \Pv\Traduction\Traducteur() ;
		$traducteur->EstNul = 1 ;
		if(isset($this->Traducteurs[$nomTrad]))
		{
			$traducteur = & $this->Traducteurs[$nomTrad] ;
		}
		return $traducteur ;
	}
	public function Execute($nomExpr, $params=array(), $valParDefaut='', $nomTrad='')
	{
		$traducteur = null;
		if($nomTrad == '' || ! isset($this->Traducteurs[$nomTrad]))
		{
			$traducteur = & $this->Traducteurs[$nomTrad] ;
		}
		else
		{
			$traducteur = $this->TraducteurActif() ;
		}
		return $traducteur->Execute($nomExpr, $params, $valParDefaut) ;
	}
	public function ActiveTraducteur($nomTrad='')
	{
		if(! isset($this->Traducteurs[$nomTrad]))
		{
			return ;
		}
		$this->NomTraducteurActif = $nomTrad ;
	}
}