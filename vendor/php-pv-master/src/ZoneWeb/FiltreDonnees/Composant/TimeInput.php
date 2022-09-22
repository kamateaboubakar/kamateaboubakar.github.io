<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class TimeInput extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	public $CheminFichierJs = "js/timeInput.js" ;
	protected static $SourceIncluse = 0;
	public $UseValeurActuelleParDefaut = 1;
	protected function CorrigeValeur()
	{
		if($this->Valeur == "" && $this->UseValeurActuelleParDefaut)
		{
			$this->Valeur = date("H:i:s") ;
		}
	}
	protected function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminFichierJs) ;
		return $ctn ;
	}
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$this->CorrigeValeur() ;
		$ctn .= '<script>drawTimeInput('.svc_json_encode($this->NomElementHtml).', '.svc_json_encode($this->Valeur).') ;</script>' ;
		return $ctn ;
	}
}