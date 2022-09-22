<?php

namespace Pv\ServicePersist ;

class Processus extends \Pv\ServicePersist\ServicePersist
{
	public function EstActif($cheminFichierAbsolu, $cheminFichierElementActif)
	{
		$ok = parent::EstActif($cheminFichierAbsolu, $cheminFichierElementActif) ;
		if(! $ok)
		{
			return $ok ;
		}
		$ok = true ;
		foreach($this->ArgsParDefaut as $nom => $valeur)
		{
			if(! isset($this->Args[$nom]) || $this->ArgsParDefaut[$nom] != $this->Args[$nom])
			{
				$ok = false ;
				break ;
			}
		}
		return $ok ;
	}
}