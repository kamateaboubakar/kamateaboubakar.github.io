<?php

namespace Rpa2p\ZonePrinc\Script\Environnement ;

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
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_environnement" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_environnement" ;
		$this->FltId = $this->FormPrinc->InsereFltSelectHttpGet("id", "id=<self>") ;
		$this->FltTitre = $this->FormPrinc->InsereFltEditHttpPost("titre", "titre") ;
		$this->FltTitre->Libelle = "Titre" ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("en_production", "en_production") ;
		$flt->Libelle = "En production" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool) ;
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
			return true ;
		}
	}
	public function ValideCritere(& $critere)
	{
		if($this->CritrPrinc->IDInstanceCalc == $critere->IDInstanceCalc)
		{
			$bd = $this->CreeBdPrinc() ;
			$lgnSimil = $bd->FetchSqlRow(
				'select id from rpapp_environnement where id<>:0 and (upper(titre)=upper(:1))',
				array(
					(($this->FormPrinc->InclureElementEnCours) ? $this->FltId->Lie() : 0),
					$this->FltTitre->Lie()
				)
			) ;
			if(! is_array($lgnSimil))
			{
				$critere->MessageErreur = "Exception SQL env. Similaire : ".$bd->ConnectionException ;
			}
			elseif(count($lgnSimil) > 0)
			{
				$critere->MessageErreur = "Un environnement similaire existe deja. Veuillez changer le titre" ;
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
