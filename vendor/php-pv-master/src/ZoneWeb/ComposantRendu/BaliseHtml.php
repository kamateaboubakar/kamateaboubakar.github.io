<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class BaliseHtml extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $IDElementHtml = "" ;
	public $NomElementHtml = "" ;
	public $TitreElementHtml = "" ;
	public $ClassesCSS = array() ;
	public $StyleCSS = "" ;
	public static $SourceInclus = 0 ;
	public static $CheminSource = "" ;
	public $BaliseInclusionSource = null ;
	protected function InclutSource()
	{
		if($this->ObtientValeurStatique('SourceInclus') == 1 || $this->ObtientValeurStatique('CheminSource') == "")
		{
			return "" ;
		}
		$this->BaliseInclusionSource = new \Pv\ZoneWeb\LienFichierJs() ;
		$this->BaliseInclusionSource->Src = $this->ObtientValeurStatique('CheminSource') ;
		$this->BaliseInclusionSource->AdopteScript("source".get_class($this), $this->ScriptParent) ;
		$this->AffecteValeurStatique('SourceInclus', 1) ;
		return $this->BaliseInclusionSource->RenduDispositif() ;
	}
	public function CorrigeIDsElementHtml()
	{
		if($this->NomElementHtml == '')
		{
			$this->NomElementHtml = $this->NomElementScript ;
		}
	}
}