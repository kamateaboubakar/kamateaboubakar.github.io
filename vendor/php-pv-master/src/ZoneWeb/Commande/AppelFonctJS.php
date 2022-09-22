<?php

namespace Pv\ZoneWeb\Commande ;

class AppelFonctJS extends \Pv\ZoneWeb\Commande\RedirectionHttp
{
	public $FenetreCible = "window" ;
	public $NomFonct = "" ;
	public $Params = array() ;
	protected function EnumParamsJS()
	{
		$ctn = '' ;
		foreach($this->Params as $i => $param)
		{
			if($ctn != '')
				$ctn .= ', ' ;
			$ctn .= svc_json_encode($param) ;
		}
		return $ctn ;
	}
	protected function ExecuteInstructions()
	{
		$ctn = '<script type="text/javascript">
jQuery(function() {
'.$this->FenetreCible.'.'.$this->NomFonct.'('.$this->EnumParamsJS().') ;
}) ;
</script>'.PHP_EOL ;
		if($this->EstPasNul($this->TableauDonneesParent))
		{
			$this->TableauDonneesParent->ContenuAvantRendu .= $ctn ;
		}
		elseif($this->EstPasNul($this->FormulaireDonneesParent))
		{
			$this->FormulaireDonneesParent->ContenuAvantRendu .= $ctn ;
		}
	}
}