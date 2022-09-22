<?php

namespace Pv\ZoneWeb\Commande ;

class Executer extends \Pv\ZoneWeb\Commande\FormulaireDonnees
{
	public $CacherFormulaireFiltresSiSucces = 0 ;
	public $AnnuleLiaisonParametresSiSucces = 0 ;
	public function AdopteFormulaireDonnees($nom, & $formulaireDonnees)
	{
		parent::AdopteFormulaireDonnees($nom, $formulaireDonnees) ;
		if($formulaireDonnees->Editable == 1)
		{
			$this->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideRegexpForm()) ;
		}
	}
	public function Execute()
	{
		parent::Execute() ;
		if($this->StatutExecution == 1)
		{
			if($this->CacherFormulaireFiltresSiSucces)
			{
				$this->FormulaireDonneesParent->CacherFormulaireFiltres = 1 ;
				$this->FormulaireDonneesParent->InclureElementEnCours = 0 ;
				$this->FormulaireDonneesParent->InclureTotalElements = 0 ;
			}
			elseif($this->AnnuleLiaisonParametresSiSucces)
			{
				$this->FormulaireDonneesParent->AnnuleLiaisonParametres() ;
			}
		}
	}
}