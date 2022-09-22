<?php

namespace Pv\ZoneConsole ;

class ZoneConsole extends \Pv\IHM\Zone
{
	protected $ArgsExecution = array() ;
	public function NatureZone()
	{
		return "console" ;
	}
	public function Execute()
	{
		$this->DetecteArgsExecution() ;
		parent::Execute() ;
	}
	protected function DetecteArgsExecution()
	{
		$platf = new \Pv\PlateformeProc\PlateformeProcConsole() ;
		$this->ArgsExecution = $platf->RecupArgs() ;
	}
	protected function DetecteParamScriptAppele()
	{
		$this->ValeurBruteParamScriptAppele = "" ;
		$this->ValeurParamScriptAppele = $this->NomScriptParDefaut ;
		if(isset($this->ArgsExecution[$this->NomParamScriptAppele]))
		{
			$this->ValeurBruteParamScriptAppele = $this->ArgsExecution[$this->NomParamScriptAppele] ;
			$this->ValeurParamScriptAppele = $this->ValeurBruteParamScriptAppele ;
		}
	}
	public function ObtientUrl()
	{
		if($this->ApplicationParent->NomElementActif == $this->NomElementApplication)
		{
			$url = $_SERVER["argv"][0] ;
			return $url ;
		}
		else
		{
			$execPath = \Pv\Application\Application::ObtientCheminPHP() ;
			$cmd = realpath(dirname(__FILE__).'/../../../'.$this->CheminFichierRelatif) ;
			return $execPath.' '.$cmd ;
		}
	}
	public function ObtientUrlParam($params=array())
	{
		return $this->ObtientUrl()." ".\Pv\Application\Application::EncodeArgsShell($params) ;
	}
	public function ObtientUrlScript($nomScript, $params=array(), $strict=1)
	{
		if(! isset($this->Scripts[$nomScript]) && $strict == 1)
			return false ;
		$params[$this->NomParamScriptAppele] = $nomScript ;
		$url = $this->ObtientUrl()." ".\Pv\Application\Application::EncodeArgsShell($params) ;
		// echo $url ;
		return $url ;
	}
	public function InvoqueScriptSpec($nomScript, $params=array(), $valeurPost=array(), $async=1)
	{
		return \Pv\Application\Application::TelechargeShell($this->ObtientUrlScript($nomScript, $params, 0), $valeurPost, $async) ;
	}
}