<?php

namespace Pv\ZoneWeb\Action ;

class RenduPage extends \Pv\ZoneWeb\Action\Action
{
	public $TitreDocument ;
	public $ContenusCSS = array() ;
	public $ContenusJs = array() ;
	public $CtnExtraHead ;
	public $InclureCtnJsEntete = 0 ;
	public $CtnAttrsBody = "" ;
	public function InscritContenuCSS($contenu)
	{
		$ctnCSS = new \Pv\ZoneWeb\BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritLienCSS($href)
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritContenuJs($contenu)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritContenuJsCmpIE($contenu, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJs($src)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJsCmpIE($src, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function RenduLienCSS($href)
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuCSS($contenu)
	{
		$ctnCSS = new \Pv\ZoneWeb\BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuJsInclus($contenu)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduContenuJsCmpIEInclus($contenu, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsInclus($src)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsCmpIEInclus($src, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	protected function RenduCtnsCSS()
	{
		$ctn = '' ;
		for($i=0; $i<count($this->ContenusCSS); $i++)
		{
			$ctnCSS = $this->ContenusCSS[$i] ;
			$ctn .= $ctnCSS->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	protected function RenduCtnsJs()
	{
		$ctn = '' ;
		for($i=0; $i<count($this->ContenusJs); $i++)
		{
			$ctnJs = $this->ContenusJs[$i] ;
			$ctn .= $ctnJs->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	protected function RenduEnteteDoc()
	{
		$ctn = '' ;
		$ctn .= '<!doctype html>'.PHP_EOL ;
		$ctn .= '<head>'.PHP_EOL ;
		$ctn .= '<title>'.$this->TitreDocument.'</title>'.PHP_EOL ;
		$ctn .= $this->RenduCtnsCSS() ;
		if($this->InclureCtnJsEntete == 1)
		{
			$ctn .= $this->RenduCtnsJs() ;
		}
		$ctn .= $this->CtnExtraHead ;
		$ctn .= '</head>'.PHP_EOL ;
		$ctn .= '<body'.(($this->CtnAttrsBody != '') ? ' '.$this->CtnAttrsBody :  '').'>';
		return $ctn ;
	}
	protected function RenduPiedDoc()
	{
		$ctn = '' ;
		if($this->InclureCtnJsEntete == 0)
		{
			$ctn .= $this->RenduCtnsJs() ;
		}
		$ctn .= '</body>'.PHP_EOL ;
		$ctn .= '</html>' ;
		return $ctn ;
	}
	protected function PrepareDoc()
	{
	}
	public function Execute()
	{
		$this->PrepareDoc() ;
		echo $this->RenduEnteteDoc() ;
		echo $this->RenduCorpsDoc() ;
		echo $this->RenduPiedDoc() ;
		exit ;
	}
	protected function RenduCorpsDoc()
	{
		return '' ;
	}
}