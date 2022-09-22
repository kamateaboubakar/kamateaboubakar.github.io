<?php

namespace Rpa2p\ZonePrinc\Script\Job\Activite ;

class ChoixType extends \Rpa2p\ZonePrinc\Script\Job\ModPart
{
	public $TitreDocument = "Choix du Type d'activité" ;
	public $Titre = "Choisissez le Type d'activité" ;
	protected function RenduBlocTypesActivit()
	{
		$ctn = '' ;
		$ctn .= '<div class="card">
<div class="card-body">'.PHP_EOL ;
		$ctn .= '<div class="row">'.PHP_EOL ;
		foreach($this->ApplicationParent->TypesActiviteJob as $n => $typeActivit)
		{
			$ctn .= '<div class="col-sm-6 col-md-3 col-lg-2 text-center align-text-bottom p-2">
<a class="text-black" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutActivite&id_job='.$this->ParamId.'&type_activite='.urlencode($n).'\') ;">
<i class="'.$typeActivit->ClasseFa().' fa-5x"></i></a><br />
<a class="btn btn-light" href="javascript:ouvreUrlModal(\'?appelleScript=ajoutActivite&id_job='.$this->ParamId.'&type_activite='.urlencode($n).'\') ;">
'.htmlentities($typeActivit->Titre()).'
</a>
</div>'.PHP_EOL ;
		}
		$ctn .= '</div>'.PHP_EOL ;
		$ctn .= '</div>
</div>' ;
		return $ctn ;
	}
	public function RenduSpecifique()
	{
		$ctn = parent::RenduSpecifique() ;
		$ctn .= $this->RenduBlocTypesActivit() ;
		return $ctn ;
	}
}
