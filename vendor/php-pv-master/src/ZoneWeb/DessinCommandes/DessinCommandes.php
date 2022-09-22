<?php

namespace Pv\ZoneWeb\DessinCommandes ;

class DessinCommandes extends \Pv\ZoneWeb\DessinFiltres\DessinFiltres
{
	public $InclureIcones = 1 ;
	public $InclureLibelle = 1 ;
	public $HauteurIcone = 20 ;
	public $CheminIconeParDefaut = "images/execute_icon.png" ;
	public $SeparateurIconeLibelle = "&nbsp;&nbsp;" ;
	public $SeparateurCommandes = "&nbsp;&nbsp;&nbsp;&nbsp;" ;
	protected function DebutExecParam(& $script, & $composant, $i, $param)
	{
		return "" ;
	}
	protected function FinExecParam(& $script, & $composant, $i, $param)
	{
		return "" ;
	}
	protected function PeutAfficherCmd(& $commande)
	{
		if($commande->Visible == 0 || $commande->EstBienRefere() == 0 || $commande->EstAccessible() == 0)
		{
			return 0 ;
		}
		return 1 ;
	}
	public function RenduCommande(& $commande, & $composant)
	{
		$ctn = '' ;
		if($commande->ContenuAvantRendu != '')
		{
			$ctn .= $commande->ContenuAvantRendu ;
		}
		$ctn .= '<button id="'.$commande->IDInstanceCalc.'" class="Commande '.$commande->NomClsCSS.'" type="submit" rel="'.$commande->NomElementSousComposantRendu.'"' ;
		$contenuJsSurClick = ($commande->ContenuJsSurClick == '') ? $composant->IDInstanceCalc.'_ActiveCommande(this) ;' : $commande->ContenuJsSurClick.' ; return false ;' ;
		$ctn .= ' onclick="'.$contenuJsSurClick.'"' ;
		if($this->InclureLibelle == 0)
		{
			$ctn .= ' title="'.htmlspecialchars($commande->Libelle).'"' ;
		}
		$ctn .= '>'.PHP_EOL ;
		if($this->InclureIcones)
		{
			$cheminIcone = $this->CheminIconeParDefaut ;
			if($commande->CheminIcone != '')
			{
				$cheminIcone = $commande->CheminIcone ;
			}
			if(file_exists($cheminIcone))
			{
				$ctn .= '<img src="'.$cheminIcone.'" height="'.$this->HauteurIcone.'" border="0" />' ;
			}
			if($commande->InclureLibelle == 1)
			{
				$ctn .= $this->SeparateurIconeLibelle ;
			}
		}
		if($this->InclureLibelle)
		{
			$ctn .= $commande->Libelle ;
		}
		$ctn .= '</button>'.PHP_EOL ;
		if($commande->ContenuApresRendu != '')
		{
			$ctn .= $commande->ContenuApresRendu ;
		}
		return $ctn ;
	}
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
				$ctn .= $this->RenduCommande($commande, $composant) ;
				$ctn .= $this->FinExecParam($script, $composant, $i, $commande) ;
			}
		}
		return $ctn ;
	}
}

