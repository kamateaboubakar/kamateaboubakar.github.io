<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneEtiquette extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $Libelle = "" ;
	public $UtiliserValeurSiLibelleVide = 1 ;
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$styleCSS = '' ;
		$ctn .= '<input name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' type="hidden"' ;
		$ctn .= ' value="'.htmlentities($this->Valeur).'"' ;
		$ctn .= ' />' ;
		$ctn .= '<span' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		$ctn .= '>' ;
		$ctn .= htmlentities($this->ObtientLibelle()) ;
		$ctn .= '</span>' ;
		return $ctn ;
	}
	public function RenduEtiquette()
	{
		return '<span id="'.$this->IDInstanceCalc.'">'.htmlentities($this->ObtientLibelle()).'</span>' ;
	}
	public function ObtientLibelle()
	{
		$resultat = $this->Libelle ;
		if($this->Libelle == "" && $this->UtiliserValeurSiLibelleVide)
		{
			$resultat = $this->Valeur ;
		}
		return $resultat ;
	}
}