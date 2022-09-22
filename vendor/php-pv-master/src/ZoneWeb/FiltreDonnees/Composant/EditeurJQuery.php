<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class EditeurJQuery extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	protected $CfgInst ;
	protected $FonctsInst = array() ;
	protected function CtnJSCfgInst()
	{
		$ctn = "" ;
		$ctn .= 'var cfgInst'.$this->IDInstanceCalc.' = '.svc_json_encode($this->CfgInst).' ;'."\n" ;
		foreach($this->FonctsInst as $i => $fonctInst)
		{
			if($fonctInst->Contenu == "")
			{
				continue ;
			}
			$ctn .= 'cfgInst'.$this->IDInstanceCalc.'.'.$fonctInst->NomMembreInst.' = '.$fonctInst->CtnJSDef().PHP_EOL ;
		}
		return $ctn ;
	}
	protected function CtnJSDeclInst()
	{
		$ctn = '' ;
		$ctn .= 'jQuery("#'.$this->IDInstanceCalc.'").find(cfgInst'.$this->IDInstanceCalc.') ;' ;
		return $ctn ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CfgInst = $this->CreeCfgInst() ;
		$this->InitFonctsInst() ;
	}
	protected function CreeCfgInst()
	{
		return new StdClass() ;
	}
	protected function InitFonctsInst()
	{
	}
	protected function RenduDispositifBrut()
	{
		$ctn = parent::RenduDispositifBrut().PHP_EOL ;
		$ctn .= $this->RenduInstJS() ;
		return $ctn ;
	}
	protected function RenduInstJS()
	{
		$ctn = '' ;
		$ctn .= $this->RenduContenuJs(
'jQuery(function() {
'.$this->CtnJSCfgInst().'
'.$this->CtnJSDeclInst().'
}) ;') ;
		return $ctn ;
	}
}