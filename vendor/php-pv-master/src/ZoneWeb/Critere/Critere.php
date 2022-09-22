<?php

namespace Pv\ZoneWeb\Critere ;

class Critere extends \Pv\ZoneWeb\Commande\ElementCommande
{
	public $TypeElementCommande = "critere" ;
	public $MessageErreur = "" ;
	public $FormatMessageErreur = 'Les champs ${ListeFiltres} ne n\'ont pas le bon format' ;
	public function PrepareRendu(& $form)
	{
		$nomFiltres = array_keys($this->FiltresCibles) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FiltresCibles[$nomFiltre] ;
			$this->PrepareRenduFiltre($filtre) ;
		}
	}
	protected function PrepareRenduFiltre(& $filtre)
	{
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
				$this->FormatMessageErreur,
				array(
					"ListeFiltres" => join(", ", $filtreErreurs)
				)
			) ;
			return 0 ;
		}
		return ($this->MessageErreur == '') ? 1 : 0 ;
	}
	public function RenseigneErreur($format, $params=array())
	{
		$this->MessageErreur = \Pv\Misc::_parse_pattern(
			$format,
			$params
		) ;
		return 0 ;
	}
	protected function RespecteRegle(& $filtre)
	{
		return 1 ;
	}
}