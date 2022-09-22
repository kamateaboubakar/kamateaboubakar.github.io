<?php

namespace Pv\ZoneWeb\ArborescenceDonnees ;

class DefGroupeCol extends \Pv\ZoneWeb\ArborescenceDonnees\DefCol
{
	public $RenduDebutParDefaut = '' ;
	public $RenduVideParDefaut = '' ;
	public $IntegrAutoValActuelle = 0 ;
	protected function PrepareRendu(& $arbr, $indLgn, $indCol, $donnees)
	{
		parent::PrepareRendu($arbr, $indLgn, $indCol, $donnees) ;
		$this->RenduDebutParDefaut .= 
		$this->RenduDebutParDefaut .= '${VALEUR_ACTUELLE}</td><td width="100%"><table width="100%" cellspacing="0" cellpadding="0"><tr>' ;
	}
}