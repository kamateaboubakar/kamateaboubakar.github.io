<?php

namespace Pv\ZoneWeb\Action ;

class Notification extends \Pv\ZoneWeb\Action\Action
{
	protected $Message ;
	public function & ObtientMessage()
	{
		return $this->Message ;
	}
	public function PossedeMessage()
	{
		return $this->Message->Contenu != "" ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->Message = new \Pv\ZoneWeb\Action\MsgNotification() ;
	}
	protected function ConfirmeMessage($msg, $typeErreur="")
	{
		$this->Message->Contenu = $msg ;
		$this->Message->TypeErreur = $typeErreur ;
	}
	public function ConfirmeSucces($msg)
	{
		$this->ConfirmeMessage($msg, "") ;
	}
	public function RenseigneErreur($msg)
	{
		$this->ConfirmeMessage($msg, "erreur") ;
	}
	public function ConfirmeErreur($msg)
	{
		$this->ConfirmeMessage($msg, "erreur") ;
	}
	public function ConfirmeException($msg)
	{
		$this->ConfirmeMessage($msg, "exception") ;
	}
	public function Execute()
	{
	}
}