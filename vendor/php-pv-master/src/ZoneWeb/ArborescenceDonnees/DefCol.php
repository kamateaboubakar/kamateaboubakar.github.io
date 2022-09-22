<?php

namespace Pv\ZoneWeb\ArborescenceDonnees ;

class DefCol extends \Pv\ZoneWeb\ArborescenceDonnees\DefNoeud
{
	public $IntegrAutoValActuelle = 1 ;
	public $RenduDebutParDefaut = '' ;
	public $RenduVideParDefaut = '' ;
	public $RenduFinParDefaut = '</td></tr></table>' ;
	protected function RenduCtnDebutCell()
	{
		$ctn = '' ;
		$ctn .= '<td' ;
		if($this->Largeur != '')
			$ctn .= ' width="'.$this->LargeurElement.'"' ;
		if($this->AlignElement != '')
			$ctn .= ' align="'.$this->AlignElement.'"' ;
		if($this->AlignVElement != '')
			$ctn .= ' valign="'.$this->AlignVElement.'"' ;
		$ctn .= '>' ;
		if($this->IntegrAutoValActuelle)
		{
			$ctn .= '${VALEUR_ACTUELLE}</td>' ;
		}
		return $ctn ;
	}
	protected function PrepareRendu(& $arbr, $indLgn, $indCol, $donnees)
	{
		parent::PrepareRendu($arbr, $indLgn, $indCol, $donnees) ;
		$this->RenduDebutParDefaut = $this->RenduCtnDebutCell() ;
	}
}