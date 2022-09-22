<?php

namespace Pv\ZoneWeb\DessinFiltres ;

class Html extends \Pv\ZoneWeb\DessinFiltres\DessinFiltres
{
	public $Largeur = "" ;
	public $MaxFiltresParLigne = 2 ;
	public $InclureRenduLibelle = 1 ;
	public $LargeurLibelles = "" ;
	public $LargeurEditeurs = "" ;
	public $InclureSeparateurFiltres = 1 ;
	public $ValeurSeparateurFiltres = "&nbsp;" ;
	protected function RenduMarquesFiltre(& $marques)
	{
		$ctn = '' ;
		foreach($marques as $i => $marque)
		{
			$ctn .= ' <span style="color:'.$marque->CouleurPolice.';">'.$marque->Contenu.'</span>' ;
		}
		return $ctn ;
	}
	public function RenduLibelleFiltre(& $filtre)
	{
		$ctn = '' ;
		$ctn .= '<label for="'.$filtre->ObtientIDElementHtmlComposant().'">' ;
		$ctn .= $this->RenduMarquesFiltre($filtre->PrefixesLibelle) ;
		$ctn .= $filtre->ObtientLibelle() ;
		$ctn .= $this->RenduMarquesFiltre($filtre->SuffixesLibelle) ;
		$ctn .= '</label>' ;
		return $ctn ;				
	}
	public function Execute(& $script, & $composant, $parametres)
	{
		$filtres = $composant->ExtraitFiltresDeRendu($parametres, $this->FiltresCaches) ;
		$ctn = '' ;
		$ctn .= '<table' ;
		if($this->Largeur != '')
		{
			$ctn .= ' width="'.$this->Largeur.'"' ;
		}
		$ctn .= '>'.PHP_EOL ;
		$colonnesTotalFusionnees = $this->MaxFiltresParLigne * 2 ;
		if($this->InclureSeparateurFiltres)
		{
			$colonnesTotalFusionnees += ($this->MaxFiltresParLigne - 1) ;
		}
		$nomFiltres = array_keys($filtres) ;
		$filtreRendus = 0 ;
		// echo count($filtres) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = $filtres[$nomFiltre] ;
			if($filtre->LectureSeule)
			{
				$ctn .= '<input type="hidden" id="'.htmlentities($filtre->ObtientIDComposant()).'" name="'.htmlentities($filtre->ObtientNomComposant()).'" value="'.htmlentities($filtre->Lie()).'" />'.PHP_EOL ;
				continue ;
			}
			if($filtreRendus % $this->MaxFiltresParLigne == 0)
			{
				$ctn .= '<tr>'.PHP_EOL ;
			}
			if($filtreRendus % $this->MaxFiltresParLigne > 0)
			{
				$ctn .= '<td>'.$this->ValeurSeparateurFiltres.'</td>'.PHP_EOL ;
			}
			if($this->InclureRenduLibelle)
			{
				$ctn .= '<td' ;
				$ctn .= ' valign="top"' ;
				$ctn .= '>'.PHP_EOL ;
				$ctn .= '<label for="'.$filtre->ObtientIDElementHtmlComposant().'">'.$this->RenduLibelleFiltre($filtre).'</label>'.PHP_EOL ;
				$ctn .= '</td>'.PHP_EOL ;
			}
			$ctn .= '<td' ;
			$ctn .= ' valign="top"' ;
			$ctn .= '>'.PHP_EOL ;
			$ctn .= $this->RenduFiltre($filtre, $composant).PHP_EOL ;
			$ctn .= '</td>'.PHP_EOL ;
			$filtreRendus++ ;
			if($filtreRendus % $this->MaxFiltresParLigne == 0)
			{
				$ctn .= '</tr>'.PHP_EOL ;
			}
		}
		if($filtreRendus % $this->MaxFiltresParLigne != 0)
		{
			$colonnesFusionnees = ($this->MaxFiltresParLigne - ($filtreRendus % $this->MaxFiltresParLigne)) * (($this->InclureRenduLibelle) ? 2 : 1) ;
			$colonnesFusionnees += ($this->MaxFiltresParLigne - 1) ;
			$ctn .= '<td colspan="'.$colonnesFusionnees.'">&nbsp;</td>'.PHP_EOL ;
			$ctn .= '</tr>'.PHP_EOL ;
		}
		$ctn .= '</table>' ;
		return $ctn ;
	}
	public function VersionTexte(& $composant, $parametres)
	{
		$filtres = $composant->ExtraitFiltresDeRendu($parametres) ;
		$nomFiltres = array_keys($filtres) ;
		$ctn = '' ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$ctn .= $this->RenduLibelleFiltre($filtre) ;
			$ctn .= ' : ' ;
			$ctn .= $filtre->Etiquette() ;
			$ctn .= "\r\n" ;
		}
		return $ctn ;
	}
}
