<?php

namespace Pv\ApiRestful\Route ;

class Route extends \Pv\Objet\Objet
{
	public $MethodeHttp ;
	public $NomElementApi ;
	public $CheminRouteApi ;
	public $ApiParent ;
	public $ApplicationParent ;
	public $NecessiteMembreConnecte = 0 ;
	public $Privileges = array() ;
	public $PrivilegesStricts = 0 ;
	public $ArreterExecution = false ;
	public function ApprouveAppel()
	{
		return 1 ;
	}
	public function PossedeMembreConnecte()
	{
		return $this->ApiParent->PossedeMembreConnecte() ;
	}
	public function PossedePrivilege($privilege)
	{
		return $this->ApiParent->PossedePrivilege($privilege) ;
	}
	public function PossedePrivileges($privileges)
	{
		return $this->ApiParent->PossedePrivileges($privileges) ;
	}
	public function IdMembreConnecte()
	{
		return $this->ApiParent->IdMembreConnecte() ;
	}
	public function LoginMembreConnecte()
	{
		return $this->ApiParent->LoginMembreConnecte() ;
	}
	public function EstAccessible()
	{
		return (($this->NecessiteMembreConnecte == 0 || $this->PossedeMembreConnecte()) && (count($this->Privileges) == 0 || $this->ApiParent->PossedePrivileges($this->Privileges, $this->PrivilegesStricts))) ;
	}
	public function AdopteApi($nom, $cheminRoute, & $api)
	{
		$this->NomElementApi = $nom ;
		if($this->CheminRouteApi == '')
		{
			$this->CheminRouteApi = $nom ;
		}
		$this->CheminRouteApi = $cheminRoute ;
		$this->ApiParent = & $api ;
		$this->ApplicationParent = & $api->ApplicationParent ;
	}
	public function SuccesReponse()
	{
		return $this->ApiParent->Reponse->EstSucces() ;
	}
	public function EchecReponse()
	{
		return $this->ApiParent->Reponse->EstEchec() ;
	}
	public function CreeBDPrinc()
	{
		return $this->ApiParent->CreeBDPrinc() ;
	}
	public function CreeDBPrinc()
	{
		return $this->ApiParent->CreeDBPrinc() ;
	}
	public function CreeFournisseurDonneesPrinc()
	{
		return $this->ApiParent->CreeFournisseurDonneesPrinc() ;
	}
	public function CreeFournDonneesPrinc()
	{
		return $this->ApiParent->CreeFournisseurDonneesPrinc() ;
	}
	public function CreeFournPrinc()
	{
		return $this->ApplicationParent->CreeFournisseurDonneesPrinc() ;
	}
	public function Execute()
	{
		$this->Requete = & $this->ApiParent->Requete ;
		$this->Reponse = & $this->ApiParent->Reponse ;
		$this->ContenuReponse = & $this->ApiParent->Reponse->Contenu ;
		$this->ArreterExecution = false ;
		$this->Reponse->ConfirmeSucces() ;
		$this->PrepareExecution() ;
		if($this->ArreterExecution)
		{
			return ;
		}
		$this->ExecuteInstructions() ;
		if($this->ArreterExecution)
		{
			return ;
		}
		$this->TermineExecution() ;
	}
	protected function PrepareExecution()
	{
	}
	protected function ExecuteInstructions()
	{
	}
	protected function TermineExecution()
	{
	}
	public function ConfirmeContenu($data)
	{
		$this->ConfirmeData($data) ;
	}
	public function RenseigneReponse($statusCode, $message='', $data=null)
	{
		$this->ApiParent->Reponse->EnteteStatusCode = $statusCode ;
		if($statusCode == 200 || $statusCode == 201 || $statusCode == 202 || $statusCode == 203)
		{
			$this->ApiParent->Reponse->Contenu->data = $data ;
		}
		else
		{
			$this->ApiParent->Reponse->Contenu->message = $message ;
		}
	}
	public function ConfirmeData($data)
	{
		$this->ApiParent->Reponse->EnteteStatusCode = 200 ;
		$this->ApiParent->Reponse->Contenu->data = $data ;
	}
	public function ConfirmeNonTrouve($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeNonTrouve($message) ;
	}
	public function ConfirmeInvalide($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeInvalide($message) ;
	}
	public function ConfirmeEchecAuth($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeEchecAuth($message) ;
	}
	public function ConfirmeNonAutorise($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeNonAutorise($message) ;
	}
	public function ConfirmeErreur($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeInvalide($message) ;
	}
	public function RenseigneErreur($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeInvalide($message) ;
	}
	public function ConfirmeException($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeErreurInterne($message) ;
	}
	public function RenseigneException($message='')
	{
		return $this->ApiParent->Reponse->ConfirmeErreurInterne($message) ;
	}
	public function ConfirmeSucces()
	{
		return $this->ApiParent->Reponse->ConfirmeSucces() ;
	}
	public function EstSucces()
	{
		return $this->ApiParent->Reponse->EstSucces() ;
	}
	public function EstEchec()
	{
		return ! $this->EstSucces() ;
	}
}