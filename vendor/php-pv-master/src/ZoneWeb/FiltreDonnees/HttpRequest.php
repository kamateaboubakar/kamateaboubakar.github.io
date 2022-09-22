<?php

namespace Pv\ZoneWeb\FiltreDonnees ;

class HttpRequest extends \Pv\ZoneWeb\FiltreDonnees\FiltreDonnees
{
	public $Role = "request" ;
	public $TypeFormatRegexp ;
	public $FormatRegexp ;
	public $MessageErreurRegexp ;
	public $TypeLiaisonParametre = "request" ;
	public $AccepteTagsHtml = 1 ;
	public $AccepteTagsSuspicieux = 0 ;
	public $ValeurBruteNonCorrigee = false ;
	public $SeparateurValeurs = ";" ;
	protected function EnleveTagsHtml($valeur)
	{
		return strip_tags($valeur) ;
	}
	protected function EnleveTagsSuspicieux($valeur)
	{
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
			$resultat = join($this->SeparateurValeurs, $valeur) ;
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
			$valTemp = \Pv\Misc::slugify($valTemp, false) ;
		}
		return $valTemp ;
	}
}