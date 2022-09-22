<?php

namespace Pv\ZoneWeb\Action ;

class TelechargeFichier extends \Pv\ZoneWeb\Action\EnvoiFichier
{
	public $TypeMime = "application/octet-stream" ;
	public $AutresEntetes = array("Pragma: public", "Expires: 0", "Cache-Control: must-revalidate, post-check=0, pre-check=0", "Content-Transfer-Encoding: binary") ;
	public $DispositionFichierAttache = "attachment" ;
}