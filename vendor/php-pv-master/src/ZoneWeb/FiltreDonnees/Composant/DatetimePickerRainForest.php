<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class DatetimePickerRainForest extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	protected static $SourceIncluse = 0 ;
    public static $CheminFichierJs = "js/datetimepicker_css.js" ;
    public $CheminRepImgs = "images" ;
    public $DescriptifPopup = 'Afficher le calendrier' ;
	public $FormatDatePHP = "d-m-Y H:i:s" ;
	public $FormatDateJs = "ddMMyyyy" ;
    protected function RenduSourceIncluse()
	{
        $ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus(\Pv\ZoneWeb\FiltreDonnees\Composant\DatetimePickerRainForest::$CheminFichierJs) ;
		$ctn .= $this->ZoneParent->RenduContenuJsInclus('function fixeValeur'.$this->IDInstanceCalc.'() {
document.getElementById("'.$this->IDInstanceCalc.'").value = Cal.Year + "-" + Cal.Month + "-" + Cal.Date + " " + Cal.Hours + "-" + Cal.Minutes + "-" + Cal.Seconds ;
}') ;
        return $ctn ;
    }
    protected function RenduEditeurBrut()
	{
        $ctn = '' ;
		if($this->Valeur == "")
		{
			$this->Valeur = date($this->FormatDatePHP) ;
		}
		else
		{
			$this->Valeur = date($this->FormatDatePHP, strtotime($this->Valeur)) ;
		}
		$valeurEnc = ($this->Valeur != "") ? htmlspecialchars($this->Valeur) : "" ;
        $ctn .= '<input type="text" id="'.$this->IDInstanceCalc.'_Support" value="'.$valeurEnc.'" onchange="fixeValeur'.$this->IDInstanceCalc.'()" />' ;
        $ctn .= '<input type="hidden" id="'.$this->IDInstanceCalc.'" name="'.htmlspecialchars($this->NomElementHtml).'" value="'.$valeurEnc.'" />' ;
        $ctn .= '
<a href="javascript:NewCssCal(\''.$this->IDInstanceCalc.'_Support\',\''.$this->FormatDateJs.'\', \'dropdown\', true, \'24\', true)"><img src="'.$this->CheminRepImgs.'/cal.gif" border="0" alt="'.htmlspecialchars($this->DescriptifPopup).'"></a>' ;
        return $ctn ;
    }
}