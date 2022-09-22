<?php

namespace Rpa2p\ZonePrinc\Script ;

class Clients extends \Rpa2p\ZonePrinc\Script\Script
{
	public $TitreDocument = "Présentation des clients" ;
	public $Titre = "Autres clients" ;
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= '<p>Ces clients nous ont fait confiance :</p>' ;
		$ctn .= '<ul>
			<li>Client 1</li>
			<li>Client 2</li>
			<li>Client 3</li>
		</ul>' ;
		return $ctn ;
	}
}
