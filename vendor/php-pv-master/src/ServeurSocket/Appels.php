<?php

namespace Pv\ServeurSocket ;

class Appels extends \Pv\ServeurSocket\ServeurSocket
{
	public $Methodes = array() ;
	public $NomMethodeTest = 'test' ;
	public $NomMethodeVerif = 'verifie' ;
	public $NomMethodeNonTrouve = 'non_trouve' ;
	public $MethodeTest ;
	public $MethodeVerif ;
	public $MethodeNonTrouve ;
	public function CreeEnvoi($nom, $args=array())
	{
		$envoi = new \Pv\ServeurSocket\EnvoiAppel() ;
		$envoi->nom = $nom ;
		$envoi->args = $args ;
		return $envoi ;
	}
	public function AppelleMethode($nom, $args=array())
	{
		$envoi = $this->CreeEnvoi($nom, $args) ;
		$retour = $this->EnvoieDemande($envoi) ;
		return $retour ;
	}
	public function Test()
	{
		return $this->AppelleMethode($this->NomMethodeTest, array()) ;
	}
	public function Verifie()
	{
		$retour = $this->AppelleMethode($this->NomMethodeVerif, array()) ;
		// print 'Retour : '.print_r($retour, true)."\n" ;
		// $methodes = $this->ObtientMethodes() ;
		if(! is_object($retour))
			return false ;
		return $retour->succes() ;
	}
	protected function CreeMethodeTest()
	{
		return new \Pv\ServeurSocket\Methode\Test() ;
	}
	protected function CreeMethodeVerif()
	{
		return new \Pv\ServeurSocket\Methode\Verif() ;
	}
	protected function CreeMethodeNonTrouve()
	{
		return new \Pv\ServeurSocket\Methode\NonTrouve() ;
	}
	public function & InsereMethode($nom, $methode)
	{
		$this->InscritMethode($nom, $methode) ;
		return $methode ;
	}
	public function InscritMethode($nom, & $methode)
	{
		$this->Methodes[$nom] = & $methode ;
	}
	protected function ObtientMethodes()
	{
		$methodes = array() ;
		foreach($this->Methodes as $nom => $methode)
		{
			$methodes[$nom] = & $this->Methodes[$nom] ;
		}
		$methodes[$this->NomMethodeNonTrouve] = $this->CreeMethodeNonTrouve() ;
		$methodes[$this->NomMethodeTest] = $this->CreeMethodeTest() ;
		$methodes[$this->NomMethodeVerif] = $this->CreeMethodeVerif() ;
		return $methodes ;
	}
	protected function RepondDemande($contenu)
	{
		$nomMethode = null ;
		$methodes = $this->ObtientMethodes() ;
		if(! is_object($contenu))
		{
			$nomMethode = $this->NomMethodeNonTrouve ;
		}
		else
		{
			if(isset($contenu->nom) && isset($methodes[$contenu->nom]))
			{
				$nomMethode = $contenu->nom ;
			}
			else
			{
				$nomMethode = $this->NomMethodeNonTrouve ;
			}
		}
		// echo "Methode : ".$nomMethode."\n" ;
		$methodes[$nomMethode]->Execute($this, $nomMethode, (isset($contenu->args)) ? $contenu->args : array()) ;
		$retour = $methodes[$nomMethode]->RetourAppel ;
		return $retour ;
	}
}