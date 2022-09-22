<?php

namespace Pv\PlateformeProc ;

class PlateformeProc
{
	public function Http()
	{
		return new http() ;
	}
	public function Console()
	{
		return new http() ;
	}
	public function EstDisponible()
	{
		return 0 ;
	}
	public function RecupArgs()
	{
		return array() ;
	}
	public function LanceProcessusProg(& $prog)
	{
	}
	public function TermineProcessusProg(& $prog)
	{
	}
}