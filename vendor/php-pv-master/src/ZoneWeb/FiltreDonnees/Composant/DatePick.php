<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class DatePick extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	protected static $SourceIncluse = 0 ;
    public static $CheminFichierJs = "js/ts_picker.js" ;
    public $CheminRepImgs = "images" ;
    public $DescriptifPopup = 'Afficher le calendrier' ;
    public $LibellesMois = array() ;
    public $LibellesJour = array() ;
    protected function RenduSourceIncluse() {
        $ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus(\Pv\ZoneWeb\FiltreDonnees\Composant\DatePick::$CheminFichierJs) ;
        $ctn .= $this->ZoneParent->RenduContenuJsInclus('ts_picker_arr_months = '.  svc_json_encode($this->LibellesMois).' ;
ts_picker_week_days = '.  svc_json_encode($this->LibellesMois)) ;
        return $ctn ;
    }
    protected function RenduEditeurBrut() {
        $ctn = '' ;
        $ctn .= '<input type="text" id="'.$this->IDInstanceCalc.'" name="'.htmlentities($this->NomElementHtml).'" value="'.htmlentities($this->Valeur).'" />';
        $ctn .= '
<a href="javascript:show_calendar(\''.$this->IDInstanceCalc.'\', document.getElementById(&quot;'.$this->IDInstanceCalc.'&quot;).value, '.  svc_json_encode_attr($this->CheminRepImgs).') ;"><img src="'.$this->CheminRepImgs.'/cal.gif" width="16" height="16" border="0" alt="'.htmlentities($this->DescriptifPopup).'"></a>' ;
        return $ctn ;
    }
}