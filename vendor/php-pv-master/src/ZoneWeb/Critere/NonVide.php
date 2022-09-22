<?php

namespace Pv\ZoneWeb\Critere ;

class NonVide extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} ne doivent pas &ecirc;tre vides' ;
	public $FormatMessageErreurUn = 'Le champ ${ListeFiltres} ne doit pas &ecirc;tre vide' ;
	protected function PrepareRenduFiltre(& $filtre)
	{
		$filtre->InsereSuffxErr("*") ;
	}
	public function EstRespecte()
	{
		if(count($this->FiltresCibles) == 0)
		{
			return 1 ;
		}
		$this->MessageErreur = "" ;
		$nomFiltres = array_keys($this->FiltresCibles) ;
		$filtreErreurs = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FiltresCibles[$nomFiltre] ;
			$filtre->Lie() ;
			$ok = $this->RespecteRegle($filtre) ;
			if(! $ok)
			{
				$filtreErreurs[] = $filtre->ObtientLibelle() ;
			}
		}
		if(count($filtreErreurs) > 0)
		{
			$this->MessageErreur = \Pv\Misc::_parse_pattern(
				(count($filtreErreurs) == 1) ? $this->FormatMessageErreurUn : $this->FormatMessageErreur,
				array(
					"ListeFiltres" => join(", ", $filtreErreurs)
				)
			) ;
			return 0 ;
		}
		return ($this->MessageErreur == '') ? 1 : 0 ;
	}
	protected function RespecteRegle(& $filtre)
	{
		$valeur = trim($filtre->ValeurParametre) ;
		return ($valeur !== "" && $valeur !== null) ? 1 : 0 ;
	}
}