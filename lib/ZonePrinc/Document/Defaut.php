<?php

namespace Rpa2p\ZonePrinc\Document ;

class Defaut extends Document
{
	public function PrepareRendu(& $zone)
	{
		parent::PrepareRendu($zone) ;
		$ctnJsFermModalPrinc = (isset($zone->ScriptPourRendu->CtnJsFermModalPrinc) && $zone->ScriptPourRendu->CtnJsFermModalPrinc != '') ? $zone->ScriptPourRendu->CtnJsFermModalPrinc : '' ;
		$ctnJs = 'var modalPrinc ;
var nodeModalPrinc ;
jQuery(function() {
nodeModalPrinc = document.getElementById("modalPrinc") ;
modalPrinc = new bootstrap.Modal(nodeModalPrinc, {
keyboard: false
}) ;'."\n" ;
		if($ctnJsFermModalPrinc != '')
		{
			$ctnJs .= 'nodeModalPrinc.addEventListener("hidden.bs.modal", function (event) {
'.$ctnJsFermModalPrinc.'
})'."\n" ;
		}
		$ctnJs .= '}) ;
function ouvreUrlModal(url) {
'.(($zone->ScriptPourRendu->ActiverAutoRafraich) ? '
if(annulAutoRafraich !== undefined)
	annulAutoRafraich() ;
' : '').'var jqModal = jQuery(document.getElementById("modalPrinc")) ;
jqModal.find("#modalPrincLabel").text("Chargement en cours...") ;
var jqFrame = jqModal.find("iframe") ;
jqFrame.attr("src", "about:blank") ;
jqFrame.css("src", "height", "100px") ;
jqFrame.attr("src", url) ;
modalPrinc.show() ;
}
function fermeModal()
{
var jqModal = jQuery(document.getElementById("modalPrinc")) ;
jqModal.find("#modalPrincLabel").text("") ;
var jqFrame = jqModal.find("iframe") ;
jqFrame.attr("src", "about:blank") ;
modalPrinc.hide() ;'.(($zone->ScriptPourRendu->ActiverAutoRafraich) ? 'if(demarreAutoRafraich !== undefined)
	demarreAutoRafraich() ;
' : '').'	
}
function majModal(titre, hauteur) {
var jqModal = jQuery(document.getElementById("modalPrinc")) ;
jqModal.find("#modalPrincLabel").text(titre) ;
jqModal.find("iframe").css("height", hauteur) ;
}' ;
		$zone->InscritContenuJs($ctnJs) ;
	}
	public function RenduEntete(& $zone)
	{
		$ctn = '' ;
		$ctn .= parent::RenduEntete($zone).PHP_EOL;
		$ctn .= '<div class="container-fluid p-4">
<div class="row">
<div class="col">
<div class="row d-flex justify-content-center">'.PHP_EOL ;
		$ctn .= '<div class="row">
<div class="col-sm-6 col-12 d-flex justify-content-start">
<h1 class="text-left"><a href="?">
 <img src="images/amblemesoluci.png" height="120" href="?"/></a>
' .htmlentities(\Rpa2p\Config\Application::NOM).'
</h1>
</div>
<div class="col-sm-6 col-12 d-flex justify-content-end p-4">'.PHP_EOL ;
		$ctn .= '<div class="dropdown">
<button class="btn btn-dark dropdown-toggle" style ="
background: #FF7800; 
border-radius: 30px;
padding: 20px;
border: none;
text-align: center;
" type="button" id="membreConnecteMenu" data-bs-toggle="dropdown" aria-expanded="false">' ;
		if($zone->SurScriptConnecte())
		{
			$ctn .= htmlentities($zone->LoginMembreConnecte())." / ".htmlentities($zone->TitreProfilConnecte()) ;
		}
		else
		{
			$ctn .= 'Connexion' ;
		}
		$ctn .= '</button>'.PHP_EOL ;
		$ctn .= '<ul class="dropdown-menu" aria-labelledby="membreConnecteMenu">'.PHP_EOL ;
		if($zone->SurScriptConnecte())
		{
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=modifPrefs">Votre compte</a></li>'.PHP_EOL ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=changeMotPasse">Mot de passe</a></li>'.PHP_EOL ;
			$ctn .= '<li><hr class="dropdown-divider"></li>'.PHP_EOL ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=deconnexion">Deconnexion</a></li>'.PHP_EOL ;
		}
		else
		{
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=connexion">Connexion</a></li>'.PHP_EOL ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=recouvreMP">Reinitialiser</a></li>'.PHP_EOL ;
		}
		$ctn .= '</ul>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>
</div>
<br>'.PHP_EOL ;
		// Barre de menu
		$ctn .= '<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="container-fluid">
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item">
  <a class="nav-link active" aria-current="page" href="?"><i class="fa fa-home">Tableau de bord</i></a>
</li>' ;
	if($zone->SurScriptConnecte())
	{
		if($zone->PossedePrivilege("consult_execs"))
		{
			$ctn .= '<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	Suivi
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeExecsJobs">Jobs</a></li>' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeSupervisJob" target="_supervision">Supervision</a></li>' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeExecsActivit">Activit&eacute;s</a></li>' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeExecsErrActivit">Echecs activit&eacute;s</a></li>' ;
			$ctn .= '<li><hr class="dropdown-divider"></li>' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeInfosPlanif">Jobs planifiés</a></li>' ;
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeJobsNonDemar">Jobs non démarrés</a></li>' ;
			$ctn .= '</ul>
</li>' ;
		}
		if($zone->PossedePrivileges(array("gestion_jobs", "exec_jobs")))
		{
			$ctn .= '<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	Jobs
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	<li><a class="dropdown-item" href="?appelleScript=listeJobs">Lister les jobs</a></li>' ;
			if($zone->PossedePrivilege("gestion_jobs"))
			{
				$ctn .= '<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutJob\')">Créer job</a></li>' ;
			}
			$ctn .= '</ul>
</li>' ;
		}
		if($zone->PossedePrivilege("gestion_references"))
		{
			$ctn .= '<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	Références
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	<li><a class="dropdown-item" href="?appelleScript=listeEnvs">Environnements</a></li>
	<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutEnv\')">Créer environnement</a></li>
	<li><hr class="dropdown-divider"></li>
	<li><a class="dropdown-item" href="?appelleScript=listeApps">Applications</a></li>
	<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutApp\')">Créer application</a></li>
	<li><hr class="dropdown-divider"></li>
	<li><a class="dropdown-item" href="?appelleScript=listeProprietes">Propriétés</a></li>
	<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutPropriete\')">Créer propriété</a></li>
	<li><hr class="dropdown-divider"></li>
	<li><a class="dropdown-item" href="?appelleScript=listeVars">Variables</a></li>
	<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutVar\')">Créer variable</a></li>
  </ul>
</li>' ;
		}
		/*
		if($zone->PossedePrivilege("gestion_configs"))
		{
			$ctn .= '<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	Configurations
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	<li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=configPlanif\')">Planificateur</a></li>
  </ul>
</li>' ;
		}
		*/
		if($zone->EditMembresPossible() || $zone->EditMembershipPossible())
		{
			$ctn .= '<li class="nav-item dropdown">
	  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		Authentification
	  </a>
	  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		<li><a class="dropdown-item" href="?appelleScript=listeMembres">Membres</a></li>
		<li><a class="dropdown-item" href="?appelleScript=ajoutMembre">Créer membre</a></li>
		<li><hr class="dropdown-divider"></li>' ;
		if($zone->EditMembershipPossible())
		{
			$ctn .= '<li><a class="dropdown-item" href="?appelleScript=listeProfils">Profils</a></li>
		<li><a class="dropdown-item" href="?appelleScript=ajoutProfil">Créer profil</a></li>
		<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="?appelleScript=listeRoles">Rôles</a></li>
		<li><a class="dropdown-item" href="?appelleScript=ajoutRole">Créer rôle</a></li>' ;
			if($zone->Membership->ADServerMemberColumn != "")
			{
				$ctn .= '<li><hr class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="?appelleScript=listeServeursAD">Connexions AD</a></li>
<li><a class="dropdown-item" href="?appelleScript=ajoutServeurAD">Créer connexion AD</a></li>' ;
			}
		}
		$ctn .= '</ul>
	</li>' ;
		}
	}
	$ctn .= '<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	Aide
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	<!-- <li><a class="dropdown-item" href="#">Documentation</a></li> -->' ;
	if($zone->PossedePrivilege("consulte_clients"))
	{
		$ctn .= ' <li><a class="dropdown-item" href="?appelleScript=nos_clients">Nos clients</a></li>' ;
	}
	$ctn .= ' <li><a class="dropdown-item" href="javascript:ouvreUrlModal(\'?appelleScript=aPropos\')">A propos</a></li>
  </ul>
</li>' ;
	$ctn .= '</ul>
</div>
</div>
</nav>' ;
		// Fin Barre de menu
		$ctn .= '<div class="row">
<div class="col">' ;
		return $ctn ;
	}
	public function RenduPied(& $zone)
	{
		$ctn = '' ;
		$ctn .= '</div>
</div>
<br>'.PHP_EOL ;
		$ctn .= '</div>
</div>
</div>
</div>'.PHP_EOL ;
		$ctn .= '<div class="modal fade" id="modalPrinc" tabindex="-1" aria-labelledby="modalPrincLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPrincLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  <iframe frameborder="0" scrolling="no" style="width:100%;" src="about:blank"></iframe>
	  </div>
    </div>
  </div>
</div>' ;
		$ctn .= parent::RenduPied($zone) ;
		return $ctn ;
	}
}
