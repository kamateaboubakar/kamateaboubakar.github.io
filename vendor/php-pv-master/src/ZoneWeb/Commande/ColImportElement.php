<?php

namespace Pv\ZoneWeb\Commande ;

class ColImportElement extends \Pv\Objet\Objet
{
	public $EstObligatoire = 0 ;
	public $NomParametreFiltreEdit ;
	public $NomColonneLiee ;
	public $ExpressionColonneLiee ;
	public $NomParametreLie ;
	public $NomsParametresAcceptes = array() ;
	public $ValeurParDefaut ;
	public function ObtientValeur($valeur)
	{
		return $valeur ;
	}
}