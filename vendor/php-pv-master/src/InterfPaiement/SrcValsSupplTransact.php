<?php

namespace Pv\InterfPaiement ;

class SrcValsSupplTransact extends \Pv\ZoneWeb\TableauDonnees\SrcValsSuppl
{
	public $InterfPaiemtParent ;
	public function Applique(& $composant, $ligneDonnees)
	{
		$result = parent::Applique($composant, $ligneDonnees) ;
		$interfPaie = & $this->InterfPaiemtParent ;
		// print_r($result) ;
		$interfExt = $interfPaie->ApplicationParent->InterfPaiement($result["nom_interf_paiemt"]) ;
		$result["interf_paiemt"] = "(Non trouv&eacute;)" ;
		if($interfExt != null)
		{
			$result["interf_paiemt"] = $interfExt->Titre ;
		}
		$result["titre_etat"] = (isset($interfPaie->TitresEtatExecution[$result["id_etat"]])) ? $interfPaie->TitresEtatExecution[$result["id_etat"]] : $interfPaie->TitreEtatExecutionNonTrouve ;
		return $result ;
	}
}
