<?php

namespace Pv\ZoneWeb\TableauDonnees\Commande ;

class Export extends \Pv\ZoneWeb\TableauDonnees\Commande\Commande
{
	public $NomFichier = "" ;
	public $TypeContenu = "application/octet-stream" ;
	public $TransfertBinaire = 1 ;
	public $InclureEntete = 1 ;
	public $RenseignerEntetesRequete = 1 ; 
	public $ValeurVideExport = "" ;
	protected function ExecuteInstructions()
	{
		if($this->RenseignerEntetesRequete)
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			if($this->NomFichier != "")
			{
				header("Content-Disposition: attachment; filename=\"".$this->NomFichier."\";");
			}
			if($this->TransfertBinaire == 1)
			{
				header("Content-Transfer-Encoding: binary");
			}
			Header("Content-type: ".$this->TypeContenu) ;
		}
		$this->EnvoieContenu() ;
		exit ;
	}
	protected function EnvoieContenu()
	{
	}
}