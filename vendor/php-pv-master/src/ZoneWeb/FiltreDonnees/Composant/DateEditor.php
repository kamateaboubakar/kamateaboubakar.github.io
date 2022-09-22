<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class DateEditor extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $ValeurJour = "" ;
	public $ValeurMois = "" ;
	public $ValeurAnnee = "" ;
	public $InfoJour = "Jour" ;
	public $InfoMois = "Mois" ;
	public $InfoAnnee = "Ann&eacute;e" ;
	public $SeparateurPartie = " / " ;
	public $DispositionComposants = array(1, 2, 3) ;
	public $FormatValeur = '${2}-${1}-${0}' ;
	protected $PortionsValeur = array() ;
	protected function NomFonctionRafraichitValeur()
	{
		return 'RafraichitValeur'.$this->IDInstanceCalc ;
	}
	protected function ContenuJsRafraichitValeur()
	{
		$ctn = '' ;
		$ctn .= '<script type="text/javascript">
function '.$this->NomFonctionRafraichitValeur().'()
{
var formatRetenu = '.svc_json_encode($this->FormatValeur).' ;
var resultat = formatRetenu ;
for(var i=0; i<'.count($this->DispositionComposants).'; i++)
{
	var indice = i.toString() ;
	var bloc = document.getElementById("'.$this->IDInstanceCalc.'_Partie" + indice) ;
	var valeurBloc = "" ;
	if(bloc.value != null)
	{
		try { valeurBloc = (bloc.value == "" || isNaN(bloc.value) == true) ? 1 : bloc.value ; } catch(ex) { }
	}
	resultat = resultat.split("${" + indice + "}").join(valeurBloc) ;
}
document.getElementsByName('.svc_json_encode($this->NomElementHtml).')[0].value = resultat ;
// alert(resultat) ;
}
</script>' ;
		return $ctn ;
	}
	protected function ExtraitPortionsValeur()
	{
		foreach($this->DispositionComposants as $i => $type)
		{
			$this->PortionsValeur[$i] = 1 ;
			if(preg_match('/\$\{'.$i.'\}/', $this->Valeur, $match))
			{
				$this->PortionsValeur[$i] = $match[0] ;
			}
		}
	}
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		// $this->ExtraitPortionsValeur() ;
		$nomFonction = $this->NomFonctionRafraichitValeur() ;
		$ctn = '' ;
		$ctn .= '<input type="hidden" name="'.$this->NomElementHtml.'" id="'.$this->IDInstanceCalc.'" />'.PHP_EOL ;
		foreach($this->DispositionComposants as $i => $id)
		{
			if($i > 0)
			{
				$ctn .= $this->SeparateurPartie ;
			}
			switch($id)
			{
				case \Pv\ZoneWeb\FiltreDonnees\Composant\DispositionZoneDate::Jour :
				{
					$ctn .= '<input type="text" id="'.$this->IDInstanceCalc.'_Partie'.$i.'" value="'.htmlentities($this->PortionsValeur[$i]).'" title="'.$this->InfoJour.'" size="2" maxlength="2" onchange="'.$nomFonction.'(this)" />' ;
				}
				break ;
				case \Pv\ZoneWeb\FiltreDonnees\Composant\DispositionZoneDate::Mois :
				{
					$ctn .= '<input type="text" id="'.$this->IDInstanceCalc.'_Partie'.$i.'" maxlength="2" size="2" value="'.htmlentities($this->PortionsValeur[$i]).'" title="'.$this->InfoMois.'" onchange="'.$nomFonction.'(this)" />' ;
				}
				break ;
				case \Pv\ZoneWeb\FiltreDonnees\Composant\DispositionZoneDate::Annee :
				{
					$ctn .= '<input type="text" size="4" maxlength="4" id="'.$this->IDInstanceCalc.'_Partie'.$i.'" value="'.htmlentities($this->PortionsValeur[$i]).'" title="'.$this->InfoAnnee.'" onchange="'.$nomFonction.'(this)" />';
				}
				break ;
			}
		}
		$ctn .= $this->ContenuJsRafraichitValeur() ;
		return $ctn ;
	}
}