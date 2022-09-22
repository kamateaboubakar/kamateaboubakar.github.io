<?php

namespace Pv\ZoneWeb\ActionCommande ;

class TailleImageGd extends \Pv\ZoneWeb\ActionCommande\ActionCommande
{
	public $DieSiNonDispo = 0 ;
	public $TaillesFiltre = array() ;
	protected $RessourceSupport = false ;
	public function CreeTailleFlt()
	{
		return new \Pv\ZoneWeb\ActionCommande\TailleFiltreImageGd() ;
	}
	public function & InsereTailleFlt($nomFiltre, $largeurMax=0, $hauteurMax=0, $operation="")
	{
		$tailleFlt = $this->CreeTailleFlt() ;
		$tailleFlt->NomFiltre = $nomFiltre ;
		$tailleFlt->LargeurMax = $largeurMax ;
		$tailleFlt->HauteurMax = $hauteurMax ;
		$tailleFlt->Operation = $operation ;
		return $this->InscritTailleFiltre($tailleFlt) ;
	}
	public function & InscritTailleFiltre(& $tailleFlt)
	{
		$this->TaillesFiltre[$tailleFlt->NomFiltre] = $tailleFlt ;
		return $tailleFlt ;
	}
	public function & InsereTailleFiltre($nomFiltre, $largeurMax=0, $hauteurMax=0, $operation="")
	{
		return $this->InsereTailleFlt($nomFiltre, $largeurMax, $hauteurMax, $operation) ;
	}
	public function Execute()
	{
		if(! function_exists("imagecreate"))
		{
			if($this->DieSiNonDispo == 1)
			{
				die("<p>La librairie GD n'est pas install&eacute;e, vous ne pouvez pas utiliser la classe ".get_class($this)."</p>") ;
			}
		}
		$nomFiltres = array_keys($this->FormulaireDonneesParent->FiltresEdition) ;
		// print_r($nomFiltres) ;
		$args = array_keys($this->TaillesFiltre) ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			$filtre = & $this->FormulaireDonneesParent->FiltresEdition[$nomFiltre] ;
			if(in_array($filtre->NomElementScript, $args) || in_array($nomFiltre, $args, true))
			{
				if(isset($this->TaillesFiltre[$filtre->NomElementScript]))
				{
					$this->AnalyseTailleFiltre($this->TaillesFiltre[$filtre->NomElementScript], $this->FormulaireDonneesParent->FiltresEdition[$nomFiltre]) ;
				}
				else
				{
					$this->AnalyseTailleFiltre($this->TaillesFiltre[$nomFiltre], $this->FormulaireDonneesParent->FiltresEdition[$nomFiltre]) ;
				}
			}
		}
	}
	protected function AnalyseTailleFiltre(& $tailleFlt, & $filtre)
	{
		$cheminImage = $filtre->Lie() ;
		if($cheminImage == '' || (! file_exists($cheminImage) || is_dir($cheminImage)))
		{
			// $GLOBALS['\Pv\Common\GD\Manipulator']->CopyFile($tailleFlt->CheminEchec, $cheminImage)
			return false ;
		}
		if(filesize($cheminImage) > 1024 * 1024 * 1024)
		{
			return false ;
		}
		$pathInfo = pathinfo($cheminImage) ;
		$cheminTemp = $pathInfo["dirname"]. DIRECTORY_SEPARATOR ."~".$pathInfo["basename"] ;
		$GLOBALS['\Pv\Common\GD\Manipulator']->CopyAdjustedFile($cheminImage, $cheminTemp, $tailleFlt->LargeurMax, $tailleFlt->HauteurMax) ;
		if(file_exists($cheminTemp))
		{
			rename($cheminTemp, $cheminImage) ;
		}
	}
}