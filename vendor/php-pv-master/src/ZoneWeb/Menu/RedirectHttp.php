<?php

namespace Pv\ZoneWeb\Menu ;

class RedirectHttp extends \Pv\ZoneWeb\Menu\MenuWeb
{
	public function ObtientStatutSelection()
	{
		$selectionne = 0 ;
		if($this->Url == '')
			return 0 ;
		$partiesEnCours = parse_url(\Pv\Misc::get_current_url()) ;
		$url = \Pv\Misc::make_abs_url($this->Url, \Pv\Misc::get_current_url_dir()) ;
		$partiesDemandees = parse_url($url) ;
		$ok = ($partiesEnCours == $partiesDemandees) ? 1 : 0 ;
		// print "$url : $ok<br>\n\n" ;
		return $ok ;
	}
}