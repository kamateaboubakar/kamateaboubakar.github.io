<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class EditeurChoix extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteChoix
{
	protected static $SourceIncluse = 0 ;
	protected function RenduSourceIncluse()
	{
		if($this->ObtientValStatique("SourceIncluse") == 1)
			return "" ;
		$ctn = $this->RenduSourceBrut() ;
		$this->AffecteValStatique("SourceIncluse", 1) ;
		return $ctn ;
	}
	protected function RenduSourceBrut()
	{
		return "" ;
	}
	protected function RenduEditeurBrut()
	{
		return "" ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$this->CorrigeIDsElementHtml() ;
		$this->InitFournisseurDonnees() ;
		if(! $this->EstNul($this->FournisseurDonnees))
		{
			$this->ChargeConfigFournisseurDonnees() ;
			$this->CalculeElementsRendu() ;
            $ctn .= $this->RenduSourceIncluse() ;
            $ctn .= $this->RenduEditeurBrut() ;
		}
		else
		{
			die("Le composant ".$this->IDInstanceCalc." necessite un fournisseur de donnees.") ;
		}
		return $ctn ;
	}
}