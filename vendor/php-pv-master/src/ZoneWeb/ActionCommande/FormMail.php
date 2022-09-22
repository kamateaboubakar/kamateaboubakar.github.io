<?php

namespace Pv\ZoneWeb\ActionCommande ;

class FormMail extends \Pv\ZoneWeb\ActionCommande\EnvoiMail
{
	protected function ConstruitContenuMessage()
	{
		$form = & $this->FormulaireDonneesParent ;
		$valeurFiltres = $form->ExtraitValeursFiltres($this->FiltresCibles) ;
		$this->SujetMessage = \Pv\Misc::_parse_pattern($this->FormatSujetMessage, $valeursFiltres) ;
		$this->ContenuMessage = $form->DessinateurFiltresEdition->VersionTexte($form, $form->FiltresEdition) ;
	}
}