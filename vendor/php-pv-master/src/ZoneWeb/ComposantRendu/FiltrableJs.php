<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class FiltrableJs extends \Pv\ZoneWeb\ComposantRendu\Filtrable
{
	protected static $SourceIncluse = 0 ;
	public $CfgInit ;
	protected function CreeCfgInit()
	{
		return new StdClass ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CfgInit = $this->CreeCfgInit() ;
	}
	protected function RenduDispositifBrut()
	{
		$this->CalculeElementsRendu() ;
		if($this->VerifiePreRequisRendu())
		{
			$ctn = '' ;
			$ctn .= $this->RenduSourceIncluse() ;
			$ctn .= $this->RenduDispositifBrutSpec() ;
			return $ctn ;
		}
		return $this->MsgPreRequisRenduNonVerifies() ;
	}
	protected function RenduSourceIncluse()
	{
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
	public function RenduInscritCtnCSS($contenu)
	{
		return $this->RenduInscritContenuCSS($contenu) ;
	}
	public function RenduInscritContenuCSS($contenu)
	{
		$ctn = '' ;
		$ctn .= '<style type="text/css">'.PHP_EOL 
			.$contenu.PHP_EOL 
			.'</style>' ;
		return $ctn ;
	}
	public function RenduInscritLienCSS($chemFich)
	{
		$ctn = '' ;
		$ctn .= '<link rel="stylesheet" type="text/css" href="'.$chemFich.'">' ;
		return $ctn ;
	}
	public function RenduInscritLienJs($chemFich)
	{
		$ctn = '' ;
		if($this->ZoneParent->InclureCtnJsEntete == 0)
		{
			$this->ZoneParent->InscritLienJs($chemFich) ;
		}
		else
		{
			$ctn .= '<script type="text/javascript" src="'.htmlspecialchars($chemFich).'"></script>' ;
		}
		return $ctn ;
	}
	public function RenduInscritContenuJs($contenuJs)
	{
		$ctn = '' ;
		if($this->ZoneParent->InclureCtnJsEntete == 0)
		{
			$this->ZoneParent->InscritContenuJs($contenuJs) ;
		}
		else
		{
			$ctn .= '<script type="text/javascript">'.PHP_EOL 
				.$contenuJs.PHP_EOL
				.'</script>' ;
		}
		return $ctn ;
	}
	public function RenduInscritCtnJs($contenuJs)
	{
		return $this->RenduInscritContenuJs($contenuJs) ;
	}
}