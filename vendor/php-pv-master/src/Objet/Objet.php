<?php

namespace Pv\Objet ;

class Objet
{
	public $ID = "" ;
	public $IDInstance = "" ;
	public $IDInstanceCalc = "" ;
	public $NomClasseInstance = "" ;
	public $IndiceInstance = 0 ;
	static $TotalInstances = 0 ;
	public $AttrsSuppl = array() ;
	public $EstNul = 0 ;
	public function ValAttrSuppl($nom, $valeurDefaut=null)
	{
		if(isset($this->AttrsSuppl[$nom]))
		{
			return $this->AttrsSuppl[$nom] ;
		}
		return $valeurDefaut ;
	}
	public function AffecteValSuppl($nom, $valeur)
	{
		$this->AttrsSuppl[$nom] = $valeur ;
	}
	public function AffecteAttrSuppl($nom, $valeur)
	{
		$this->AttrsSuppl[$nom] = $valeur ;
	}
	public function FixeAttrSuppl($nom, $valeur)
	{
		$this->AffecteAttrSuppl($nom, $valeur) ;
	}
	public function SupprAttrSuppl($nom, $valeur)
	{
		unset($this->AttrsSuppl[$nom]) ;
	}
	public function ObtientAttrSuppl($nom, $valeurDefaut=null)
	{
		return $this->ValAttrSuppl($nom, $valeurDefaut) ;
	}
	public function CreeInstanceGener()
	{
		$nomClasse = get_class($this) ;
		return new $nomClasse() ;
	}
	public function ObtientValStatique($nomPropriete, $valParDefaut=false)
	{
		return $this->ObtientValeurStatique($nomPropriete, $valParDefaut) ;
	}
	public function AffecteValStatique($nomPropriete, $valParDefaut=false)
	{
		return $this->AffecteValeurStatique($nomPropriete, $valParDefaut) ;
	}
	public function ObtientValeurStatique($nomPropriete, $valeurDefaut=false)
	{
		$valeur = $valeurDefaut ;
		$nomClasse = get_class($this) ;
		try
		{
			eval('if(isset('.$nomClasse.'::$'.$nomPropriete.'))
			{
				$valeur = '.$nomClasse.'::$'.$nomPropriete.' ;
			}') ;
		}
		catch(Exception $ex)
		{
		}
		return $valeur ;
	}
	public function AffecteValeurStatique($nomPropriete, $valeur)
	{
		$nomClasse = get_class($this) ;
		try
		{
			eval('if(isset('.$nomClasse.'::$'.$nomPropriete.'))
			{
				'.$nomClasse.'::$'.$nomPropriete.' = $valeur ;
			}') ;
		}
		catch(Exception $ex)
		{
		}
		return $valeur ;
	}
	public function __construct()
	{
		$this->InitConfigStatique() ;
		$this->InitConfig() ;
	}
	protected function InitConfigStatique()
	{
		$classe = get_class($this) ;
		eval('if(! isset('.$classe.'::$TotalInstances))
		{
			'.$classe.'::$TotalInstances = 0 ;
		}') ;
	}
	protected function InitConfig()
	{
		$this->InitConfigInstance() ;
	}
	protected function InitConfigInstance()
	{
		$totalInstances = $this->ObtientValeurStatique("TotalInstances") ;
		$totalInstances++ ;
		$infosClasse = explode('\\', get_class($this)) ;
		$this->NomClasseInstance = array_pop($infosClasse) ;
		$this->IndiceInstance = $totalInstances ;
		$this->AffecteValeurStatique("TotalInstances", $totalInstances) ;
		$this->IDInstance = $this->NomClasseInstance."_".$this->IndiceInstance ;
		$this->IDInstanceCalc = $this->IDInstance ;
	}
	public function ChargeConfig()
	{
	}
	public function EstNul($objet)
	{
		if($objet == null)
			return 1 ;
		$nomClasse = get_class($objet) ;
		$nomClasseObj = get_class($this) ;
		return (in_array($nomClasse, array('Pv\Objet\ObjetNul', "stdClass"))) ? 1 : 0 ;
		// return (get_class($objet) == "\Pv\Objet\ObjetNul" or get_class($objet) == get_class($this)) ? 1 : 0 ;
	}
	public function EstNonNul($objet)
	{
		return ($this->EstNul($objet)) ? 0 : 1 ;
	}
	public function EstPasNul($objet)
	{
		return $this->EstNonNul($objet) ;
	}
	public function ValeurNulle()
	{
		return new \Pv\Objet\ObjetNul() ;
	}
	public function & ObjetNul()
	{
		$objet = $this->ValeurNulle() ;
		return $objet ;
	}
	public function CorrigeChemin($cheminFichier)
	{
		$resultat = $cheminFichier ;
		if(DIRECTORY_SEPARATOR != "/")
			$resultat = str_replace("/", DIRECTORY_SEPARATOR, $resultat) ;
		if(DIRECTORY_SEPARATOR != "\\")
			$resultat = str_replace("\\", DIRECTORY_SEPARATOR, $resultat) ;
		return $resultat ;
	}
	public function ObtientDelaiMaxExecution()
	{
		return ini_get('max_execution_time');
	}
	public function ObtientDelaiMaxExec()
	{
		return $this->ObtientDelaiMaxExecution() ;
	}
	public function DelaiMaxExec()
	{
		return $this->ObtientDelaiMaxExecution() ;
	}
	public function ObtientValSuppl($nom, $valeurDefaut=null)
	{
		return (isset($this->AttrsSuppl[$nom])) ? $this->AttrsSuppl[$nom] : $valeurDefaut ;
	}
	public function RenduModeleEval($cheminModele)
	{
		ob_start() ;
		include $cheminModele ;
		$ctn = ob_get_clean() ;
		return $ctn ;
	}
}