<?php

namespace Pv\ZoneWeb\ActionCommande ;

class NotifieScript extends \Pv\ZoneWeb\ActionCommande\ActionCommande
{
	public $Url = "" ;
	public $NomScript = "" ;
	public $Parametres = array() ;
	protected function DetermineUrl()
	{
		if($this->NomScript != "" && ! $this->EstNul($this->ZoneParent) && isset($this->ZoneParent->Scripts[$this->NomScript]))
		{
			$this->Url = $this->ZoneParent->Scripts[$this->NomScript]->ObtientUrl() ;
		}
		if($this->Url != '' && count($this->Parametres) > 0)
		{
			$this->Url = \Pv\Misc::update_url_params($this->Url, $this->Parametres) ;
		}
	}
	public function Execute()
	{
		$this->DetermineUrl() ;
		if($this->Url != "")
		{
			$cmd = & $this->CommandeParent ;
			if($cmd->EstSucces())
			{
				$cmd->ZoneParent->ConfirmeSuccesExecution($cmd->MessageExecution) ;
			}
			else
			{
				$cmd->ZoneParent->DefinitMessageExecution(2, $cmd->MessageExecution) ;
			}
			\Pv\Misc::redirect_to($this->Url) ;
		}
		else
		{
			$this->MessageErreur = "URL vide trouv&eacute;e" ;
		}
	}
}