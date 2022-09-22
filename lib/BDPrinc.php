<?php

namespace Rpa2p ;

class BDPrinc extends \Pv\DB\PDO\Mysql
{
	public $CharacterEncoding = 'utf8' ;
	public function InitConnectionParams()
	{
		parent::InitConnectionParams() ;
		$this->ConnectionParams["server"] = \Rpa2p\Config\BD::HOTE_PRINC ;
		$this->ConnectionParams["user"] = \Rpa2p\Config\BD::USER_PRINC ;
		$this->ConnectionParams["password"] = \Rpa2p\Config\BD::MOT_PASSE_PRINC ;
		$this->ConnectionParams["schema"] = \Rpa2p\Config\BD::SCHEMA_PRINC ;
	}
}