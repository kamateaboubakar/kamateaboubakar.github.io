<?php

namespace Pv\ZoneWeb\Commande ;

class OuvrePopup extends \Pv\ZoneWeb\Commande\RedirectionHttp
{
	public $NomFenetre = "" ;
	public $CoinGaucheEcran = "" ;
	public $CoinHautEcran = "" ;
	public $LargeurPopup = "" ;
	public $HauteurPopup = "" ;
	public $LargeurIntern = "" ;
	public $HauteurIntern = "" ;
	public $BarreAdrUrl = "" ;
	public $BarreDefil = "" ;
	public $BarreStatut = "" ;
	public $BarreOutils = "" ;
	public $BarreMenus = "" ;
	public $Dependant = "" ;
	public $CoinGauche = "" ;
	public $CoinHaut = "" ;
	public $RaccourcisClavier = "" ;
	public $Redimens = "" ;
	protected function ObtientNomFenetre()
	{
		$nomFenetre = $this->NomFenetre ;
		if($nomFenetre == "")
		{
			$nomFenetre = $this->IDInstanceCalc ;
		}
		return $nomFenetre ;
	}
	public function ObtientParamsOuverture()
	{
		$params = array() ;
		if($this->LargeurPopup != "")
			$params["width"] = $this->LargeurPopup ;
		if($this->HauteurPopup != "")
			$params["height"] = $this->HauteurPopup ;
		if($this->LargeurIntern != "")
			$params["innerWidth"] = $this->LargeurIntern ;
		if($this->HauteurIntern != "")
			$params["innerHeight"] = $this->HauteurIntern ;
		if($this->BarreAdrUrl != "")
			$params["location"] = $this->BarreAdrUrl ;
		if($this->BarreDefil != "")
			$params["scrollbars"] = $this->BarreDefil ;
		if($this->BarreStatut != "")
			$params["status"] = $this->BarreStatut ;
		if($this->BarreOutils != "")
			$params["toolbar"] = $this->BarreOutils ;
		if($this->BarreMenus != "")
			$params["menubar"] = $this->BarreMenus ;
		if($this->Redimens != "")
			$params["resizable"] = $this->Redimens ;
		if($this->CoinGauche != "")
			$params["left"] = $this->CoinGauche ;
		if($this->CoinHaut != "")
			$params["top"] = $this->CoinHaut ;
		if($this->CoinGaucheEcran != "")
			$params["screenX"] = $this->CoinGaucheEcran ;
		if($this->CoinHautEcran != "")
			$params["screenY"] = $this->CoinHautEcran ;
		if($this->LargeurPopup != "")
			$params["width"] = $this->LargeurPopup ;
		if($this->HauteurPopup != "")
			$params["height"] = $this->HauteurPopup ;
		if($this->LargeurIntern != "")
			$params["innerWidth"] = $this->LargeurIntern ;
		if($this->HauteurIntern != "")
			$params["innerHeight"] = $this->HauteurIntern ;
		return $params ;
	}
	protected function ExecuteInstructions()
	{
	}
}