<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneCommonCaptcha extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneTexte
{
	public $NomImg = "verify" ;
	public $LargeurImg = 115 ;
	public $HauteurImg = 32 ;
	public $CasseInsensibleImg = 1 ;
	public $NomActionAffichImg ;
	public $ActionAffichImg ;
	public $NomParamsAction = array() ;
	public function AdopteZone($nom, & $zone)
	{
		parent::AdopteZone($nom, $zone) ;
		$this->NomActionAffichImg = $this->IDInstanceCalc.'_AffichImg' ;
		$this->ActionAffichImg = new \Pv\ZoneWeb\FiltreDonnees\Composant\ActImgCommonCaptcha() ;
		$this->InscritActionAvantRendu($this->NomActionAffichImg, $this->ActionAffichImg) ;
	}
	protected function RenduDispositifBrut()
	{
		if(count($this->NomParamsAction) > 0)
		{
			$this->ActionAffichImg->Params = \Pv\Misc::array_extract_value_for_keys($_GET, $this->NomParamsAction) ;
		}
		$ctn = '' ;
		$ctn .= '<table cellspacing="0" cellpadding="0"><tr><td>' ;
		$ctn .= parent::RenduDispositifBrut() ;
		$ctn .= '</td><td>&nbsp;</td><td>' ;
		$ctn .= '<img src="'.$this->ActionAffichImg->ObtientUrl().'" />' ;
		$ctn .= '</td></tr></table>' ;
		return $ctn ;
	}
	public function VerifieValeurSoumise($texte)
	{
		return $this->ActionAffichImg->VerifieValeurSoumise($texte) ;
	}
}