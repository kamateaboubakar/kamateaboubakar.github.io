<?php

namespace Pv\ZoneWeb\SliderJs ;

class JQueryCamera extends \Pv\ZoneWeb\ComposantRendu\FiltrableJs
{
	public $CheminJs = "js/camera.min.js" ;
	public $CheminCSS = "css/camera.css" ;
	public $CheminJQueryEasing = "js/jquery.easing.js" ;
	public $InclureJQueryEasing = 1 ;
	public $FormatCheminImage = '' ;
	public $NomColCaption = '' ;
	public $FormatUrl = '' ;
	public $ElementsEnCours = array() ;
	protected function CreeCfgInit()
	{
		return new \Pv\ZoneWeb\SliderJs\CfgInitJQueryCamera() ;
	}
	protected function CtnJsInstall()
	{
		return 'jQuery("#'.$this->IDInstanceCalc.'").camera('.svc_json_encode($this->CfgInit).') ;' ;
	}
	protected function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduInscritLienCSS($this->CheminCSS) ;
		if($this->InclureJQueryEasing == 1)
		{
			$ctn .= $this->RenduInscritLienJs($this->CheminJQueryEasing) ;
		}
		$ctn .= $this->RenduInscritLienJs($this->CheminJs) ;
		$ctn .= $this->RenduInscritContenuJs('jQuery(function() {
'.$this->CtnJsInstall().'
}) ;') ;
		return $ctn ;
	}
	protected function RenduDispositifBrutSpec()
	{
		$ctn = '' ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'">'.PHP_EOL ;
		$lgns = $this->FournisseurDonnees->SelectElements(array(), $this->ObtientFiltresSelection()) ;
		$this->ElementsEnCours = array() ;
		foreach($lgns as $i => $lgn)
		{
			$valeurs = $this->ExtraitValeursLgnDonnees($lgn) ;
			$this->ElementsEnCours[] = $valeurs ;
			$valeursUrl = array_map('urlencode', $valeurs) ;
			$cheminImage = \Pv\Misc::_parse_pattern($this->FormatCheminImage, $valeurs) ;
			$url = \Pv\Misc::_parse_pattern($this->FormatUrl, $valeurs) ;
			$caption = (isset($valeurs[$this->NomColCaption])) ? $valeurs[$this->NomColCaption] : '' ;
			$ctn .= '<div data-src="'.htmlspecialchars($cheminImage).'"'.(($url != '') ? ' data-link="'.htmlspecialchars($url).'"' : '').'>'.PHP_EOL ;
			if($caption != '')
			{
				$ctn .= '<div class="camera_caption">'.PHP_EOL ;
				$ctn .= $caption.PHP_EOL ;
				$ctn .= '</div>'.PHP_EOL ;
			}
			$ctn .= '</div>'.PHP_EOL ;
		}
		$ctn .= '</div>' ;
		return $ctn ;
	}
}