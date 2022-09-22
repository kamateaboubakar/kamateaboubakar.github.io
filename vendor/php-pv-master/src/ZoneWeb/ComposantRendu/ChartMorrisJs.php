<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class ChartMorrisJs extends \Pv\ZoneWeb\ComposantRendu\FiltrableJs
{
	public $NomColonneX ;
	public $NomColonnesY = array() ;
	public $LibellesY = array() ;
	public $CfgInit ;
	public $TypeChart = "Area" ;
	public $CheminFichierJs = "vendor/morrisjs/morris.min.js" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CfgInit = new \Pv\ZoneWeb\ComposantRendu\CfgChartMorrisJs() ;
		$this->CfgInit->element = $this->IDInstanceCalc ;
	}
	public function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduInscritLienJs($this->CheminFichierJs) ;
		$ctn .= $this->RenduInscritContenuJs('jQuery(function() {
Morris.'.$this->TypeChart.'('.svc_json_encode($this->CfgInit).') ;
}) ;') ;
		return $ctn ;
	}
	public function CalculeElementsRendu()
	{
		$this->CfgInit->data = array() ;
		if($this->NomColonneX == "")
		{
			return ;
		}
		$fourn = & $this->FournisseurDonnees ;
		$lgns = $fourn->SelectElements(array(), $this->ObtientFiltresSelection()) ;
		if(is_array($lgns))
		{
			foreach($lgns as $i => $lgn)
			{
				$donneesStat = array() ;
				$donneesStat[$this->NomColonneX] = (isset($lgn[$this->NomColonneX])) ? $lgn[$this->ColonneY] : 0 ;
				foreach($this->NomColonnesY as $j => $nomCol)
				{
					$donneesStat[$nomCol] = (isset($lgn[$nomCol])) ? $lgn[$nomCol] : 0 ;
				}
				$this->CfgInit->data[] = $donneesStat ;
			}
		}
		$this->CfgInit->xkey = $this->NomColonneX ;
		$this->CfgInit->ykeys = $this->NomColonnesY ;
		$this->CfgInit->labels = $this->LibellesY ;
	}
	protected function RenduDispositifBrutSpec()
	{
		$ctn = '' ;
		$ctn .= '<div id="'.$this->IDInstanceCalc.'"></div>' ;
		return $ctn ;
	}
}