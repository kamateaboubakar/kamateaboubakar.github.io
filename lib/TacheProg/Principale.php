<?php

namespace Rpa2p\TacheProg ;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Principale extends TacheProg
{
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->CheminFichierRelatif = \Rpa2p\Config\Chemin::TACHE_PRINC ;
	}
	protected function ExecuteSession()
	{
	}
}
