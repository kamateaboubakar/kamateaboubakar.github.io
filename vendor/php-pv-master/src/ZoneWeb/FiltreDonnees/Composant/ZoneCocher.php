<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneCocher extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $ValeurVrai = 1 ;
	public $LibelleVrai = "Oui" ;
	public $LibelleFaux = "Non" ;
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduOption() ;
		return $ctn ;
	}
	public function RenduEtiquette()
	{
		return '<span id="'.$this->IDInstanceCalc.'">'.(($this->Valeur == $this->ValeurVrai) ? $this->LibelleVrai : $this->LibelleFaux).'</span>' ;
	}
	public function RenduOption()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= '<input name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' type="checkbox"' ;
		$ctn .= ' value="'.htmlspecialchars($this->ValeurVrai).'"' ;
		if($this->Valeur == $this->ValeurVrai)
		{
			$ctn .= ' checked' ;
		}
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= ' />' ;
		return $ctn ;
	}
}