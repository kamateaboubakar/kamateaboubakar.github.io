<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneCocherBool extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $ValeurVrai = "1" ;
	public $ValeurFaux = "0" ;
	public $LibelleVrai = "Oui" ;
	public $LibelleFaux = "Non" ;
	public function RenduEtiquette()
	{
		return '<span id="'.$this->IDInstanceCalc.'">'.(($this->Valeur == $this->ValeurVrai) ? $this->LibelleVrai : $this->LibelleFaux).'</span>' ;
	}
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$styleCSS = '' ;
		$ctn .= '<input id="'.$this->IDInstanceCalc.'_Support" type="checkbox" value="'.htmlentities($this->ValeurVrai).'" onclick="document.getElementById(\''.$this->IDInstanceCalc.'\').value = (this.checked) ? '.htmlentities(svc_json_encode($this->ValeurVrai)).' : '.htmlentities(svc_json_encode($this->ValeurFaux)).';"'.(($this->Valeur == $this->ValeurVrai) ? ' checked' : '').' />' ;
		$ctn .= '<input name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' type="hidden"' ;
		$ctn .= ' value="'.htmlentities($this->Valeur).'"' ;
		$ctn .= ' />' ;
		return $ctn ;
	}
}