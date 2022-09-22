<?php

namespace Pv\ZoneWeb\Script\Critere ;

class EditServeurAD extends \Pv\ZoneWeb\Critere\Critere
{
	public function EstRespecte()
	{
		$form = & $this->FormulaireDonneesParent ;
		if($form->InclureElementEnCours == 0)
		{
			return 1 ;
		}
		$script = $form->ScriptParent ;
		$bd = $form->FournisseurDonnees->BaseDonnees ;
		$membership = $form->ZoneParent->Membership ;
		$lgnSimilaire = $bd->FetchSqlRow(
			"select * from ".$bd->EscapeTableName($membership->ADServerTable)." where ".$bd->EscapeTableName($membership->ADServerTable, $membership->HostADServerColumn)." = ".$bd->ParamPrefix."hote and ".$bd->EscapeTableName($membership->ADServerTable, $membership->DomainADServerColumn)." = ".$bd->ParamPrefix."domaine",
			array(
				"domaine" => $script->FltDomaine->Lie(),
				"hote" => $script->FltHote->Lie(),
			)
		) ;
		if(! is_array($lgnSimilaire))
		{
			$this->MessageErreur = "Erreur SQL : ".$bd->ConnectionException ;
			return 0 ;
		}
		if(count($lgnSimilaire) > 0)
		{
			$this->MessageErreur = $script->MessageErreurDejaEnregistre ;
			return 0 ;
		}
		return 1 ;
	}
}