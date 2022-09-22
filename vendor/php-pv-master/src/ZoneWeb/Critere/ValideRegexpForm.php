<?php

namespace Pv\ZoneWeb\Critere ;

class ValideRegexpForm extends \Pv\ZoneWeb\Critere\Critere
{
	public $FormatMessageErreur = 'Le champ ${libelleFiltre} a un format invalide' ;
	public $TypesFormatRegexp = array(
		'titre_court' => '.{0,75}',
		'titre' => '.{0,150}',
		'titre_alpha' => '[^0-9]{0,150}',
		'adresse' => '.{0,65}',
		'ville' => '[^0-9]{0,75}',
		'pays' => '[^0-9]{0,75}',
		'paragraphe' => '.*',
		'paragraphe_alpha' => '[^0-9]*',
		'telephone' => '(\+?[0-9 \-]{0,16}[ ,;]{0,1})*',
		'nom_personne' => '[a-zA-Z]{0,20}([^a-zA-Z0-9]{1,2}[a-zA-Z]{1,20}){0,5}',
		'prenom_personne' => '[a-zA-Z]{0,20}([^a-zA-Z0-9]{1,2}[a-zA-Z]{1,20}){0,9}',
		'code_postal' => '[a-zA-Z0-9]{0,20}([^a-zA-Z0-9]{1,2}[a-zA-Z0-9]{1,20}){0,9}',
	) ;
	protected function & ObtientFiltresCibles()
	{
		return $this->FormulaireDonneesParent->FiltresEdition ;
	}
	public function EstRespecte()
	{
		$this->MessageErreur = "" ;
		$filtresCibles = $this->ObtientFiltresCibles() ;
		if(! is_array($filtresCibles) || count($filtresCibles) == 0)
		{
			return 1 ;
		}
		$nomsFiltres = array_keys($filtresCibles) ;
		foreach($nomsFiltres as $i => $nomFiltre)
		{
			$filtre = & $filtresCibles[$nomFiltre] ;
			if($filtre->Role != "get" && $filtre->Role != "post")
			{
				continue ;
			}
			$formatRegexp = '' ;
			if($filtre->TypeFormatRegexp != '')
			{
				if(isset($this->TypesFormatRegexp[$filtre->TypeFormatRegexp]))
				{
					$formatRegexp = $this->TypesFormatRegexp[$filtre->TypeFormatRegexp] ;
				}
				else
				{
					die("Type de format inconnu pour ".$filtre->NomParametreDonnees." : ".$filtre->TypeFormatRegexp) ;
				}
			}
			else
			{
				$formatRegexp = $filtre->FormatRegexp ;
			}
			if($formatRegexp == "")
			{
				continue ;
			}
			// print $filtre->NomParametreDonnees." : ".$formatRegexp."<br>" ;
			$valeur = $filtre->Lie() ;
			if(! preg_match('/^'.$formatRegexp.'$/', $valeur))
			{
				$this->MessageErreur = ($filtre->MessageErreurRegexp == "") ? \Pv\Misc::_parse_pattern($this->FormatMessageErreur, array("libelleFiltre" => $filtre->ObtientLibelle())) : $filtre->MessageErreurRegexp ;
				break ;
			}
		}
		// exit ;
		return ($this->MessageErreur == '') ? 1 : 0 ;
	}

}