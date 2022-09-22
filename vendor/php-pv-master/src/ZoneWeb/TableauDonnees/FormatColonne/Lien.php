<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class Lien
{
	public $FormatLibelle ;
	public $EncodeHtmlLibelle ;
	public $FormatURL ;
	public $FormatCheminIcone ;
	public $ClasseCSS ;
	public $ChaineAttributs ;
	public $Cible ;
	public static $InstanceJs = 0 ;
	public $InclureIcone = 1 ;
	public $InclureLibelle = 1 ;
	public $HauteurIcone = "18" ;
	public $NomDonneesValid = "" ;
	public $Privileges = array() ;
	public $ValeurVraiValid = 1 ;
	public $Visible = 1 ;
	public function Accepte($donnees)
	{
		$ok = ($this->RenduPossible() && ($this->NomDonneesValid == "" || (isset($donnees[$this->NomDonneesValid]) && $donnees[$this->NomDonneesValid] == $this->ValeurVraiValid))) ? 1 : 0 ;
		return $ok ;
	}
	public function InstrJsAccepte()
	{
		if($this->RenduPossible() == false)
		{
			return 'false' ;
		}
		if($this->NomDonneesValid == "")
		{
			return 'true' ;
		}
		return '(donnees['.svc_json_encode($this->NomDonneesValid).'] !== undefined && donnees['.svc_json_encode($this->NomDonneesValid).'] == '.svc_json_encode($this->ValeurVraiValid).') ? 1 : 0' ;
		return $ok ;
	}
	protected function RenduIcone($donnees, $donneesUrl)
	{
		$ctn = '' ;
		if(! $this->InclureIcone || $this->FormatCheminIcone == "")
		{
			return $ctn ;
		}
		$cheminIcone = \Pv\Misc::_parse_pattern($this->FormatCheminIcone, $donneesUrl) ;
		$ctn .= '<img src="'.$cheminIcone.'" height="'.$this->HauteurIcone.'" border="0" />' ;
		return $ctn ;
	}
	public function RenduPossible()
	{
		return ($this->Visible == 1) ;
	}
	public function Rendu($donnees)
	{
		if(! $this->RenduPossible())
		{
			return '' ;
		}
		return $this->RenduBrut($donnees) ;
	}
	public function InstrJsRendu()
	{
		if(! $this->RenduPossible())
		{
			return '' ;
		}
		return $this->InstrJsRenduBrut() ;
	}
	protected function ObtientHrefFmt($donneesUrl)
	{
		return \Pv\Misc::_parse_pattern($this->FormatURL, $donneesUrl) ;
	}
	protected function ObtientLibelleFmt(& $donnees)
	{
		return \Pv\Misc::_parse_pattern($this->FormatLibelle, $donnees) ;
	}
	protected function RenduBrut($donnees)
	{
		$ctn = '' ;
		$donneesUrl = array_map("urlencode", $donnees) ;
		$donneesSuff = \Pv\Misc::array_apply_suffix($donnees, "_brut") ;
		$href = $this->ObtientHrefFmt(array_merge($donneesUrl, $donneesSuff)) ;
		$libelle = $this->ObtientLibelleFmt($donnees) ;
		$ctn .= '<a href="'.htmlentities($href).'"' ;
		if($this->Cible != '')
		{
			$ctn .= ' target="'.$this->Cible.'"' ;
		}
		if($this->ChaineAttributs != '')
		{
			$ctn .= ' '.$this->ChaineAttributs ;
		}
		if($this->ClasseCSS != '')
		{
			$ctn .= ' class="'.$this->ClasseCSS.'"' ;
		}
		if($this->InclureLibelle == 0)
		{
			$ctn .= ' title="'.htmlspecialchars(\Pv\Misc::_parse_pattern($this->FormatLibelle, $donnees)).'"' ;
		}
		$ctn .= '>' ;
		$ctnIcone = $this->RenduIcone($donnees, $donneesUrl) ;
		$ctn .= $ctnIcone ;
		if($this->InclureLibelle)
		{
			if($ctnIcone != '')
			{
				$ctn .= ' ' ;
			}
			if($this->EncodeHtmlLibelle)
			{
				$libelle = htmlentities($libelle) ;
			}
			$ctn .= $libelle ;
		}
		$ctn .= '</a>' ;
		return $ctn ;
	}
	protected function InstrJsRenduBrut()
	{
		$instanceJs = \Pv\ZoneWeb\TableauDonnees\FormatColonne\Lien::$InstanceJs ;
		\Pv\ZoneWeb\TableauDonnees\FormatColonne\Lien::$InstanceJs++ ;
		$ctn = '' ;
		$ctn .= 'var href'.$instanceJs.' = '.svc_json_encode($this->FormatURL).' ;
var libelle'.$instanceJs.' = '.svc_json_encode($this->FormatLibelle).' ;
for(var n in donnees)
{
href'.$instanceJs.' = href'.$instanceJs.'.split("${" + n + "}").join(encodeURIComponent(donnees[n])) ;
libelle'.$instanceJs.' = libelle'.$instanceJs.'.split("${" + n + "}").join((donnees[n] !== null) ? donnees[n].replace(/[\u00A0-\u9999<>\&]/gim, function(indexTmp) {
return \'&#\'+indexTmp.charCodeAt(0)+\';\';
}) : "") ;
}
var noeud'.$instanceJs.' = document.createElement("a") ;
noeud'.$instanceJs.'.setAttribute("href", href'.$instanceJs.') ;'.PHP_EOL ;
		if($this->Cible != '')
		{
			$ctn .= 'noeud'.$instanceJs.'.setAttribute("target", '.svc_json_encode($this->Cible).') ;'.PHP_EOL ;
		}
		if($this->ChaineAttributs != '')
		{
			$attrs = explode(" ", $this->ChaineAttributs) ;
			foreach($attrs as $i => $attrStr)
			{
				$parts = explode("=", $attrStr, 2) ;
				$ctn .= 'noeud'.$instanceJs.'.setAttribute('.svc_json_encode($parts[0]).', '.((count($parts) == 2) ? svc_json_encode($parts[1]) : '""').') ;'.PHP_EOL ;
			}
		}
		if($this->ClasseCSS != '')
		{
			$ctn .= 'noeud'.$instanceJs.'.setAttribute("class", '.svc_json_encode($this->ClasseCSS).') ;'.PHP_EOL ;
		}
		if($this->InclureLibelle == 0)
		{
			$ctn .= 'noeud'.$instanceJs.'.setAttribute("title", libelle'.$instanceJs.') ;'.PHP_EOL ;
		}
		// $ctnIcone = $this->RenduIcone($donnees, $donneesUrl) ;
		// $ctn .= $ctnIcone ;
		if($this->InclureLibelle)
		{
			if($this->EncodeHtmlLibelle == true)
			{
				$ctn .= 'noeud'.$instanceJs.'.textContent = libelle'.$instanceJs.' ;'.PHP_EOL ;
			}
			else
			{
				$ctn .= 'noeud'.$instanceJs.'.innerHTML = libelle'.$instanceJs.' ;'.PHP_EOL ;
			}
		}
		$ctn .= 'noeudCellule.appendChild(noeud'.$instanceJs.') ;' ;
		return $ctn ;
	}
	public function DefinitValidite($nomDonnees, $valeurVrai=1)
	{
		$this->NomDonneesValid = $nomDonnees ;
		$this->ValeurVraiValid = $valeurVrai ;
	}
}