<?php

namespace Pv\ZoneBootstrap\DessinCommandes ;

class DessinCommandes extends \Pv\ZoneWeb\DessinCommandes\DessinCommandes
{
	public $ClasseCSSPanel = "card-primary" ;
	public function Execute(& $script, & $composant, $parametres)
	{
		$ctn = '' ;
		$commandes = $parametres ;
		$nomCommandes = array_keys($commandes) ;
		foreach($nomCommandes as $i => $nomCommande)
		{
			$commande = & $commandes[$nomCommande] ;
			if($this->PeutAfficherCmd($commande) == 0)
			{
				continue ;
			}
			if($ctn != '')
			{
				$ctn .= $this->SeparateurCommandes. PHP_EOL ;
			}
			if($commande->UtiliserRenduDispositif)
			{
				$ctn .= $commande->RenduDispositif() ;
			}
			else
			{
				$ctn .= $this->DebutExecParam($script, $composant, $i, $commande) ;
				if($commande->ContenuAvantRendu != '')
				{
					$ctn .= $commande->ContenuAvantRendu ;
				}
				$classeBtn = $commande->ObtientValSuppl("classe-btn", "btn-primary") ;
				$ctn .= '<button id="'.$commande->IDInstanceCalc.'" class="Commande btn '.$commande->NomClsCSS.' '.$classeBtn.'" type="submit" rel="'.$commande->NomElementSousComposantRendu.'"' ;
				$contenuJsSurClick = ($commande->ContenuJsSurClick == '') ? $composant->IDInstanceCalc.'_ActiveCommande(this) ;' : $commande->ContenuJsSurClick.' ; return false ;' ;
				$ctn .= ' onclick="'.$contenuJsSurClick.'"' ;
				if($this->InclureLibelle == 0)
				{
					$ctn .= ' title="'.htmlspecialchars($commande->Libelle).'"' ;
				}
				$ctn .= '>'.PHP_EOL ;
				if($this->InclureLibelle)
				{
					$ctn .= $commande->Libelle ;
				}
				$ctn .= '</button>'.PHP_EOL ;
				if($commande->ContenuApresRendu != '')
				{
					$ctn .= $commande->ContenuApresRendu ;
				}
				$ctn .= $this->FinExecParam($script, $composant, $i, $commande) ;
			}
		}
		return $ctn ;
	}
}
