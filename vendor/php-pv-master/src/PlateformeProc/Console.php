<?php

namespace Pv\PlateformeProc ;

class Console extends PlateformeProc
{
	public function ObtientNomOS()
	{
		return (PHP_OS == "WINNT" || PHP_OS == "WIN32") ? 'Windows' : 'Linux' ;
	}
	public function EstDisponible()
	{
		return php_sapi_name() == 'cli' ? 1 : 0 ;
	}
	public function RecupArgs()
	{
		$args = array() ;
		if(! isset($_SERVER["argv"]) || ! is_array($_SERVER["argv"]))
		{
			return $args ;
		}
		for($i=1; $i<count($_SERVER["argv"]); $i++)
		{
			$partsArg = explode("=", $_SERVER["argv"][$i], 2) ;
			$partsArg[0] = preg_replace('/^\-+/', '', $partsArg[0]) ;
			if(! isset($partsArg[1]))
			{
				$partsArg[1] = null ;
			}
			$args[$partsArg[0]] = $partsArg[1] ;
		}
		return $args ;
	}
	protected function ObtientOS()
	{
		return \Pv\Application\Application::ObtientOS() ;
	}
	public function ObtientChemProcProg(& $prog, $inclureArgs=1)
	{
		$cmd = realpath(dirname(__FILE__).'/../../'.$prog->CheminFichierRelatif) ;
		if($cmd === false)
		{
			return "" ;
		}
		if($inclureArgs == 1)
		{
			$cmd .= \Pv\Application\Application::EncodeArgsShell($prog->ArgsParDefaut) ;
		}
		$cmd = $execPath.' '.$cmd ;
		return $cmd ;
	}
	public function ObtientCmdExecProg(& $prog)
	{
		$os = $this->ObtientOS() ;
		$execPath = \Pv\Application\Application::ObtientCheminPHP() ;
		$cmd = realpath(dirname(__FILE__).'/../../'.$prog->CheminFichierRelatif) ;
		$chemJournal = '' ;
		if($this->SortieDansFichier == 1)
		{
			$chemJournal = dirname($cmd).'/'.$prog->IDInstanceCalc.'.log' ;
		}
		if($cmd === false)
		{
			return "" ;
		}
		$cmd .= \Pv\Application\Application::EncodeArgsShell($prog->ArgsParDefaut) ;
		$cmd = $execPath.' '.$cmd ;
		if($os == 'Linux')
		{
			$cmd = $cmd.' >'.(($prog->SortieDansFichier == 1) ? $chemJournal : '/dev/null').' 2>&1 &' ;
		}
		else
		{
			$cmd = 'start /b '.$cmd.(($prog->SortieDansFichier == 1) ? ' >'.$chemJournal.' 2>&1' : '').'' ;
		}
		return $cmd ;
	}
	public function LanceProcessusProg(& $prog)
	{
		$os = $this->ObtientOS() ;
		$cmd = $this->ObtientCmdExecProg($prog) ;
		if($cmd == '')
		{
			return false ;
		}
		// echo $cmd."\n" ;
		if($os == 'Linux')
		{
			return pclose(popen($cmd, 'r')) ;
		}
		else
		{
			$fluxProc = popen($cmd, 'r') ;
			register_shutdown_function(array(& $this, 'AnnuleFluxProc'), array(& $fluxProc)) ;
			return 1 ;
		}
	}
	public function TermineProcessusProg(& $prog)
	{
		$os = $this->ObtientNomOS() ;
	}
	public function AnnuleFluxProc($fluxProc)
	{
		if(is_resource($fluxProc))
		{
			pclose($fluxProc) ;
		}
	}
}