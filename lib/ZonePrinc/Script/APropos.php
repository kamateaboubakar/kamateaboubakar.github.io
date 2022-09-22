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
		$ctn .= '<a href="?">
		<img src="images/logosoluci.png" height="120" href="?"/></a>' ;
		$ctn .= '<br>' ;
		$ctn .= '<p style = "color: gray">'.\Rpa2p\Config\Application::NOM.'<br>  v'.\Rpa2p\Config\Application::VERSION.'</p>' ;
		$ctn .= '<p>Copyright@2022 (SNT) Soluci Nouvelle Technologie / <a href="soluci.com" target="blanck">soluci.com</a> / support@soluci.com</p> ' ;
		$ctn .= '<p> +225 07 88 33 95 18 </p>' ;
		$ctn .= '</div>' ;
		return $ctn ;
	}
}
