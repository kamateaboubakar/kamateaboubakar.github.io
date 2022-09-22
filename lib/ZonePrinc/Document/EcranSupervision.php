<?php

namespace Rpa2p\ZonePrinc\Document ;

class EcranSupervision extends Document
{
	public $ActiverAutoRafraich = true ;
	public $DelaiAutoRafraich = 30 ;
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
<h1 class="text-left">
<img src="images/amblemesoluci.png" height="120" href="?"/></a>
'.htmlentities(\Rpa2p\Config\Application::NOM).'
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
<hr>'.PHP_EOL ;
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
  <div class="modal-dialog">
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
