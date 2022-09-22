<?php

namespace Rpa2p\ZonePrinc\Script ;

class Script extends \Pv\ZoneWeb\Script\Script
{
	public $NecessiteMembreConnecte = true ;
	public $CtnJsFermModalPrinc = "" ;
	public function CreeTablPrinc()
	{
		return new \Rpa2p\ZonePrinc\TableauDonnees() ;
	}
	public function CreeFormPrinc()
	{
		return new \Rpa2p\ZonePrinc\FormulaireDonnees() ;
	}
	public function CreeGrillePrinc()
	{
		return new \Rpa2p\ZonePrinc\GrilleDonnees() ;
	}
	public function RenduLiensPrinc()
	{
		return '' ;
	}
	public function AppliqueCommande(& $cmd)
	{
		$cmd->ConfirmeSucces() ;
	}
	public function AppliqueActCmd(& $actCmd)
	{
	}
	public function ValideCritere(& $critere)
	{
		return true ;
	}
	public function ExtraitSrcValsSuppl($ligneDonnees, & $composant, & $srcValsSuppl)
	{
		return $ligneDonnees ;
	}
	public function DessineFiltres(& $dessin, & $composant, $parametres)
	{
		return '' ;
	}

}
