<?php

namespace Rpa2p\ZonePrinc\Script ;

class APropos extends Script
{
	public $NomDocumentWeb = "modal" ;
	public $NecessiteMembreConnecte = false ;
	public $TitreDocument = "A Propos" ;
	public $Titre = "A Propos" ;
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= '<div align="center">' ;
		$ctn .= '<p style = "color: green">'.\Rpa2p\Config\Application::NOM.'<br>  v'.\Rpa2p\Config\Application::VERSION.'</p>' ;
		$ctn .= '<br>' ;
		$ctn .= '<p>Soluci RPA Manager</p>' ;
		$ctn .= '<p>Copyright@2022 (SNT) Soluci Nouvelle Technologie</p>' ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}
