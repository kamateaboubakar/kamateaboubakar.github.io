<?php

namespace Pv\ZoneWeb\TableauDonnees\Commande ;

class Commande extends \Pv\ZoneWeb\Commande\Commande
{
	public $NecessiteTableauDonnees = 1 ;
	public function AdopteTableauDonnees($nom, & $tableauDonnees)
	{
		parent::AdopteTableauDonnees($nom, $tableauDonnees) ;
		//$this->InsereNouvCritere(new \Pv\ZoneWeb\Critere\ValideRegexpTabl()) ;
	}
}