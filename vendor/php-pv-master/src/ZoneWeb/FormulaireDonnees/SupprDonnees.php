<?php

namespace Pv\ZoneWeb\FormulaireDonnees ;

class SupprDonnees extends \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees
{
	public $NomClasseCommandeExecuter = "\Pv\ZoneWeb\Commande\SupprElement" ;
	public $InclureElementEnCours = 1 ;
	public $InclureTotalElements = 1 ;
	public $Editable = 0 ;
}