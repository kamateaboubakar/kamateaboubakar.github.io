<?php

namespace Pv\ZoneWeb\TableauDonnees\FormatColonne ;

class PlusDetail extends \Pv\ZoneWeb\TableauDonnees\FormatColonne\FormatColonne
{
	public $MaxCaracteresIntro = 40 ;
	public $HauteurBlocDetail = '225px' ;
	public $ArrPlBlocDetail = 'white' ;
	public $CouleurBlocDetail = 'black' ;
	public $CouleurBordureBlocDetail = '#e8e8e8' ;
	public $TailleBordureBlocDetail = '4px' ;
	protected $RenduSourceInclus = 0 ;
	protected $IndexLigne = 0 ;
	public function Encode(& $composant, $colonne, $ligne)
	{
		$valeur = '' ;
		if(isset($ligne[$colonne->NomDonnees]))
			$valeur = $ligne[$colonne->NomDonnees] ;
		$valeurIntro = substr($valeur, 0, $this->MaxCaracteresIntro) ;
		if(strlen($valeurIntro) < strlen($valeur))
		{
			$valeurIntro .= "..." ;
		}
		$this->IndexLigne++ ;
		if(strlen($valeur) == strlen($valeurIntro))
		{
			return $valeur ;
		}
		$rendu = '' ;
		if($this->RenduSourceInclus == 0)
		{
			$rendu .= '<style type="text/css">
.detail-'.$this->IDInstanceCalc.' {
position: relative;
display: inline-block;
}
.detail-'.$this->IDInstanceCalc.':hover .tooltiptext {
visibility: visible;
}
.detail-'.$this->IDInstanceCalc.' .tooltiptext {
visibility: hidden;
width: 100%;
overflow: scroll ;
top: 50%;
left: 20% ;
margin-left: -40px;
background-color: '.$this->ArrPlBlocDetail.';
padding:8px ;
border:'.$this->TailleBordureBlocDetail.' solid '.$this->CouleurBordureBlocDetail.' ;
color: '.$this->CouleurBlocDetail.' ;
height:'.$this->HauteurBlocDetail.' ;
text-align: center;
border-radius: 2px;
position: absolute;
z-index: 1;
}
</style>' ;
			$this->RenduSourceInclus = 1 ;
		}
		$rendu .= '<div class="detail-'.$this->IDInstanceCalc.'">'.htmlentities($valeurIntro).'<span class="tooltiptext">'.htmlentities($valeur).'</span></div>' ;
		return $rendu ;
	}
}