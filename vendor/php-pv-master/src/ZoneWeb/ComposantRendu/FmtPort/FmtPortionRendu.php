<?php

namespace Pv\ZoneWeb\ComposantRendu\FmtPort ;

class FmtPortionRendu extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $PrefixeEncUrl = "url_" ;
	public $EncoderUrl = 1 ;
	public $PrefixeEncHtmlEnt = "html_" ;
	public $EncoderHtmlEnt = 1 ;
	public $Encodeurs = array() ;
	public $Params = array() ;
	public $Contenu = "" ;
	public $NomClasseCSS ;
	protected $ParamsCalc = array() ;
	protected function RenduVideActif()
	{
		return ($this->Contenu == '') ;
	}
	public function & InsereEncodeurDateFr($nomParams=array(), $prefixe="\Pv\Misc::date_fr")
	{
		$encodeur = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\DateFr($prefixe) ;
		$encodeur->NomParams = $nomParams ;
		$this->InsereEncodeur($encodeur) ;
		return $encodeur ;
	}
	public function & InsereEncodeurNonVide($nomParams=array(), $contenu='${luimeme}', $prefixe="non_vide")
	{
		$encodeur = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\NonVide($prefixe) ;
		$encodeur->NomParams = $nomParams ;
		$encodeur->Contenu = $contenu ;
		$this->InsereEncodeur($encodeur) ;
		return $encodeur ;
	}
	public function & InsereEncodeur($encodeur)
	{
		$this->Encodeurs[] = $encodeur ;
		return $encodeur ;
	}
	protected function ObtientEncodeurs()
	{
		$encodeurs = $this->Encodeurs;
		if($this->EncoderUrl)
		{
			$encodeurs[] = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Url($this->PrefixeEncUrl) ;
		}
		if($this->EncoderHtmlEnt)
		{
			$encodeurs[] = new \Pv\ZoneWeb\ComposantRendu\EncPortionRendu\Html($this->PrefixeEncHtmlEnt) ;
		}
		return $encodeurs ;
	}
	protected function DetecteParamsCalc()
	{
		$this->ParamsCalc = $this->Params ;
		$encodeurs = $this->ObtientEncodeurs() ;
		foreach($encodeurs as $i => $encodeur)
		{
			$elem = $this->Params ;
			$valeurs = ($encodeur->AppliquerTout) ? $elem : array_intersect_key($elem, array_flip($encodeur->NomParams)) ;
			$params = $encodeur->Execute($valeurs, $elem) ;
			if(count($params) == 0)
			{
				continue ;
			}
			$params = \Pv\Misc::array_apply_prefix($params, $encodeur->Prefixe) ;
			$this->ParamsCalc = array_merge($this->ParamsCalc, $params) ;
		}
	}
	public function EstVide()
	{
		return (empty($this->Contenu)) ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		if($this->RenduVideActif())
		{
			return $ctn ;
		}
		$this->DetecteParamsCalc() ;
		$ctn .= '<div id="'.$this->IDInstanceCalc ;
		if($this->NomClasseCSS != "")
			$ctn .= ' class="'.$this->NomClasseCSS.'"' ;
		$ctn .= '">' ;
		$ctn .= \Pv\Misc::_parse_pattern($this->Contenu, $this->ParamsCalc) ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}