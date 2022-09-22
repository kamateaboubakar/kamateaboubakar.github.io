<?php

namespace Pv\ZoneWeb\TableauDonnees\Commande ;

class ExportVers extends \Pv\ZoneWeb\TableauDonnees\Commande\Export
{
	public $SeparateurColonnes = ";" ;
	public $SeparateurLignes = "\r\n" ;
	public $ExprAvantValeur = "" ;
	public $ExprApresValeur = "" ;
	public $TotalLignes = 0 ;
	public $NomFichier = "resultat.txt" ;
	protected function EnvoieContenu()
	{
		$defCols = $this->TableauDonneesParent->DefinitionsColonnesExport() ;
		$requete = $this->TableauDonneesParent->FournisseurDonnees->OuvreRequeteSelectElements($this->TableauDonneesParent->FiltresSelection, $defCols) ;
		$this->EnvoieEntete() ;
		$this->TotalLignes = 0 ;
		while($ligne = $this->TableauDonneesParent->FournisseurDonnees->LitRequete($requete))
		{
			$valeurs = $this->TableauDonneesParent->ExtraitValeursExport($ligne, $this) ;
			$valeurs = $this->TableauDonneesParent->SourceValeursSuppl->Applique($this->TableauDonneesParent, $valeurs) ;
			$this->EnvoieValeurs($valeurs) ;
			$this->TotalLignes++ ;
		}
		$this->TableauDonneesParent->FournisseurDonnees->FermeRequete($requete) ;
		$this->EnvoiePied() ;
	}
	protected function EnvoieEntete()
	{
		if(! $this->InclureEntete)
		{
			return ;
		}
		$libelles = $this->TableauDonneesParent->ExtraitLibellesExport() ;
		foreach($libelles as $i => $libelle)
		{
			if($i > 0)
			{
				echo $this->SeparateurColonnes ;
			}
			echo \Pv\Misc::clean_special_chars($libelle) ;
		}
		echo $this->SeparateurLignes ;
	}
	protected function EnvoieValeurs($valeurs)
	{
		foreach($valeurs as $i => $valeur)
		{
			if($i != 0)
			{
				echo $this->SeparateurColonnes ;
			}
			echo $this->ExprAvantValeur.strip_tags(\Pv\Misc::clean_special_chars($valeur)).$this->ExprApresValeur ;
		}
		echo $this->SeparateurLignes ;
	}
	protected function EnvoiePied()
	{
	
	}
}
