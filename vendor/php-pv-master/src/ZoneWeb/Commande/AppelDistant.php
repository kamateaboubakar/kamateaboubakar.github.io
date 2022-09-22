<?php

namespace Pv\ZoneWeb\Commande ;

class AppelDistant extends \Pv\ZoneWeb\Commande\Executer
{
	public $NomZoneAppelDistant ;
	public $NomMethodeDistante ;
	public $AppelDistant ;
	public $ResultDistant ;
	protected function VerifiePreRequis()
	{
		parent::VerifiePreRequis() ;
		if(! $this->ErreurNonRenseignee())
		{
			return ;
		}
		if($this->NomZoneAppelDistant == "" || ! isset($this->ZoneParent->ApplicationParent->IHMs[$this->NomZoneAppelDistant]))
		{
			$this->RenseigneErreur("Zone d'appels distants inexistante") ;
		}
		$this->ZoneAppelDistant = & $this->ZoneParent->ApplicationParent->IHMs[$this->NomZoneAppelDistant] ;
		$this->ZoneAppelDistant->ChargeConfig() ;
	}
	protected function CreeAppelDistant()
	{
		$appel = new PvAppelJsonDistant() ;
		$appel->method = $this->NomMethodeDistante ;
		$appel->args = $this->ExtraitArgsAppelDistant() ;
		return $appel ;
	}
	protected function ExecuteInstructions()
	{
		$this->AppelDistant = $this->CreeAppelDistant() ;
		$this->ResultDistant = $this->ZoneAppelDistant->TraiteAppel($this->AppelDistant) ;
		if($this->ResultDistant->Succes())
		{
			$this->ConfirmeSucces() ;
		}
		else
		{
			$msgErreur = '' ;
			if($this->ResultDistant->erreur->message != '')
			{
				$msgErreur .= $this->ResultDistant->erreur->code."#".$this->ResultDistant->erreur->message ;
			}
			else
			{
				$msgErreur = 'Erreur rencontr&eacute; : #'.$this->ResultDistant->erreur->code ;
			}
			$this->RenseigneErreur($msgErreur) ;
		}
	}
}