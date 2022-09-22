<?php

namespace Rpa2p\Job\TypeNotif ;

class Silencieux extends TypeNotif
{
	public $CaptureImgs = true ;
	public function Id()
	{
		return 'silencieux' ;
	}
	public function Titre()
	{
		return 'Silencieux' ;
	}
}

