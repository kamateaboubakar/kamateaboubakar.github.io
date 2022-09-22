<?php

namespace Pv\ZoneBootstrap ;

class ZoneBootstrap extends \Pv\ZoneWeb\ZoneWeb
{
	public $LangueDocument = "en" ;
	public $EncodageDocument = "utf-8" ;
	public $AutoriserModifPrefs = true ;
	public $InclureCtnJsEntete = true ;
	public $InclureJQuery = true ;
	public $VersionBootstrap = 5 ;
	public $InclureBootstrap = true ;
	public $RedirigerVersConnexion = true ;
	public $RenduExtraHead = '<meta http-equiv="X-UA-Compatible" content="IE=edge">' ;
	public $ViewportMeta = 'width=device-width, initial-scale=1' ;
	public $ClasseCSSMsgExecSucces = "alert alert-success" ;
	public $ClasseCSSMsgExecErreur = "alert alert-danger" ;
	public $CheminCSSBootstrap = 'css/bootstrap.min.css' ;
	public $NomClasseScriptConnexion = '\Pv\ZoneBootstrap\ScriptMembership\Connexion' ;
	public $CheminFontAwesome = 'vendor/fontawesome/css/all.min.css' ;
	protected function AfficheRenduIndisponible(& $script, $msg)
	{
		$ctn = '' ;
		$this->ScriptPourRendu = & $script ;
		$ctn .= $this->RenduEnteteDocument() ;
		$ctn .= '<div class="alert alert-danger" role="alert">'.$msg.'</div>' ;
		$ctn .= $this->RenduPiedDocument() ;
		$this->ScriptPourRendu = null ;
		echo $ctn ;
		exit ;
	}
	public function CreeTablPrinc()
	{
		return new \Pv\ZoneBootstrap\TableauDonnees\TableauDonnees() ;
	}
	public function CreeFormPrinc()
	{
		return new \Pv\ZoneBootstrap\FormulaireDonnees\FormulaireDonnees() ;
	}
	public function CreeGrillePrinc()
	{
		return new \Pv\ZoneBootstrap\TableauDonnees\GrilleDonnees() ;
	}
	public function CreeRepetPrinc()
	{
		return new \Pv\ZoneBootstrap\TableauDonnees\RepeteurDonnees() ;
	}
	protected function DefinitAliasCompsFltsDonnees()
	{
		parent::DefinitAliasCompsFltsDonnees() ;
		\Pv\Application\Application::$AliasesCompsFltsDonnees = array_merge(
			\Pv\Application\Application::$AliasesCompsFltsDonnees,
			array(
				'PvZoneBoiteOptionsRadioBootstrap' => '\Pv\ZoneBootstrap\FiltresDonnees\Composant\ZoneBoiteOptionRadio',
				'PvZoneBoiteOptionsCocherBootstrap' => '\Pv\ZoneBootstrap\FiltresDonnees\Composant\ZoneBoiteOptionsCocher',
			)
		) ;
	}
}

