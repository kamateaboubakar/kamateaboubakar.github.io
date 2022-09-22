<?php

namespace Pv\ZoneWeb\Commande ;

class ActionNotification extends \Pv\ZoneWeb\Commande\Commande
{
	public $ActionNotification ;
	protected function ExecuteInstructions()
	{
		if($this->EstNul($this->ActionNotification))
		{
			$this->RenseigneErreur("L'action rattach&eacute;e &agrave; la commande est nulle ou n'existe pas.") ;
			return ;
		}
		$this->ActionNotification->Execute() ;
		$msg = $this->ActionNotification->ObtientMessage() ;
		if($msg->TypeErreur == "")
		{
			$this->ConfirmSucces($msg->Contenu) ;
		}
		else
		{
			$this->ConfirmErreur($msg->Contenu) ;
		}
	}
}