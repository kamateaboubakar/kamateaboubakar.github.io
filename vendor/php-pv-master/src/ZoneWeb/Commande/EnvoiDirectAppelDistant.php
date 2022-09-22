<?php

namespace Pv\ZoneWeb\Commande ;

class EnvoiDirectAppelDistant extends \Pv\ZoneWeb\Commande\AppelDistant
{
	protected function ExtraitArgsAppelDistant()
	{
		return $this->FormulaireDonneesParent->ExtraitObjetColonneLiee($this->FormulaireDonneesParent->FiltresEdition) ;
	}
}