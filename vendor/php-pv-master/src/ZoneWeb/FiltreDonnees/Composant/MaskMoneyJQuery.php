<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class MaskMoneyJQuery extends \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneInvisible
{
	public static $SourceIncluse = 0 ;
	public $Config ;
	protected $ValeurEditeur ;
	public $CheminJs = "js/jquery.maskMoney.js" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Config = new \Pv\ZoneWeb\FiltreDonnees\Composant\CfgMaskMoneyJQuery() ;
	}
	public function InclutLibSource()
	{
		$ctn = '' ;
		if($this->ObtientValeurStatique('SourceIncluse') == 1)
		{
			return $ctn ;
		}
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
		$this->AffecteValeurStatique("SourceIncluse", 1) ;
		return $ctn ;
	}
	protected function PrepareEditeur()
	{
		$this->ValeurEditeur = $this->Valeur ;
		if($this->Config->precision > 0 && intval($this->Valeur) != $this->Valeur)
		{
			$this->ValeurEditeur .= ".".str_repeat("0", $this->Config->precision) ;
		}
	}
	protected function RenduEditeur()
	{
		$ctn = '' ;
		$this->PrepareEditeur() ;
		$ctn .= '<input id="Editeur_'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' value="'.htmlentities($this->ValeurEditeur).'"' ;
		$ctn .= ' type="text"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= ' />' ;
		return $ctn ;
	}
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->InclutLibSource() ;
		$ctn .= $this->RenduEditeur() ;
		$ctn .= parent::RenduDispositifBrut() ;
		$ctn .= $this->ZoneParent->RenduContenuJsInclus('jQuery(function () {
jQuery("#Editeur_'.$this->IDInstanceCalc.'").maskMoney('.svc_json_encode($this->Config).')
.change(function () {
	if(jQuery(this).val() == "")
	{
		jQuery("#'.$this->IDInstanceCalc.'").val(jQuery(this).val()) ;
		return ;
	}
	var val = jQuery(this).maskMoney("unmasked") ;
	if(val[0] != undefined)
		val = val[0] ;'.(($this->Config->precision == 0) ? '
	alert(Math.pow(10, ((String(val).length > 2) ? 3 : String(val).length - 1))) ;
	val = val * Math.pow(10, ((String(val).length > 2) ? 3 : String(val).length - 1)) ;' : '').'
	jQuery("#'.$this->IDInstanceCalc.'").val(val) ;
})
.maskMoney("mask") ;
}) ;') ;
		return $ctn ;
	}
}
class PvConfigPriceFormatJQuery
{
	public $prefix = "" ;
	public $suffix = "" ;
    public $centsSeparator = "." ;
	public $thousandsSeparator = " " ;
	public $limit = "" ;
	public $centsLimit = 3 ;
	public $clearPrefix = false ;
	public $allowNegative = false ;
	public $insertPlusSign = false ;
}