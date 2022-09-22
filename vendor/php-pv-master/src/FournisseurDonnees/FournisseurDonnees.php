<?php

namespace Pv\FournisseurDonnees ;

class FournisseurDonnees extends \Pv\Objet\Objet
{
	public $DerniereException = null ;
	protected function CreeException()
	{
		return new \Pv\Objet\Exception() ;
	}
	public function ExceptionTrouvee()
	{
		return ($this->DerniereException != null) ? 1 : 0 ;
	}
	public function MessageException()
	{
		if($this->DerniereException == null)
		{
			return "" ;
		}
		return $this->DerniereException->Message ;
	}
	protected function VideDerniereException()
	{
	}
	public function ExecuteRequete($requete, $params=array())
	{
		return array() ;
	}
	public function SelectElementsTries($colonnes, $filtres, $indiceColonneTri=0, $sensColonneTri="asc")
	{
		
	}
	public function RangeeElementsTries($colonnes, $filtres, $indiceDebut=0, $maxElements=100, $indiceColonneTri=0, $sensColonneTri="asc")
	{
		
	}
	public function SelectElements($colonnes, $filtres, $indiceColonneTri=0, $sensColonneTri="asc")
	{
	}
	public function AgregeElements($exprs, $filtres)
	{
		return array() ;
	}
	public function RangeeElements($colonnes, $filtres, $indiceDebut=0, $maxElements=100, $indiceColonneTri=0, $sensColonneTri="asc")
	{
	}
	public function CompteElements($colonnes, $filtres)
	{
	}
	public function LigneElement($colonnes, $filtresGlobaux, $filtresLigne, $indiceColonneTri=0, $sensColonneTri="asc")
	{
	}
	public function AjoutElement($filtresEdition)
	{
	}
	public function ModifElement($filtresSelection, $filtresEdition)
	{
	}
	public function SupprElement($filtresSelection)
	{
	}
	public function OuvreRequeteSelectElements($filtres, $colonnes=array())
	{
	}
	public function LitRequete(& $requete)
	{
	}
	public function FermeRequete(& $requete)
	{
	}
	public function RechExacteElements($filtres, $nomColonne, $valeur)
	{
	}
	public function RechsExactesElements($filtres, $nomColonne, $valeurs)
	{
	}
	public function RechPartielleElements($filtres, $nomColonnes, $valeur)
	{
	}
	public function RechDebuteElements($filtres, $nomColonnes, $valeur)
	{
	}
	public function EncodeEntiteHtml($valeur)
	{
		return htmlentities($valeur) ;
	}
	public function EncodeAttrHtml($valeur)
	{
		return htmlspecialchars($valeur) ;
	}
	public function EncodeUrl($valeur)
	{
		return urlencode($valeur) ;
	}
}