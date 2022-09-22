<?php

namespace Rpa2p\ZonePrinc\Script\Job ;

use \Rpa2p\ZonePrinc\Script ;

class Edit extends Script\Script
{
	public $NomDocumentWeb = "modal" ;
	public function DetermineEnvironnement()
	{
		parent::DetermineEnvironnement() ;
		$this->DetermineFormPrinc() ;
	}
	protected function DetermineFormPrinc()
	{
		$this->FormPrinc = $this->InsereFormPrinc() ;
		$this->InitFormPrinc() ;
		$this->FormPrinc->ChargeConfig() ;
		$this->FormPrinc->CommandeAnnuler->ContenuJsSurClick = "window.top.fermeModal()" ;
		$this->FormPrinc->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_job" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_job" ;
		$this->FltId = $this->FormPrinc->InsereFltSelectHttpGet("id", "id=<self>") ;
		$this->FltRef = $this->FormPrinc->InsereFltEditHttpPost("reference_job", "reference_job") ;
		$this->FltRef->Libelle = "Reference" ;
		$this->FltNom = $this->FormPrinc->InsereFltEditHttpPost("nom", "nom") ;
		$this->FltNom->Libelle = "Nom" ;
		$this->FltIdApp = $this->FormPrinc->InsereFltEditHttpPost("id_application", "id_application") ;
		$this->FltIdApp->Libelle = "Application" ;
		$comp = $this->FltIdApp->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelect) ;
		$comp->FournisseurDonnees = $this->CreeFournPrinc() ;
		$comp->FournisseurDonnees->RequeteSelection = "rpapp_application" ;
		$comp->NomColonneValeur = "id" ;
		$comp->NomColonneLibelle = "titre" ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("description", "description") ;
		$flt->Libelle = "Description" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$comp->TotalLignes = 6 ;
		if($this->FormPrinc->InclureElementEnCours == false)
		{
			$this->FormPrinc->InsereFltEditFixe("id_membre_creation", $this->IdMembreConnecte(), "id_membre_creation") ;
			$this->FormPrinc->InsereFltEditFixe("date_creation", date("Y-m-d H:i:s"), "date_creation") ;
			$this->FormPrinc->InsereFltEditFixe("type_periode", "jamais", "type_periode") ;
		}
		$this->FormPrinc->InsereFltEditFixe("id_membre_modif", $this->IdMembreConnecte(), "id_membre_modif") ;
		$this->FormPrinc->InsereFltEditFixe("date_modif", date("Y-m-d H:i:s"), "date_modif") ;
		$this->ActCmdPrinc = $this->FormPrinc->CommandeExecuter->InsereActCmdScriptParent() ;
		$this->CritrPrinc = $this->FormPrinc->CommandeExecuter->InsereCritereScriptParent() ;
		$this->FormPrinc->DessinateurFiltresEdition = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
	}
	protected function InitFormPrinc()
	{
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= $this->FormPrinc->RenduDispositif() ;
		return $ctn ;
	}
	public function AppliqueActCmd(& $actCmd)
	{
		if($this->ActCmdPrinc->IDInstanceCalc == $actCmd->IDInstanceCalc)
		{
		}
	}
	public function ValideCritere(& $critere)
	{
		if($this->CritrPrinc->IDInstanceCalc == $critere->IDInstanceCalc)
		{
			$bd = $this->CreeBdPrinc() ;
			$lgnSimil = $bd->FetchSqlRow(
				'select id from rpapp_job where (reference_job=:0 or (id_application=:1 and nom=:2)) and id<>:3',
				array(
					$this->FltRef->Lie(),
					$this->FltIdApp->Lie(),
					$this->FltNom->Lie(),
					(($this->FormPrinc->InclureElementEnCours) ? $this->FltId->Lie() : 0)
				)
			) ;
			if(! is_array($lgnSimil))
			{
				$critere->MessageErreur = "Exception SQL Job Similaire : ".$bd->ConnectionException ;
			}
			elseif(count($lgnSimil) > 0)
			{
				$critere->MessageErreur = "Un job similaire existe deja. Veuillez changer le nom ou la reference" ;
			}
			return $critere->MessageErreur == "" ;
		}
		return true ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		if($this->FormPrinc->IDInstanceCalc == $composant->IDInstanceCalc)
		{
			return '' ;
		}
		return '' ;
	}
}
