<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class CalendarDateInput extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $Format = "YYYY-MM-DD" ;
	public $Necessaire = 1 ;
	public static $SourceInclus = 0 ;
	public static $CheminSource = "js/calendarDateInput.js" ;
	public function CorrigeValeur()
	{
		if($this->Valeur == "")
		{
			$this->Valeur = date("Y-m-d") ;
		}
	}
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$this->CorrigeValeur() ;
		$ctn = '' ;
		$ctn .= $this->InclutSource() ;
		$ctn .= '<script type="text/javascript">
DateInput('.svc_json_encode($this->NomElementHtml).', '.(($this->Necessaire == 1) ? 'true' : 'false').', '.svc_json_encode($this->Format).', '.svc_json_encode(htmlentities($this->Valeur)).', '.svc_json_encode($this->IDInstanceCalc).') ;
</script>' ;
		return $ctn ;
	}
}