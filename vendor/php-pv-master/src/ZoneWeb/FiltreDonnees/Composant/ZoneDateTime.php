<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneDateTime extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneEntree
{
	public $TypeEditeur = "input_datetime_html" ;
	public $TypeElementFormulaire = "datetime-local" ;
	public $UseValeurActuelleParDefaut = true ;
	protected function CorrigeValeur()
	{
		if($this->Valeur == "" && $this->UseValeurActuelleParDefaut)
		{
			$this->Valeur = date("Y-m-d")."T".date("H:i:s") ;
		}
		elseif($this->Valeur != "")
		{
			if(preg_match('/^\d+\-\d+\-\d+/$', $this->Valeur))
			{
				$this->Valeur .= "T00:00:00" ; 
			}
			elseif(preg_match('/^\d+\-\d+\-\d+ \d+\:\d+.+/$', $this->Valeur))
			{
				$this->Valeur = str_replace(" ", "T", $this->Valeur) ;
			}
		}
	}
}