<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class Donnees extends ComposantRendu
{
	public $TypeComposant = "ComposantDonnees" ;
	public $Editable = 1 ;
	public $NomParamIdCommande = "Commande" ;
	public $ValeurParamIdCommande = "" ;
	public $ParamsGetSoumetFormulaire = array() ;
	public $ChampsGetSoumetFormulaire = array() ;
	public $DesactBtnsApresSoumiss = true ;
	public $ForcerDesactCache = false ;
	public $SuffixeParamIdAleat = "id_aleat" ;
	public $InstrsJSAvantSoumetForm = "" ;
	public function CreeFournDonneesDirect($vals, $nomCle='')
	{
		$fourn = new \Pv\FournisseurDonnees\Directe() ;
		if($nomCle == '')
			$nomCle = 'Principale' ;
		$fourn->Valeurs[$nomCle] = $vals ;
		return $fourn ;
	}
	public function CreeFournDonneesSql(& $bd, $reqSelect='', $tablEdit='')
	{
		$fourn = new \Pv\FournisseurDonnees\Sql() ;
		$fourn->BaseDonnees = & $bd ;
		$fourn->RequeteSelection = $reqSelect ;
		$fourn->TableEdition = $tablEdit ;
		return $fourn ;
	}
	public function DeclareFournDonneesDirect($valeurs, $nomCle='')
	{
		$this->FournisseurDonnees = $this->CreeFournDonneesDirect($valeurs, $nomCle) ;
	}
	public function DeclareFournDonneesSql(& $bd, $reqSelect='', $tablEdit='')
	{
		$this->FournisseurDonnees = $this->CreeFournDonneesSql($bd, $reqSelect, $tablEdit) ;
	}
	public function NomParamIdAleat()
	{
		return $this->IDInstanceCalc."_".$this->SuffixeParamIdAleat ;
	}
	protected function CtnJsActualiseFormulaireFiltres()
	{
		$ctn = '' ;
		$ctn .= 'ActualiseFormulaire'.$this->IDInstanceCalc.'()' ;
		return $ctn ;
	}
	protected function DeclarationSoumetFormulaireFiltres($filtres)
	{
		$nomFiltres = array_keys($filtres) ;
		$filtresGets = array() ;
		$nomFiltresGets = array($this->IDInstanceCalc."_".$this->NomParamIdCommande) ;
		$filtresGetsEdit = array() ;
		foreach($nomFiltres as $i => $nomFiltre)
		{
			if($filtres[$nomFiltre]->TypeLiaisonParametre == "get")
			{
				$filtresGetsEdit[] = $filtres[$nomFiltre]->ObtientIDElementHtmlComposant() ;
				$nomFiltresGets[] = $filtres[$nomFiltre]->NomParametreLie ;
			}
		}
		foreach($this->ChampsGetSoumetFormulaire as $n => $v)
		{
			$filtresGetsEdit[] = $v ;
		}
		foreach($this->ParamsGetSoumetFormulaire as $n => $v)
		{
			$filtresGets[] = $v ;
		}
		$params = \Pv\Misc::extract_array_without_keys($_GET, $nomFiltresGets) ;
		// print_r($nomFiltresGets) ;
		$filtresGets = array_unique($filtresGets) ;
		$indexMinUrl = (count($params) > 0) ? 0 : 1 ;
		$urlFormulaire = \Pv\Misc::remove_url_params(\Pv\Misc::get_current_url()) ;
		$urlFormulaire .= '?'.\Pv\Misc::http_build_query_string($params) ;
		$instrDesactivs = '' ;
		if($this->DesactBtnsApresSoumiss)
		{
			$instrDesactivs = '		for(var i=0; i<form.elements.length; i++)
{
	var elem = form.elements[i] ;
	if(elem.type == "submit")
	{
		if(elem.disabled != undefined)
			elem.disabled = "disabled" ;
		else
			elem.setAttribute("disabled", "disabled") ;
	}
}'.PHP_EOL ;
		}
		if($this->ForcerDesactCache)
		{
			$urlFormulaire .= '&'.urlencode($this->NomParamIdAleat()).'='.htmlspecialchars(rand(0, 999999)) ;
		}
		$ctn = '<script type="text/javascript">
function SoumetFormulaire'.$this->IDInstanceCalc.'(form)
{
var urlFormulaire = "'.$urlFormulaire.'" ;
///JJJ
var parametresGet = '.json_encode($filtresGetsEdit).' ;
if(parametresGet != undefined )
{
	for(var i=0; i<parametresGet.length; i++)
	{
		if(i >= '.$indexMinUrl.')
		{
			urlFormulaire += "&" ;
		}
		var nomParam = parametresGet[i] ;
		var valeurParam = "" ;
		var elementParam = document.getElementById(nomParam) ;
		if(elementParam != null)
		{
		
			nomParam = elementParam.name ;
			valeurParam = elementParam.value ;
			elementParam.disabled = "disabled" ;
		}
		urlFormulaire += encodeURIComponent(nomParam) + "=" + encodeURIComponent(valeurParam) ;
	}
}
// alert(urlFormulaire) ;
'.$instrDesactivs.'		form.action = urlFormulaire ;
return true ;
}
function ActualiseFormulaire'.$this->IDInstanceCalc.'()
{
'.$this->CtnJsActualiseFormulaireFiltres().' ;
}'.$this->CtnJsSoumetSurEntree().'
</script>' ;
		return $ctn ;
	}
	protected function DeclarationJsActiveCommande()
	{
		$ctn = '' ;
		$ctn .= '<input type="hidden" name="'.$this->IDInstanceCalc.'_'.$this->NomParamIdCommande.'" value="" />'.PHP_EOL ;
		$ctn .= '<script type="text/javascript">
if(typeof '.$this->IDInstanceCalc.'_ActiveCommande != "function")
{
function '.$this->IDInstanceCalc.'_ActiveCommande(btn)
{
	document.getElementsByName("'.$this->IDInstanceCalc.'_'.$this->NomParamIdCommande.'")[0].value = (btn.rel != undefined) ? btn.rel : btn.getAttribute("rel") ;
	return true ;
}
}
</script>' ;
		return $ctn ;
	}
	protected function ChargeFournisseurDonnees()
	{
		$nomClasse = $this->NomClasseFournisseurDonnees ;
		$this->FournisseurDonnees = null ;
		if(class_exists($nomClasse))
		{
			$this->FournisseurDonnees = new $nomClasse() ;
			$this->FournisseurDonnees->ChargeConfig() ;
		}
	}
	public function ChargeConfig()
	{
		parent::ChargeConfig() ;
		$this->ChargeFournisseurDonnees() ;
	}
	public function PrepareRendu()
	{
		parent::PrepareRendu() ;
	}
	protected function CtnJsSoumetSurEntree()
	{
		return '' ;
	}
}
