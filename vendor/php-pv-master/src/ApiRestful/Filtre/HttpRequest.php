<?php

namespace Pv\ApiRestful\Filtre ;

class HttpRequest extends Filtre
{
	public $Role = "request" ;
	public $TypeFormatRegexp ;
	public $FormatRegexp ;
	public $MessageErreurRegexp ;
	public $TypeLiaisonParametre = "request" ;
	public $AccepteTagsHtml = 1 ;
	public $AccepteTagsSuspicieux = 0 ;
	public $ValeurBruteNonCorrigee = false ;
	protected function EnleveTagsHtml($valeur)
	{
		/*
		$tag = new HtmlTag() ;
		$tag->LoadFromText($valeur) ;
		return $tag->Preview ;
		$result = str_get_html($valeur)->plaintext ;
		return $result ;
		*/
		return strip_tags($valeur) ;
	}
	protected function EnleveTagsSuspicieux($valeur)
	{
		/*
		$tag = new HtmlTag() ;
		$tag->SafeMode = 1 ;
		$tag->LoadFromText($valeur) ;
		return $tag->GetContent(true) ;
		*/
		$parser = new \SafeHTML();
		$result = $parser->parse($valeur);
		return $result ;
	}
	protected function CorrigeValeurBrute($valeur)
	{
		if(! is_array($valeur) && ! is_scalar($valeur))
		{
			return $this->ValeurParDefaut ;
		}
		if(is_array($valeur))
		{
			$resultat = array() ;
			foreach($valeur as $cle => $sousVal)
			{
				$resultat[$cle] = $this->CorrigeValeurBrute($sousVal) ;
			}
		}
		else
		{
			if($this->AccepteTagsHtml == 0)
			{
				$resultat = $this->EnleveTagsHtml($valeur) ;
			}
			elseif($this->AccepteTagsSuspicieux == 0)
			{
				$resultat = $this->EnleveTagsSuspicieux($valeur) ;
			}
			else
			{
				$resultat = $valeur ;
			}
		}
		return $resultat ;
	}
	protected function ExtraitValeurFormattee($valeur)
	{
		$resultat = "" ;
		if(is_array($valeur))
		{
			$resultat = join(";", $valeur) ;
		}
		else
		{
			$resultat = $valeur ;
		}
		return $resultat ;
	}
	protected function CalculeValeurBruteNonCorrigee()
	{
		$this->ValeurBruteNonCorrigee = (array_key_exists($this->NomParametreLie, $_REQUEST)) ? $_REQUEST[$this->NomParametreLie] : $this->ValeurVide ;
	}
	public function ObtientValeurParametre()
	{
		$this->CalculeValeurBruteNonCorrigee() ;
		$this->ValeurBrute = $this->CorrigeValeurBrute($this->ValeurBruteNonCorrigee) ;
		return $this->ExtraitValeurFormattee($this->ValeurBrute) ;
	}
	public function FormatTexte()
	{
		$valTemp = $this->Lie() ;
		if($this->AccepteTagsHtml)
		{
			$valTemp = strip_tags($valTemp) ;
			$valTemp = slugify($valTemp, false) ;
		}
		return $valTemp ;
	}
}