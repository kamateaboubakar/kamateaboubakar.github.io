<?php

namespace Pv\ZoneWeb\SiteWeb ;

class LienPartageReseauxSociaux extends \Pv\ZoneWeb\ComposantRendu\ComposantRendu
{
	public $Url ;
	public $Libelle = "Partager : " ;
	public $CheminIconeFacebook = "images/share_facebook.png" ;
	public $CheminIconeTwitter = "images/share_twitter.png" ;
	public $CheminIconeGooglePlus = "images/share_google_p.png" ;
	public $CheminIconeLinkedIn = "images/share_linkedin.png" ;
	public $SeparateurLiens = "&nbsp;&nbsp;" ;
	protected function RenduDispositifBrut()
	{
		$ctn = '' ;
		$ctn .= $this->Libelle ;
		$ctn .= '<a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($this->Url).'" target="_share"><img src="'.$this->CheminIconeFacebook.'" border="0" /></a>' ;
		$ctn .= $this->SeparateurLiens ;
		$ctn .= '<a href="https://twitter.com/home?status='.urlencode($this->Url).'" target="_share"><img src="'.$this->CheminIconeTwitter.'" border="0" /></a>' ;
		$ctn .= $this->SeparateurLiens ;
		$ctn .= '<a href="https://plus.google.com/share?url='.urlencode($this->Url).'" target="_share"><img src="'.$this->CheminIconeGooglePlus.'" border="0" /></a>' ;
		return $ctn ;
	}
}