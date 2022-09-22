<?php

namespace Pv\ZoneBootstrap\FormulaireDonnees ;

class FormulaireDonnees extends \Pv\ZoneWeb\FormulaireDonnees\FormulaireDonnees
{
	public $UtiliserLargeur = 0 ;
	public $ClasseCSSSucces = "alert alert-primary" ;
	public $ClasseCSSErreur = "alert alert-danger" ;
	public $ClasseCSSCommandeExecuter = "btn-primary" ;
	public $ClasseCSSCommandeAnnuler = "btn-danger" ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->DessinateurFiltresEdition = new \Pv\ZoneBootstrap\DessinFiltres\DessinFiltres() ;
		$this->DessinateurBlocCommandes = new \Pv\ZoneBootstrap\DessinCommandes\DessinCommandes() ;
	}
	protected function RenduComposants()
	{
		$ctn = '' ;
		if(count($this->DispositionComposants))
		{
			$ctn .= '<form class="FormulaireDonnees'.(($this->NomClasseCSS != '') ? ' '.$this->NomClasseCSS : '').'" method="post" enctype="multipart/form-data" onsubmit="return SoumetFormulaire'.$this->IDInstanceCalc.'(this)" role="form">'.PHP_EOL ;
			foreach($this->DispositionComposants as $i => $id)
			{
				if($i > 0)
				{
					$ctn .= PHP_EOL ;
				}
				switch($id)
				{
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::BlocEntete :
					{
						$ctn .= $this->RenduBlocEntete() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::FormulaireFiltresEdition :
					{
						$ctn .= $this->RenduFormulaireFiltres() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::ResultatCommandeExecutee :
					{
						$ctn .= $this->RenduResultatCommandeExecutee() ;
					}
					break ;
					case \Pv\ZoneWeb\FormulaireDonnees\Disposition::BlocCommandes :
					{
						$ctn .= $this->RenduBlocCommandes() ;
					}
					break ;
					default :
					{
						$ctn .= $this->RenduAutreComposantSupport($id) ;
					}
					break ;
				}
			}
			$ctn .= '</form>' ;
		}
		return $ctn ;
	}
	public function DessineFiltresScriptParent()
	{
		$this->DessinateurFiltresEdition = new \Pv\ZoneBootstrap\DessinFiltres\AppliqueScriptParent() ;
	}
	public function DessineCommandesScriptParent()
	{
		$this->DessinateurFiltresEdition = new \Pv\ZoneBootstrap\DessinCommandes\AppliqueScriptParent() ;
	}
}
