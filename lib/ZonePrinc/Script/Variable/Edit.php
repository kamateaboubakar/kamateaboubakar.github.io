<?php

namespace Rpa2p\ZonePrinc\Script\Variable ;

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
		$this->FormPrinc->FournisseurDonnees->RequeteSelection = "rpapp_variable" ;
		$this->FormPrinc->FournisseurDonnees->TableEdition = "rpapp_variable" ;
		$this->FltNom = $this->FormPrinc->InsereFltSelectHttpGet("nom", "nom=<self>") ;
		$this->FltNomEdit = $this->FormPrinc->InsereFltEditHttpPost("nom_edit", "nom") ;
		$this->FltNomEdit->Libelle = "Nom" ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("valeur", "valeur") ;
		$flt->Libelle = "Valeur" ;
		$flt->AliasParametreDonnees = "CASE WHEN crypter=0 THEN AES_DECRYPT(FROM_BASE64(valeur), '".\Rpa2p\Config\Cryptage::CLE_VAR."') else '************' end" ;
		$flt->ExpressionColonneLiee = "TO_BASE64(AES_ENCRYPT(<self>, '".\Rpa2p\Config\Cryptage::CLE_VAR."'))" ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("crypter", "crypter") ;
		$flt->Libelle = "Crypter" ;
		$flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneSelectBool()) ;
		$flt = $this->FormPrinc->InsereFltEditHttpPost("description", "description") ;
		$flt->Libelle = "Description" ;
		$comp = $flt->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$comp->TotalLignes = 6 ;
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
			if($this->FormPrinc->InclureElementEnCours == false || $this->FltNom->Lie() != $this->FltNomEdit->Lie())
			{
				$lgnSimil = $bd->FetchSqlRow(
					'select nom from rpapp_variable where  (upper(nom)=upper(:0))',
					array(
						$this->FltNomEdit->Lie()
					)
				) ;
				if(! is_array($lgnSimil))
				{
					$critere->MessageErreur = "Exception SQL var. Similaire : ".$bd->ConnectionException ;
				}
				elseif(count($lgnSimil) > 0)
				{
					$critere->MessageErreur = "Une variable similaire existe deja. Veuillez changer le nom" ;
				}
			}
			return ($critere->MessageErreur == "") ;
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
