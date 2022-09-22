<?php

namespace Pv\ZoneBootstrap\ScriptMembership ;

class Connexion extends \Pv\ZoneWeb\ScriptMembership\Connexion
{
	public $MessageRecouvreMP = '<br><p>Mot de passe oubli&eacute; ? <a href="${url}">Cliquez ici</a> pour le r&eacute;cup&eacute;rer</p>' ;
	public $MessageInscription = '<br><p>Si vous n\'avez pas de compte, <a href="${url}">Inscrivez-vous</a>.</p>' ;
	public $ColXsLibelle = 5 ;
	public $ClsBstBoutonSoumettre = "" ;
	public $ClsBstFormulaire = "bg-light" ;
	public $AlignBtnSoumettre = "right" ;
	public $TagTitre = 'h3' ;
	public $InclureIcones = 0 ;
	public $ClasseCSSCadre = "col-12 col-sm-12 col-md-6 col-lg-4" ;
	public $ClasseCSSConteneur = "" ;
	public $ClasseCSSErreur = 'alert alert-danger alert-dismissable' ;
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= '<div class="row'.(($this->ClasseCSSConteneur != '') ? ' '.$this->ClasseCSSConteneur : '').'">'.PHP_EOL ;
		$ctn .= '<div class="'.$this->ClasseCSSCadre.'">'.PHP_EOL ;
		$ctn .= '<form class="user_login_box '.$this->NomClsCSSFormulaireDonnees.'" action="'.$this->UrlSoumetTentativeConnexion().'" role="form" method="post">'.PHP_EOL ;
		$ctn .= '<div class="card '.$this->ClsBstFormulaire.'">'.PHP_EOL ;
		$ctn .= '<div class="card-body">'.PHP_EOL ;
		$ctn .= $this->RenduMessageErreur() ;
		$ctn .= $this->RenduTableauParametres().PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		if($this->AfficherBoutonSoumettre)
		{
			$ctn .= '<div class="card-footer" align="'.$this->AlignBtnSoumettre.'">'.PHP_EOL ;
			$ctn .= '<input type="submit" value="'.$this->LibelleBoutonSoumettre.'" class="btn btn-lg btn-success'.(($this->ClsBstBoutonSoumettre) ? ' '.$this->ClsBstBoutonSoumettre : '').'" />'.PHP_EOL ;
			$ctn .= '</div>'.PHP_EOL ;
		}
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</form>'.PHP_EOL ;
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>' ;
		if($this->ZoneParent->AutoriserInscription == 1 && $this->ZoneParent->EstPasNul($this->ZoneParent->ScriptInscription))
		{
			if($this->AutoriserUrlsRetour == 1 && $this->ZoneParent->ScriptInscription->AutoriserUrlsRetour == 1)
			{
				$this->ParamsUrlInscription[$this->ZoneParent->ScriptInscription->NomParamUrlRetour] = $this->ValeurUrlRetour ;
			}
			$ctn .= \Pv\Misc::_parse_pattern($this->MessageInscription, array("url" => $this->ZoneParent->ScriptInscription->ObtientUrlParam($this->ParamsUrlInscription))) ;
		}
		if($this->ZoneParent->EstPasNul($this->ZoneParent->ScriptRecouvreMP))
		{
			$ctn .= \Pv\Misc::_parse_pattern($this->MessageRecouvreMP, array("url" => $this->ZoneParent->ScriptRecouvreMP->ObtientUrlParam($this->ParamsUrlRecouvreMP))) ;
		}
		return $ctn ;
	}
	public function RenduTableauParametres()
	{
		$ctn = '' ;
		$ctn .= '<div class="row mb-2">
<div class="col-'.$this->ColXsLibelle.'">'.$this->LibellePseudo.'</div>
<div class="col-'.(12 - $this->ColXsLibelle).'">
'.(($this->InclureIcones) ? '<div class="input-group">
<span class="input-group-addon">
<i class="glyphicon glyphicon-user"></i>
</span>' : '').'<input class="form-control" name="'.$this->NomParamPseudo.'" type="text" value="'.htmlspecialchars($this->ValeurParamPseudo).'" autofocus />
'.(($this->InclureIcones) ? '</div>' : '').'</div>
</div>
<div class="row mb-2">
<div class="col-'.$this->ColXsLibelle.'">'.$this->LibelleMotPasse.'</div>
<div class="col-'.(12 - $this->ColXsLibelle).'">
'.(($this->InclureIcones) ? '<div class="input-group">
<span class="input-group-addon">
<i class="glyphicon glyphicon-lock"></i>
</span>' : '').'<input class="form-control" name="'.$this->NomParamMotPasse.'" type="password" value="" />
'.(($this->InclureIcones) ? '</div>' : '').'</div>
</div>' ;
		$ctn .= '<input type="hidden" name="'.$this->NomParamSoumetTentative.'" value="'.htmlentities($this->ValeurParamSoumetTentative).'" />' ;
		if($this->InclureIcones)
		{
			$ctn .= '<style type="text/css">
.icon-addon {
position: relative;
color: #555;
display: block;
}
.icon-addon:after,
.icon-addon:before {
display: table;
content: " ";
}

.icon-addon:after {
clear: both;
}

.icon-addon.addon-md .glyphicon,
.icon-addon .glyphicon, 
.icon-addon.addon-md .fa,
.icon-addon .fa {
position: absolute;
z-index: 2;
left: 10px;
font-size: 14px;
width: 20px;
margin-left: -2.5px;
text-align: center;
padding: 10px 0;
top: 1px
}
</style>' ;
		}
		return $ctn ;
	}
}
