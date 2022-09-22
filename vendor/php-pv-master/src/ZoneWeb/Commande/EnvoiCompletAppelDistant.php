<?php

namespace Pv\ZoneWeb\Commande ;

class EnvoiCompletAppelDistant extends \Pv\ZoneWeb\Commande\AppelDistant
{
	protected function ExtraitArgsAppelDistant()
	{
		return array(
			'filtresGlobauxSelect' => $this->FormulaireDonneesParent->ExtraitObjetColonneLiee($this->FormulaireDonneesParent->FiltresGlobauxSelection),
			'filtresLgSelect' => $this->FormulaireDonneesParent->ExtraitObjetColonneLiee($this->FormulaireDonneesParent->FiltresLigneSelection),
			'filtresEdit' => $this->FormulaireDonneesParent->ExtraitObjetColonneLiee($this->FormulaireDonneesParent->FiltresEdition),
		) ;
	}
}