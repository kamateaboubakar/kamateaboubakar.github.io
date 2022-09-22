<?php

namespace Pv\ZoneWeb\ScriptMembership ;

class Deconnexion extends \Pv\ZoneWeb\Script\Script
{
	public $Titre = "D&eacute;connexion" ;
	public $TitreDocument = "D&eacute;connexion" ;
	public $UrlDeconnexionReussie = "" ;
	public $NomScriptDeconnexionReussie = "" ;
	public $MessageDeconnexionReussie = "Vous avez &eacute;t&eacute; d&eacute;connect&eacute; avec succ&egrave;s." ;
	public $NecessiteMembreConnecte = 1 ;
	public $MessageRetourAccueil = "Retour &agrave; la page d'accueil" ;
	public function DetermineEnvironnement()
	{
		if(! $this->ZoneParent->EstNul($this->ZoneParent->Membership) && $this->ZoneParent->PossedeMembreConnecte())
		{
			$this->ZoneParent->Membership->LogoutMember($this->ZoneParent->Membership->MemberLogged->Id) ;
		}
		$url = '' ;
		if($this->NomScriptDeconnexionReussie != '' && isset($this->ZoneParent->Scripts[$this->NomScriptDeconnexionReussie]))
		{
			$url = $this->ZoneParent->Scripts[$this->NomScriptDeconnexionReussie] ;
		}
		elseif($this->UrlDeconnexionReussie != '')
		{
			$url = $this->UrlDeconnexionReussie ;
		}
		if($url != '')
		{
			\Pv\Misc::redirect_to($url) ;
		}
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		if($this->MessageDeconnexionReussie != '')
		{
			$ctn .= '<p>'.$this->MessageDeconnexionReussie.'</p>' ;
		}
		$ctn .= '<p align="center"><a href="'.$this->ZoneParent->ObtientUrl().'">'.$this->MessageRetourAccueil.'</a></p>' ;
		return $ctn ;
	}
}