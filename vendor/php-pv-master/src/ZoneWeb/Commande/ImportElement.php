<?php

namespace Pv\ZoneWeb\Commande ;

class ImportElement extends \Pv\ZoneWeb\Commande\FormulaireDonnees
{
	public $FormatFichier ;
	public $NomParametreFiltreEdit ;
	public $MessageErreurFiltreNonDefini = "Le champ fichier n'est pas defini" ;
	public $MessageErreurMauvaiseExtension = "Extension de fichier non prise en charge" ;
	public $MessageErreurAucuneColonne = "Aucune colonne n'est dÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©finie" ;
	protected $ColonnesSelection = array() ;
	protected $ColonnesEdition = array() ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->FormatFichier = new PvFmtCsvCommandeImportElement() ;
	}
	protected function ExecuteInstructions()
	{
		$filtreNonRenseigne = 0 ;
		$filtreEdit = null ;
		if($this->NomParametreFiltreEdit == '')
		{
			$filtreNonRenseigne = 1 ;
		}
		else
		{
			foreach($this->FiltresEdition as $i => $filtreTemp)
			{
				if($filtreTemp->NomParametreLie == $this->NomParametreFiltreEdit)
				{
					$filtreEdit = & $filtreTemp ;
					break ;
				}
			}
		}
		if($this->EstNul($filtreEdit))
		{
			$this->RenseigneErreur($this->MessageErreurFiltreNonDefini) ;
			return ;
		}
		$this->FormulaireDonneesParent->LieTousLesFiltres() ;
		$cheminFichier = $filtreEdit->Lie() ;
		if($cheminFichier == '')
		{
			$this->RenseigneErreur($this->MessageErreurMauvaiseExtension) ;
			return ;
		}
		else
		{
			$infosFichier = pathinfo($cheminFichier) ;
			if(! in_array(strtolower($infosFichier["extension"]), $this->FormatFichier->Extensions))
			{
				$this->RenseigneErreur($this->MessageErreurMauvaiseExtension) ;
				return ;
			}
		}
		if($this->FormatFichier->Ouvre($cheminFichier))
		{
			$colonnes = $this->ColonnesSelection ;
			array_splice($colonnes, count($colonnes), 0, $this->ColonnesEdition) ;
			$enteteBrute = $this->FormatFichier->LitEntete() ;
			$entete = array() ;
			foreach($enteteBrute as $i => $nomCol)
			{
				$nomCol = strtolower(trim($nomCol)) ;
				if($nomCol == '')
				{
					continue ;
				}
				foreach($colonnes as $j => $colonne)
				{
					$nomsParamsAccept = array_map('strtolower', $this->NomsParametresAcceptes) ;
					if($nomCol == strtolower($colonne->NomParametreLie) || in_array($nomCol, $nomsParamsAccept))
					{
						$entete[$i] = $j ;
					}
				}
			}
			if(count($entete) == 0)
			{
				$this->RenseigneErreur($this->MessageErreurAucuneColonne) ;
			}
			else
			{
				while(($ligneBrute = $this->FormatFichier->LitLigne()) !== false)
				{
					$ligne = $this->CorrigeLigne($ligneBrute, $entete) ;
					$this->TraiteLigne($ligne) ;
				}
			}
			$this->FormatFichier->Ferme() ;
		}
	}
	protected function TraiteLigne($ligne)
	{
		$exprSelection = '' ;
		$fourn = & $this->FormulaireDonneesParent->FournisseurDonnees ;
		if(count($this->ColonnesSelection) > 0)
		{
			foreach($this->ColonnesSelection as $i => $colonne)
			{
				if($colonne->ExpressionDonnees == '')
				{
					continue ;
				}
				if($exprSelection != '')
				{
					$exprSelection .= ' and ' ;
				}
				$exprSelection = str_ireplace(array('<self>', '${luimeme}', '${this}'), $fourn->BaseDonnees->ParamPrefix.$colonne->IDInstanceCalc, $colonne->ExpressionDonnees) ;
			}
		}
		if($exprSelection != '')
		{
			
		}
	}
	protected function CorrigeLigne($ligneBrute, & $entete)
	{
		$ligne = array() ;
		foreach($entete as $i => $indexCol)
		{
			$colonne = & $this->Colonnes[$indexCol] ;
			if(! isset($ligneBrute[$i]))
			{
				$ligne = $colonne->ValeurParDefaut ;
			}
			else
			{
				$ligne[$indexCol] = $colonne->ObtientValeur($ligneBrute[$i]) ;
			}
		}
		return $ligne ;
	}
}