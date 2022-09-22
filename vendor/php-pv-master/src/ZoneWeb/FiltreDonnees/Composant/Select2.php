<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class Select2 extends EditeurJQuery
{
	public $CheminFichierJs = "js/select2.min.js" ;
	public $CheminFichierCSS = "css/select2.min.css" ;
	public $FournisseurDonnees ;
	public $NomColonneLibelle ;
	public $NomColonneValeur ;
	public $NomsColonneExtra = array() ;
	public $ActSupport ;
	public $Largeur = '100%' ;
	public $MaxElemsParPage = 30 ;
	public $SeparateurLibelleEtiqVide = ", " ;
	public $FiltresSelection = array() ;
	public $InclureElementHorsLigne = 0 ;
	public $ValeurElementHorsLigne = "" ;
	public $LibelleElementHorsLigne = "" ;
	public $RechercheParDebut = 1 ;
	public $ParamsActSupport = array() ;
	public function ExtraitFiltresSelection($termeRech)
	{
		$filtres = $this->FiltresSelection ;
		return $filtres ;
	}
	public function InitConfig()
	{
		parent::InitConfig() ;
		$this->ActSupport = new Select2\ActSupport() ;
	}
	public function AdopteZone($nom, & $zone)
	{
		parent::AdopteZone($nom, $zone) ;
		$this->InscritActionAvantRendu("ActSupport_".$this->IDInstanceCalc, $this->ActSupport) ;
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ActSupport->ChargeConfig() ;
	}
	protected function InitFonctsInst()
	{
		$this->FonctsInst[] = new FonctInstJQuery("escapeMarkup", array("markup"), "return markup ;") ;
		$this->FonctsInst[] = new FonctInstJQuery("ajax.data", array("params"), "return {
q: params.term,
page: params.page
} ;") ;
	}
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$ctn .= '<select name="'.htmlentities($this->NomElementHtml).'" id="'.$this->IDInstanceCalc.'"'.$this->RenduAttrStyleCSS().''.$this->RenduAttrsSupplHtml().'>'.PHP_EOL ;
		$ctn .= '</select>' ;
		return $ctn ;
	}
	protected function CreeCfgInst()
	{
		return new Select2\Cfg() ;
	}
	protected function CtnJSDeclInst()
	{
		$ctn = 'jQuery("#'.$this->IDInstanceCalc.'").select2(cfgInst'.$this->IDInstanceCalc.') ;' ;
		if($this->Valeur != null && ! is_array($this->Valeur) && strpos($this->Valeur, ";") === false)
		{
			$valeurSelect = $this->Valeur ;
			$lignes = $this->FournisseurDonnees->RechExacteElements($this->FiltresSelection, $this->NomColonneValeur, $valeurSelect) ;
			// print_r($this->FournisseurDonnees) ;
			if(is_array($lignes) && count($lignes))
			{
				$ctn .= PHP_EOL .'var noeudSelect2 = jQuery("#'.$this->IDInstanceCalc.'") ;
var dataDefaut = { id : '.svc_json_encode($lignes[0][$this->NomColonneValeur]).', text : '.svc_json_encode($lignes[0][$this->NomColonneLibelle]) ;
				foreach($this->NomsColonneExtra as $i => $nomCol)
				{
					$ctn .= ', '.$nomCol.' : '.svc_json_encode($lignes[0][$nomCol]) ;
				}
				$ctn .= '} ;
var option = new Option(dataDefaut.text, dataDefaut.id, true, true);
noeudSelect2.append(option) ;
noeudSelect2.trigger({
type: "select2:select",
params: {
data: dataDefaut
}
});' ;
			}
		}
		return $ctn ;
	}
	protected function CtnJSCfgInst()
	{
		$ctnFonctProcRes = 'params.page = params.page || 1;
if(data == null || data == undefined) {
return { results : [{ id : -1, text : "Contenu vide"}] }
}
return {
results: jQuery.map(data.items, function (item) {
return { text: item.'.$this->NomColonneLibelle.', id: item.'.$this->NomColonneValeur ;
		foreach($this->NomsColonneExtra as $i => $nomCol)
		{
			$ctnFonctProcRes .= ', '.$nomCol.' : item.'.$nomCol ;
		}
		$ctnFonctProcRes .= ' } ;
}),
pagination: {
more: (params.page * '.$this->MaxElemsParPage.') < data.total_count
}
};' ;
		$this->FonctsInst[] = new FonctInstJQuery("ajax.processResults", array("data", "params"), $ctnFonctProcRes) ;
		$this->CfgInst->placeholder = $this->EspaceReserve ;
		if($this->InclureElementHorsLigne == 1)
		{
			$this->CfgInst->placeholder = new PvCfgPlaceholderSelect2() ;
			$this->CfgInst->placeholder->id = $this->ValeurElementHorsLigne ;
			$this->CfgInst->placeholder->text = $this->LibelleElementHorsLigne ;
		}
		$this->CfgInst->ajax->url = $this->ActSupport->ObtientUrl($this->ParamsActSupport) ;
		$ctn = parent::CtnJSCfgInst().PHP_EOL ;
		return $ctn ;
	}
	protected function RenduSourceBrut()
	{
		$ctn = $this->RenduLienJs($this->CheminFichierJs) ;
		if($ctn != '') { $ctn .= PHP_EOL ; }
		$ctn .= $this->RenduContenuJs('jQuery.fn.select2.defaults.set("language", "fr");') ;
		if($ctn != '') { $ctn .= PHP_EOL ; }
		return $ctn.'<link rel="stylesheet" type="text/css" href="'.$this->CheminFichierCSS.'">'.PHP_EOL ;
	}
	public function RenduEtiquette()
	{
		if($this->EstNul($this->FournisseurDonnees))
		{
			return parent::RenduEtiquette() ;
		}
		$lignes = $this->FournisseurDonnees->RechExacteElements($this->FiltresSelection, $this->NomColonneValeur, $this->Valeur) ;
		$etiquette = '' ;
		if(count($lignes) > 0)
		{
			foreach($lignes as $i => $ligne)
			{
				if($etiquette != "")
				{
					$etiquette .= $this->SeparateurLibelleEtiqVide ;
				}
				$etiquette .= $ligne[$this->NomColonneLibelle] ;
			}
		}
		else
		{
			$etiquette = $this->LibelleEtiqVide ;
		}
		return $etiquette ;
	}
}
