<?php

namespace Pv\ZoneWeb\FormulaireDonnees ;

class Lien
{
	public $Libelle ;
	public $Url ;
	public $FenetreCible ;
	public $ClassesCSS = array() ;
	public function __construct($url, $libelle)
	{
		$this->Url = $url ;
		$this->Libelle = $libelle ;
	}
	public function RenduDispositif(& $form, $index)
	{
		return '<a'.((count($this->ClassesCSS) > 0) ? ' class="'.join(' ', $this->ClassesCSS).'"' : '').' href="'.htmlspecialchars($this->Url).'"'.(($this->FenetreCible != '') ? ' target="'.$this->FenetreCible.'"' : '').'>'.$this->Libelle.'</a>' ;
	}
}