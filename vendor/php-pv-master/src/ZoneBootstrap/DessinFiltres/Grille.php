<?php

namespace Pv\ZoneBootstrap\DessinFiltres ;

class DessinFiltres extends \Pv\ZoneWeb\DessinFiltres\Html
{
	public $ClassesBsEditeur = array(
	"form-control" => array(
			"input_text_html", "input_password_html", "input_file_html"
		),
	"form-select" => array(
			"select_html"
		),
	) ;
	public $ColXs = "" ;
	public $ColSm = "" ;
	public $ColMd = "" ;
	public $ColLd = "" ;
	public $EditeurSurligne = 0 ;
	public $ColXsLibelle = 4 ;
	public $ClsBstLibelle ;
	public $ClsBstLigne = "mb-2" ;
	public $AlignLibelle ;
	public $ClsBstEditeur ;
	public $AlignEditeur ;
	public $MaxFiltresParLigne = 1 ;
	protected function ObtientColXs($maxFiltres)
	{
		$val = 0 ;
		if($this->ColXs != '')
		{
			$val = $this->ColXs ;
		}
		elseif($this->ColLd != '')
		{
			$val = $this->ColLd ;
		}
		elseif($this->ColMd != '')
		{
			$val = $this->ColMd ;
		}
		elseif($this->ColSm != '')
		{
			$val = $this->ColSm ;
		}
		else
		{
			$val = intval(12 / $maxFiltres) ;
		}
		return $val ;
	}
	protected function RenduFiltre(& $filtre, & $composant)
	{
		$ctn = '' ;
		if($composant->Editable)
		{
			if($filtre->EstNul($filtre->Composant))
			{
				$filtre->DeclareComposant($filtre->NomClasseComposant) ;
			}
			if($filtre->EstPasNul($filtre->Composant))
			{
				foreach($this->ClassesBsEditeur as $nomCls => $typesEditeur)
				{
					if(in_array($filtre->Composant->TypeEditeur, $typesEditeur) && ! in_array($nomCls, $filtre->Composant->ClassesCSS))
					{
						$filtre->Composant->ClassesCSS[] = $nomCls ;
					}
				}
			}
			$ctn .= $filtre->Rendu() ;
		}
		else
		{
			$ctn .= $filtre->Etiquette() ;
		}
		return $ctn ;
	}
	public function Execute(& $script, & $composant, $parametres)
	{
		if($this->EditeurSurligne == 1 && $this->InclureLibelle == 1)
		{
			return $this->RenduEditeursSurligne($script, $composant, $parametres) ;
		}
		$filtres = $composant->ExtraitFiltresDeRendu($parametres, $this->FiltresCaches) ;
		$ctn = '' ;
		if($this->MaxFiltresParLigne <= 0)
		{
			$this->MaxFiltresParLigne = 1 ;
		}
		$colXs = $this->ObtientColXs($this->MaxFiltresParLigne) ;
		$nomFiltres = array_keys($filtres) ;
		$filtreRendus = 0 ;
		$ctn .= '<div class="row">'.PHP_EOL ;
		$ctn .= '<div class="col-'.$colXs.(($this->ColSm != '') ? ' col-sm-'.$this->ColSm : '').''.(($this->ColMd != '') ? ' col-md-'.$this->ColMd : '').(($this->ColLd != '') ? ' col-ld-'.$this->ColLd : '').' ">'.PHP_EOL ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = $filtres[$nomFiltre] ;
			if($filtre->LectureSeule)
			{
				$ctn .= '<input type="hidden" id="'.htmlspecialchars($filtre->ObtientIDComposant()).'" name="'.htmlspecialchars($filtre->ObtientNomComposant()).'" value="'.htmlspecialchars($filtre->Lie()).'" />'.PHP_EOL ;
				continue ;
			}
			$ctn .= '<div class="row form-group'.(($this->ClsBstLigne != '') ? ' '.$this->ClsBstLigne : '').'">'.PHP_EOL ;
			if($this->InclureRenduLibelle)
			{
				if($this->EditeurSurligne == 0)
				{
					$ctn .= '<div class="col-12 col-sm-'.$this->ColXsLibelle.''.(($this->ClsBstLibelle == '') ? '' : ' '.$this->ClsBstLibelle).(($this->AlignLibelle == '') ? '' : ' text-'.$this->AlignLibelle).'">'.PHP_EOL ;
					$ctn .= $this->RenduLibelleFiltre($filtre).PHP_EOL ;
					$ctn .= '</div>'.PHP_EOL .'<div class="col-12 col-sm-'.(12 - $this->ColXsLibelle).''.(($this->ClsBstEditeur == '') ? '' : ' '.$this->ClsBstEditeur).(($this->AlignEditeur == '') ? '' : 'text-'.$this->AlignEditeur).'">'.PHP_EOL ;
				}
				else
				{
					$ctn .= $this->RenduLibelleFiltre($filtre).PHP_EOL ;
				}
			}
			if($this->EditeurSurligne == 0)
			{
				$ctn .= $this->RenduFiltre($filtre, $composant).PHP_EOL ;
				$ctn .= '</div>'.PHP_EOL ;
			}
			else
			{
				$ctn .= $this->RenduFiltre($filtre, $composant).PHP_EOL ;
			}
			$ctn .= '</div>'.PHP_EOL ;
			$filtreRendus++ ;
		}
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		return $ctn ;
	}
}
