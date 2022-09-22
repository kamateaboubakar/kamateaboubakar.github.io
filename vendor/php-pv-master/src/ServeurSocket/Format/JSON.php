<?php

namespace Pv\ServeurSocket\Format ;

class JSON extends \Pv\ServeurSocket\Format\FormatSocket
{
	public function Decode($contenu)
	{
		return @svc_json_decode($contenu) ;
	}
	public function Encode($contenu)
	{
		return svc_json_encode($contenu) ;
	}
}