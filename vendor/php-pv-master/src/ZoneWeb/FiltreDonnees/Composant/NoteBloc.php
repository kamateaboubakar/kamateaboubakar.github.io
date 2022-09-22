<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class NoteBloc extends \Pv\ZoneWeb\FiltreDonnees\Composant\EditeurHtml
{
	public $CheminFichierJs = "js/noteBloc.js" ;
	public $ValeurMin = 1 ;
	public $ValeurMax = 5 ;
	protected static $SourceIncluse = 0;
	public function DefinitRangee($min, $max)
	{
		$this->ValeurMin = $min ;
		$this->ValeurMax = $max ;
	}
	protected function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminFichierJs) ;
		return $ctn ;
	}
	protected function RenduEditeurBrut()
	{
		$ctn = '' ;
		$ctn .= '<script>drawNoteBloc('.svc_json_encode($this->NomElementHtml).', '.svc_json_encode($this->ValeurMin).', '.svc_json_encode($this->ValeurMax).', '.(($this->Modifiable) ? 'true' : 'false').') ;</script>' ;
		return $ctn ;
	}
}