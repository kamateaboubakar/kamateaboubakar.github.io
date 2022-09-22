<?php

namespace Pv\ServeurSocket\Methode ;

class MethodeSocket
{
	public $ArgsParDefaut = array() ;
	public $Args ;
	public $ArgsBruts ;
	protected $Serveur ;
	protected $ServeurIndef ;
	public $RetourAppel ;
	public $NomAppel = "" ;
	public function __construct()
	{
		$this->InitConfig() ;
	}
	protected function InitConfig()
	{
		$this->ServeurIndef = new \Pv\ServeurSocket\Appels() ;
		$this->RetourAppel = $this->CreeRetourAppel() ;
	}
	protected function CreeRetourAppel()
	{
		return new \Pv\ServeurSocket\RetourAppel() ;
	}
	protected function PrepareExecution(& $serveur, $nom, $args=array())
	{
		$this->RetourAppel = $this->CreeRetourAppel() ;
		$this->NomAppel = $nom ;
		$this->ArgsBruts = $args ;
		$this->Args = $this->ArgsParDefaut ;
		foreach($this->Args as $nom => $arg)
		{
			if(is_object($args) && isset($args->$nom))
			{
				$this->Args[$nom] = $args->$nom ;
			}
			elseif(is_array($args) && isset($args[$nom]))
			{
				$this->Args[$nom] = $args[$nom] ;
			}
		}
		// print_r($args) ;
		$this->Serveur = & $serveur ;
	}
	protected function ExecuteInstructions()
	{
	}
	protected function TermineExecution()
	{
		$this->Serveur = & $this->ServeurIndef ;
	}
	public function Execute(& $serveur, $nom, $args=array())
	{
		$this->PrepareExecution($serveur, $nom, $args) ;
		$this->ExecuteInstructions() ;
		$this->TermineExecution() ;
	}
	protected function EstErreur()
	{
		return $this->RetourAppel->erreurTrouvee() ;
	}
	protected function ErreurTrouvee()
	{
		return $this->RetourAppel->erreurTrouvee() ;
	}
	protected function EstSucces()
	{
		return $this->RetourAppel->succes() ;
	}
	protected function ConfirmeSucces($msg, $resultat=null)
	{
		$this->RetourAppel->codeErreur = 0 ;
		$this->RetourAppel->message = $msg ;
		$this->RetourAppel->resultat = $resultat ;
	}
	protected function SignaleErreur($code, $msg, $resultat=null)
	{
		$this->RetourAppel->codeErreur = $code ;
		$this->RetourAppel->message = $msg ;
		$this->RetourAppel->resultat = $resultat ;
	}
	protected function RenseigneErreur($code, $msg, $resultat=null)
	{
		return $this->SignaleErreur($code, $msg, $resultat) ;
	}
}