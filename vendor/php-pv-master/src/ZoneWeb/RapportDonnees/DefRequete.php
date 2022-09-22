<?php

namespace Pv\ZoneWeb\RapportDonnees ;

class DefRequete
{
	public $Colonnes ;
	public $Requete ;
	public $Condition ;
	public $Groupage ;
	public $Tri ;
	public function CommeSql()
	{
		$sql = "" ;
		$sql .= 'select ' ;
		$sql .= ($this->Colonnes != "") ? $this->Colonnes : "*" ;
		$sql .= ' from '.$this->Requete ;
		if($this->Condition != "")
			$sql .= ' where '.$this->Condition ;
		if($this->Tri != "")
			$sql .= ' order by '.$this->Tri ;
		if($this->Groupage != "")
			$sql .= ' group by '.$this->Groupage ;
		return $sql ;
	}
}