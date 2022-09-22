<?php

namespace Pv\ZoneWeb\Commande ;

class FmtCsvImportElement extends \Pv\ZoneWeb\Commande\FmtFichImportElement
{
	public $Extensions = array('csv', 'txt') ;
	public $SeparateurLigne = "\r\n" ;
	public $SeparateurColonne = ";" ;
	public $SupportFichier = false ;
	public function Ouvre($cheminFichier)
	{
		$this->SupportFichier = fopen($cheminFichier, "r") ;
		return ($this->SupportFichier !== false) ;
	}
	public function LitEntete()
	{
		$ligne = fgets($this->SupportFichier) ;
		return explode($this->SeparateurColonne, $ligne) ;
	}
	public function LitLigne()
	{
		$ligne = fgets($this->SupportFichier) ;
		return explode($this->SeparateurColonne, $ligne) ;
	}
	public function Ferme()
	{
		return fclose($this->SupportFichier) ;
	}
}