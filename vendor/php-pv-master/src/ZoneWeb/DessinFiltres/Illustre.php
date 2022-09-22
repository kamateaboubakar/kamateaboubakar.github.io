<?php

namespace Pv\ZoneWeb\DessinFiltres ;

class Illustre extends \Pv\ZoneWeb\DessinFiltres\Html
{
	public static $StyleGlobalInclus = 0 ;
	public $AlignIcone = "droite" ;
	public static function RenduStyleGlobal()
	{
		$val = \Pv\ZoneWeb\DessinFiltres\Illustre::$StyleGlobalInclus ;
		if($val == 1)
		{
			return "" ;
		}
		return '<style type="text/css">
.editeur-illustr { 
position: relative;
margin-bottom:12px ;
}
.editeur-illustr .icone-illustr {
position: absolute;
padding: 10px;
pointer-events: none;
}
.illustr-gauche .icone-illustr  { left:  0px;}
.illustr-droite .icone-illustr { right: 0px;}

/* add padding  */
.illustr-gauche > input, .illustr-gauche > select { padding-left:  30px; }
.illustr-droite > input, .illustr-droite > select { padding-right: 30px; }
</style>' ;
		\Pv\ZoneWeb\DessinFiltres\Illustre::$StyleGlobalInclus = 1 ;
	}
	public function Execute(& $script, & $composant, $parametres)
	{
		$filtres = $composant->ExtraitFiltresDeRendu($parametres, $this->FiltresCaches) ;
		$ctn = '' ;
		$ctn .= \Pv\ZoneWeb\DessinFiltres\Illustre::RenduStyleGlobal() ;
		$alignIcone = ($this->AlignIcone == "droite") ? "droite" : "gauche" ;
		$ctn .= '<div' ;
		if($this->Largeur != '')
		{
			$ctn .= ' style="width:'.$this->Largeur.'px"' ;
		}
		$ctn .= '>'.PHP_EOL ;
		$nomFiltres = array_keys($filtres) ;
		$filtreRendus = 0 ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = $filtres[$nomFiltre] ;
			if($filtre->LectureSeule)
			{
				$ctn .= '<input type="hidden" id="'.htmlentities($filtre->ObtientIDComposant()).'" name="'.htmlentities($filtre->ObtientNomComposant()).'" value="'.htmlentities($filtre->Lie()).'" />'.PHP_EOL ;
				continue ;
			}
			$ctn .= '<div class="editeur-illustr">'.PHP_EOL ;
			if($alignIcone == "gauche")
			{
				$ctn .= $this->RenduIconeFiltre($alignIcone, $filtre) ;
			}
			$ctn .= $this->RenduFiltre($filtre, $composant).PHP_EOL ;
			if($alignIcone == "droite")
			{
				$ctn .= $this->RenduIconeFiltre($alignIcone, $filtre) ;
			}
			$ctn .= '</div>'.PHP_EOL ;
			$filtreRendus++ ;
		}
		$ctn .= '</div>' ;
		return $ctn ;
	}
	protected function RenduIconeFiltre($alignIcone, & $filtre)
	{
		$ctn = '' ;
		$ctn .= '<i class="illustr-'.$alignIcone.' '.$filtre->NomClasseCSSIcone.'">'.(($filtre->CheminIcone != "") ? '<img src="'.$filtre->CheminIcone.'" />' : '').'</i>' ;
		return $ctn ;
	}
}