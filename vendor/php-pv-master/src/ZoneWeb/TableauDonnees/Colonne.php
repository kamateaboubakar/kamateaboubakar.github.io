<?php

namespace Pv\ZoneWeb\TableauDonnees ;

class Colonne extends \Pv\Objet\Objet
{
	public $TriPrealable = 0 ;
	public $OrientationTri = "asc" ;
	public $NomDonneesTri = "" ;
	public $AliasDonneesTri = "" ;
	public $AlignElement ;
	public $AlignVElement = "top" ;
	public $HauteurElement ;
	public $Largeur ;
	public $HauteurEntete ;
	public $AlignEntete ;
	public $AlignVEntete = "top" ;
	public $NomDonnees ;
	public $AliasDonnees ;
	public $Libelle ;
	public $Formatteur ;
	public $CorrecteurValeur ;
	public $TriPossible = 1 ;
	public $EncodeHtmlValeur = 1 ;
	public $ExtracteurValeur ;
	public $PrefixeValeursExtraites = "" ;
	public $Visible = 1 ;
	public $ExporterDonnees = 1 ;
	public $ExporterDonneesObligatoire = 0 ;
	public $FormatValeur ;
	public $StyleCSS ;
	public $NomClasseCSS ;
	public $RenvoyerValeurVide = 1 ;
	public $ValeurVide = "&nbsp;" ;
	public function EstVisible(& $zone)
	{
		$ok = $this->Visible == 1 ;
		if($ok && $this->EstPasNul($this->Formatteur))
		{
			$ok = $this->Formatteur->EstAccessible($zone, $this) ;
		}
		return $ok ;
	}
	public function DeclareFormatteurLiens()
	{
		$this->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Liens() ;
	}
	public function DeclareFormatteurBool()
	{
		$this->Formatteur = new \Pv\ZoneWeb\TableauDonnees\FormatColonne\Bool() ;
	}
	public function ObtientPrefixeValsExtraites()
	{
		$prefixe = $this->PrefixeValeursExtraites ;
		if($prefixe == '')
		{
			$prefixe = $this->NomDonnees ;
		}
		return $prefixe ;
	}
	public function PeutExporterDonnees()
	{
		return (($this->Visible == 1 && $this->ExporterDonnees == 1) || $this->ExporterDonneesObligatoire) ? 1 : 0 ;
	}
	public function ObtientLibelle()
	{
		$libelle = $this->NomDonnees ;
		if($this->Libelle != "")
		{
			$libelle = $this->Libelle ;
		}
		return $libelle ;
	}
	public function FormatteValeur(& $composant, $ligne)
	{
		$val = null ;
		if($this->EstNul($this->Formatteur))
		{
			$val = $this->FormatteValeurInt($composant, $ligne) ;
		}
		else
		{
			$val = $this->Formatteur->Encode($composant, $this, $ligne) ;
		}
		if($this->EstPasNul($this->CorrecteurValeur))
		{
			$val = $this->CorrecteurValeur->AppliquePourColonne($val, $this) ;
		}
		return $val ;
	}
	public function InstrsJsPrepareRendu()
	{
		$ctn = '' ;
		if(! $this->EstNul($this->Formatteur))
		{
			$ctn = $this->Formatteur->InstrsJsPrepareRendu($composant, $this) ;
		}
		return $ctn ;
	}
	public function InstrsJsFormatteValeur(& $composant)
	{
		$ctn = '' ;
		if($this->EstNul($this->Formatteur))
		{
			$ctn = $this->InstrsJsFormatteValeurInt($composant) ;
		}
		else
		{
			$ctn = $this->Formatteur->InstrsJsEncode($composant, $this) ;
		}
		return $ctn ;
	}
	protected function FormatteValeurInt(& $composant, $ligne)
	{
		$val = "" ;
		if($this->NomDonnees != '')
			$val = $this->EncodeValeur($ligne[$this->NomDonnees]) ;
		if($val == "" && $this->RenvoyerValeurVide)
			return $this->ValeurVide ;
		if($this->FormatValeur != '')
		{
			$val = str_ireplace(array('${self}', '${luimeme}', '${soi}'), $val, $this->FormatValeur) ;
		}
		return $val ;
	}
	protected function InstrsJsFormatteValeurInt(& $composant)
	{
		$ctn = '' ;
		if($this->NomDonnees == '')
		{
			return '' ;
		}
		$nomDonnees = svc_json_encode($this->NomDonnees) ;
		$ctn .= 'var val = "" ;
if(donnees['.$nomDonnees.'] !== undefined) {
val = donnees['.$nomDonnees.'] ;
}'.PHP_EOL ;
		if($this->FormatValeur != '')
		{
			$ctn .= 'var formatVal = '.svc_json_decode($this->FormatValeur).' ;
var tagsSelf = ["${self}", "${luimeme}", "${soi}"] ;
for(var n in tagsSelf) {
formatVal = formatVal.split(tagsSelf[i]).join(val) ;
}'.PHP_EOL ;
		}
		$ctn .= 'noeudCellule.innerHTML = val ;' ;
		return $ctn ;
	}
	public function EncodeValeur($valeur)
	{
		if(empty($valeur)) {
			return $valeur ;
		}
		$resultat = ($this->EncodeHtmlValeur) ? htmlentities($valeur) : $valeur ;
		return $resultat ;
	}
	public function EstEditable()
	{
		$ok = 1 ;
		if($this->EstNul($this->Formatteur))
		{
			return 0 ;
		}
		return $this->Formatteur->EstEditable() ;
	}
}