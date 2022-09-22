<?php

namespace Pv\ZoneWeb\ActionCommande ;

class EditElementDonnees extends \Pv\ZoneWeb\ActionCommande\ActionCommande
{
	public $TableEdition = '' ;
	public $ModeEdition = 0 ;
	public function Execute()
	{
		if($this->TableEdition == "")
		{
			$this->TableEdition = $this->FormulaireDonneesParent->FournisseurDonnees->TableEdition ;
		}
		if($this->TableEdition == "" || $this->FormulaireDonneesParent->EstNul($this->FormulaireDonneesParent->FournisseurDonnees))
		{
			return ;
		}
		$ancTableEdition = $this->FormulaireDonneesParent->FournisseurDonnees->TableEdition ;
		$this->FormulaireDonneesParent->FournisseurDonnees->TableEdition = $this->TableEdition ;
		$succes = 0 ;
		switch($this->ModeEdition)
		{
			case \Pv\ZoneWeb\FormulaireDonnees\ModeEditionElement::Ajout :
			{
				if(count($this->FiltresCibles) > 0)
				{
					$succes = $this->FormulaireDonneesParent->FournisseurDonnees->AjoutElement($this->FiltresCibles) ;
				}
			}
			break ;
			case \Pv\ZoneWeb\FormulaireDonnees\ModeEditionElement::Modif :
			{
				if(count($this->FiltresCibles) > 0)
				{
					$succes = $this->FormulaireDonneesParent->FournisseurDonnees->ModifElement($this->FormulaireDonneesParent->ObtientFiltresSelection(), $this->FiltresCibles) ;
				}
			}
			break ;
			case \Pv\ZoneWeb\FormulaireDonnees\ModeEditionElement::Suppr :
			{
				$succes = $this->FormulaireDonneesParent->FournisseurDonnees->SupprElement($this->FormulaireDonneesParent->ObtientFiltresSelection()) ;
			}
			break ;
			default :
			{
				$this->FormulaireDonneesParent->RenseigneErreur("Le mode d'ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©dition de la commande est inconnue") ;
			}
			break ;
		}
		if(! $succes && $this->FormulaireDonneesParent->FournisseurDonnees->BaseDonnees->ConnectionException != "")
		{
			$this->FormulaireDonneesParent->AfficheExceptionFournisseurDonnees() ;
		}
		$this->FormulaireDonneesParent->FournisseurDonnees->TableEdition = $ancTableEdition ;
	}
}