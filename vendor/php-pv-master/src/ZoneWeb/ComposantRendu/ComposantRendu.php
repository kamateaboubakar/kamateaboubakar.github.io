<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class ComposantRendu extends \Pv\ZoneWeb\ElementRendu\ElementRendu
{
	public $ContenuAvantRendu = "" ;
	public $ContenuApresRendu = "" ;
	public $FiltreParent = null ;
	public $ZoneParent = null ;
	public $ScriptParent = null ;
	public $ComposantRenduParent = null ;
	public $ApplicationParent = null ;
	public $NomElementScript = "" ;
	public $NomElementZone = "" ;
	public $FournisseurDonnees = null ;
	public $TypeComposant = "base" ;
	public $FonctionComposant = "base" ;
	public $SignatureComposant = "" ;
	public $Visible = 1 ;
	public $Bloque = 0 ;
	public $NomClasseFournisseurDonnees = "\Pv\FournisseurDonnees\Sql" ;
	protected function RenduBloque()
	{
		$this->Bloque = 0 ;
		$ctn = null ;
		if(! $this->EstBienRefere())
		{
			$this->Bloque = 1 ;
			return $this->RenduMalRefere() ;
		}
		if(! $this->EstAccessible())
		{
			$this->Bloque = 1 ;
			return $this->RenduInaccessible() ;
		}
		return $ctn ;
	}
	protected function DefinitRenduBloque($msg)
	{
		$this->Bloque = 1 ;
		return $msg ;
	}
	protected function RenduException($exception)
	{
		return $this->ZoneParent->RenduException($exception) ;
	}
	protected function AfficheExceptionFournisseurDonnees()
	{
		echo $this->RenduExceptionFournisseurDonnees() ;
	}
	protected function RenduExceptionFournisseurDonnees()
	{
		if($this->EstNul($this->FournisseurDonnees))
		{
			return "" ;
		}
		// print_r($this->FournisseurDonnees) ;
		if($this->FournisseurDonnees->ExceptionTrouvee())
		{
			return $this->RenduException($this->FournisseurDonnees->DerniereException) ;
		}
		return "" ;
	}
	public function & InsereActionAvantRendu($nomAction, $action)
	{
		$this->InscritActionAvantRendu($nomAction, $action) ;
		return $action ;
	}
	public function InscritActionAvantRendu($nomAction, & $action)
	{
		$this->ZoneParent->ActionsAvantRendu[$nomAction] = & $action ;
		$action->AdopteComposantRendu($nomAction, $this) ;
	}
	public function InscritActionApresRendu($nomAction, & $action)
	{
		$this->ZoneParent->ActionsApresRendu[$nomAction] = & $action ;
		$action->AdopteComposantRendu($nomAction, $this) ;
	}
	public function AdopteComposantRendu($nom, & $comp)
	{
		$this->NomElementComposantRendu = $nom ;
		$this->ComposantRenduParent = & $null ;
		$this->AdopteScript($comp->NomElementScript.'_'.$nom, $comp->ScriptParent) ;
	}
	public function AdopteFiltre($nom, & $filtre)
	{
		$this->NomElementFiltre = $nom ;
		$this->FiltreParent = & $filtre ;
		$this->AdopteScript($filtre->NomElementScript.'_'.$nom, $filtre->ScriptParent) ;
	}
	public function AdopteScript($nom, & $script)
	{
		$this->NomElementScript = $nom ;
		$this->ScriptParent = & $script ;
		$this->AdopteZone($script->NomElementZone.'_'.$nom, $script->ZoneParent) ;
	}
	public function AdopteZone($nom, & $zone)
	{
		$this->NomElementZone = $nom ;
		$this->ZoneParent = & $zone ;
		$this->ApplicationParent = & $zone->ApplicationParent ;
	}
	public function PrepareZone()
	{
	}
	public function PrepareRendu()
	{
	}
	public function RenduDispositif()
	{
		if($this->Visible == 0)
		{
			return '' ;
		}
		$ctn = $this->RenduBloque() ;
		if($this->Bloque == 1)
		{
			return $ctn ;
		}
		if($this->MaxRendusAtteint())
		{
			return $this->MessageMaxRendusAtteint ;
		}
		$this->TraduitMessages() ;
		$this->InsereInfoRenduEnCours() ;
		$ctn .= $this->RenduDispositifBrut() ;
		$this->RetireInfoRenduEnCours() ;
		return $ctn ;
	}
	protected function TraduitMessages()
	{
	}
	protected function RenduDispositifBrut()
	{
		return "" ;
	}
	protected function RenduLienJs($url)
	{
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritLienJs($url) ;
		}
		return '<script type="text/javascript" src="'.htmlspecialchars($url).'"></script>' ;
	}
	protected function RenduLienJsCmpIE($url, $versionMin=9)
	{
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritLienJsCmpIE($url, $versionMin) ;
		}
		return '<script type="text/javascript" src="'.htmlspecialchars($url).'"></script>' ;
	}
	protected function RenduLienCSS($url)
	{
		/*
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritLienCSS($url) ;
		}
		*/
		return '<link rel="stylesheet" type="text/css" href="'.htmlspecialchars($url).'" />' ;
	}
	protected function RenduContenuJs($ctn)
	{
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritContenuJs($ctn) ;
		}
		return '<script type="text/javascript">
'.$ctn.'
</script>' ;
	}
	protected function RenduContenuJsCmpIE($ctn, $versionMin=9)
	{
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritContenuJsCmpIE($ctn, $versionMin) ;
		}
		return '<script type="text/javascript">
'.$ctn.'
</script>' ;
	}
	protected function RenduContenuCSS($ctn)
	{
		if($this->EstPasNul($this->ZoneParent) && $this->ZoneParent->InclureCtnJsEntete == 0)
		{
			return $this->ZoneParent->InscritContenuCSS($ctn) ;
		}
		return '<style type="text/css">
'.$ctn.'
</style>' ;
	}
	public function RenduEtiquette()
	{
	}
}
