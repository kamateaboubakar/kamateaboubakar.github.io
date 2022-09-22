<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneCadreOptionsRadio extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneBoiteOptionsRadio
{
	public $NomParamCadre = "Cadre" ;
	public $ValeurDefautParamCadre = "" ;
	public $ValeurParamCadre = "" ;
	public $ValeurOuvreCadre = "1" ;
	public $SepOuvreCadre = "  " ;
	public $LibelleOuvreCadre = "Parcourir" ;
	public $LibelleAnnuleSelection = "Annuler" ;
	public $LibelleValideSelection = "Valider" ;
	public $StyleIncorporation = "POPUP" ;
	public $TitreDocument ;
	public $ContenusCSS = array() ;
	public $ContenusJs = array() ;
	public $CtnExtraHead ;
	public $InclureCtnJsEntete = 0 ;
	public $LargeurCadre = "100%" ;
	public $HauteurCadre = "300" ;
	public function InscritContenuCSS($contenu)
	{
		$ctnCSS = new \Pv\ZoneWeb\BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritLienCSS($href)
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		$this->ContenusCSS[] = $ctnCSS ;
	}
	public function InscritContenuJs($contenu)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritContenuJsCmpIE($contenu, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJs($src)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function InscritLienJsCmpIE($src, $versionMin=9)
	{
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
	}
	public function RenduLienCSS($href)
	{
		$ctnCSS = new \Pv\ZoneWeb\LienFichierCSS() ;
		$ctnCSS->Href = $href ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuCSS($contenu)
	{
		$ctnCSS = new \Pv\ZoneWeb\BaliseCSS() ;
		$ctnCSS->Definitions = $contenu ;
		return $ctnCSS->RenduDispositif() ;
	}
	public function RenduContenuJsInclus($contenu)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJs() ;
		$ctnJs->Definitions = $contenu ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduContenuJsCmpIEInclus($contenu, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\BaliseJsCmpIE() ;
		$ctnJs->Definitions = $contenu ;
		$ctnJs->VersionMin = $versionMin ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsInclus($src)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJs() ;
		$ctnJs->Src = $src ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	public function RenduLienJsCmpIEInclus($src, $versionMin=9)
	{
		$ctn = '' ;
		$ctnJs = new \Pv\ZoneWeb\LienFichierJsCmpIE() ;
		$ctnJs->Src = $src ;
		$ctnJs->VersionMin = $versionMin ;
		$this->ContenusJs[] = $ctnJs ;
		if(! $this->InclureCtnJsEntete)
		{
			$this->ContenusJs[] = $ctnJs ;
		}
		else
		{
			$ctn = $ctnJs->RenduDispositif() ;
		}
		return $ctn ;
	}
	protected function RenduCtnsCSS()
	{
		$ctn = '' ;
		for($i=0; $i<count($this->ContenusCSS); $i++)
		{
			$ctnCSS = $this->ContenusCSS[$i] ;
			$ctn .= $ctnCSS->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	protected function RenduCtnsJs()
	{
		$ctn = '' ;
		for($i=0; $i<count($this->ContenusJs); $i++)
		{
			$ctnJs = $this->ContenusJs[$i] ;
			$ctn .= $ctnJs->RenduDispositif().PHP_EOL ;
		}
		return $ctn ;
	}
	protected function RenduEnteteDocCadre()
	{
		$ctn = '' ;
		$ctn .= '<!doctype html>'.PHP_EOL ;
		$ctn .= '<head>'.PHP_EOL ;
		$ctn .= '<title>'.$this->TitreDocument.'</title>'.PHP_EOL ;
		$ctn .= $this->RenduCtnsCSS() ;
		if($this->InclureCtnJsEntete == 1)
		{
			$ctn .= $this->RenduCtnsJs() ;
		}
		$ctn .= $this->CtnExtraHead ;
		$ctn .= '</head>'.PHP_EOL ;
		$ctn .= '<body>' ;
		return $ctn ;
	}
	protected function RenduPiedDocCadre()
	{
		$ctn = '' ;
		if($this->InclureCtnJsEntete == 0)
		{
			$ctn .= $this->RenduCtnsJs() ;
		}
		$ctn .= '</body>'.PHP_EOL ;
		$ctn .= '</html>' ;
		return $ctn ;
	}
	protected function RenduValeurCadre()
	{
		return $this->RenduEtiquette() ;
	}
	protected function DetecteCadre()
	{
		$this->ValeurParamCadre = $this->ValeurDefautParamCadre ;
		if(isset($_GET[$this->IDInstanceCalc.'_'.$this->NomParamCadre]))
		{
			$valBrute = $_GET[$this->IDInstanceCalc.'_'.$this->NomParamCadre] ;
			if($valBrute == $this->ValeurOuvreCadre)
			{
				$this->ValeurParamCadre = $this->ValeurOuvreCadre ;
			}
		}
		return $this->ValeurParamCadre == $this->ValeurOuvreCadre ;
	}
	protected function RenduCadre()
	{
		$ctn = '' ;
		$ctn .= $this->RenduEnteteDocCadre() ;
		$ctn .= $this->RenduCorpsDocCadre() ;
		$ctn .= $this->RenduPiedDocCadre() ;
		return $ctn ;
	}
	protected function RenduCorpsDocCadre()
	{
		$ctn = '' ;
		$this->InitFournisseurDonnees() ;
		if(! $this->EstNul($this->FournisseurDonnees))
		{
			$this->ChargeConfigFournisseurDonnees() ;
			$this->CalculeElementsRendu() ;
			$ctn .= $this->RenduListeElements() ;
			$ctn .= $this->RenduBtnsExec() ;
		}
		else
		{
			die("Le composant ".$this->IDInstanceCalc." nécessite un fournisseur de données.") ;
		}				
		return $ctn ;
	}
	protected function RenduBtnsExec()
	{
		$ctn = '' ;
		$ctn .= '<script language="javascript">
function annuleSelect() {
window.close() ;
}
function valideSelect() {
var cibleFenetre = '.(($this->StyleIncorporation == "POPUP") ? 'window.opener' : 'window.parent').' ;
var valeurChoisie = "" ;
var libelleChoisi = "" ;
// alert(cibleFenetre.document.getElementById("'.$this->IDInstanceCalc.'")) ;
// alert(document.getElementById("'.$this->IDInstanceCalc.'")) ;
var lstElems = document.getElementsByName("'.htmlentities($this->NomElementHtml).'") ;
for(var i=0; i<lstElems.length; i++)
{
	var elem = lstElems[i] ;
	if(elem.checked){
		valeurChoisie = elem.value ;
		libelleChoisi = elem.title ;
		break ;
	}
}
cibleFenetre.document.getElementById("'.$this->IDInstanceCalc.'").value = valeurChoisie ;
cibleFenetre.document.getElementById("'.$this->IDInstanceCalc.'_Libelle").firstChild.data = libelleChoisi ;
window.close() ;
}
</script>'.PHP_EOL ;
		$ctn .= '<div class="Bloc_Commandes">';
		if($this->StyleIncorporation == "POPUP")
		{
			$ctn .= '<input type="button" onclick="annuleSelect()" value="'.htmlentities($this->LibelleAnnuleSelection).'" />' ;
			$ctn .= '&nbsp;&nbsp;&nbsp;&nbsp;' ;
		}
		$ctn .= '<input type="button" onclick="valideSelect()" value="'.htmlentities($this->LibelleValideSelection).'" />' ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
	protected function RenduOptionElement($valeur, $libelle, $ligne, $position=0)
	{
		$forcerSelection = 0 ;
		if($position == 1 && $this->Valeur == "" && $this->CocherAutoPremiereOption)
		{
			$forcerSelection = 1 ;
		}
		$ctn = '' ;
		$nomElementHtml = $this->NomElementHtml ;
		$ctn .= '<input type="radio" name="'.$nomElementHtml.'" id="'.$this->IDInstanceCalc.'_'.$position.'"' ;
		$ctn .= ' value="'.htmlentities($valeur).'"' ;
		$ctn .= ' title="'.htmlentities($libelle).'"' ;
		if($this->EstValeurSelectionnee($valeur) || $forcerSelection)
		{
			$ctn .= ' checked' ;
		}
		$ctn .= ' />' ;
		return $ctn ;
	}
	protected function RenduLibelleElement($valeur, $libelle, $ligne, $position=0)
	{
		$ctn = '<label for="'.$this->IDInstanceCalc.'_'.$position.'">'.htmlentities($libelle).'</label>' ;
		return $ctn ;
	}
	protected function AfficheCadre()
	{
		if(! $this->DetecteCadre())
			return 0 ;
		$ctn = $this->RenduCadre() ;
		echo $ctn ;
		exit ;
	}
	protected function RenduDispositifBrut()
	{
		// echo $this->StyleIncorporation ;
		$this->AfficheCadre() ;
		$url = \Pv\Misc::get_current_url() ;
		$this->CorrigeIDsElementHtml() ;
		$ctn = '' ;
		$ctn .= '<div class="Conteneur'.$this->IDInstanceCalc.'"><input type="hidden" name="'.$this->NomElementHtml.'" id="'.$this->IDInstanceCalc.'" value="'.htmlentities($this->Valeur).'" />'.$this->RenduValeurCadre().'</div>' ;
		$urlCadre = \Pv\Misc::update_url_params($url, array($this->IDInstanceCalc.'_'.$this->NomParamCadre => 1)) ;
		switch(strtoupper($this->StyleIncorporation))
		{
			case "POPUP" :
			{
				$ctn .= '<div><a href="'.$urlCadre.'" target="popup'.$this->IDInstanceCalc.'">'.$this->LibelleOuvreCadre.'</a></div>' ;
			}
			break ;
			case "IFRAME" :
			case "CADRE" :
			{
				$ctn .= '<iframe src="'.$urlCadre.'" width="'.$this->LargeurCadre.'" frameborder="0" height="'.$this->HauteurCadre.'"></iframe>' ;
			}
			break ;
		}
		return $ctn ;
	}
}