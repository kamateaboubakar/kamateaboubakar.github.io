<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class EditeurHtml extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	protected static $SourceIncluse = 0 ;
	protected function RenduSourceIncluse()
	{
		$nomClasse = get_class($this) ;
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
		$ctn .= $this->RenduSourceIncluse() ;
		$ctn .= $this->RenduEditeurBrut() ;
		return $ctn ;
	}
}