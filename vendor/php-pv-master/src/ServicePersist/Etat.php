<?php

namespace Pv\ServicePersist ;

class Etat
{
	const ETAT_NON_DEFINI = 0 ;
	const ETAT_DEMARRE = 1 ;
	const ETAT_STOPPE = 2 ;
	public $PID = 0 ;
	public $Statut = 0 ;
	public $CompteSysteme ;
	public $TimestmpCapt = 0 ;
	public $TimestmpDebutSession = 0 ;
	public $TimestmpFinSession = 0 ;
	public function EstDefini()
	{
		return $this->Statut != \Pv\ServicePersist\Etat::ETAT_NON_DEFINI ;
	}
}