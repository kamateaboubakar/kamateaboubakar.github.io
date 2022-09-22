<?php

namespace Pv\ZoneWeb\Action ;

class FluxRSS extends \Pv\ZoneWeb\Action\EnvoiFichier
{
	public $TypeMime = "application/rss+xml" ;
	public $Titre = "" ;
	public $ExtensionFichierAttache = "rss" ;
	public $VersionXML = "1.0" ;
	public $VersionRSS = "2.0" ;
	public $Encodage = "utf-8" ;
	public $UtiliserFichierSource = 1 ;
	public $Lgns = array() ;
	public function EncodeTexteRss($texte)
	{
		return strip_tags(\Pv\Misc::clean_special_chars($texte)) ;
	}
	protected function AfficheContenu()
	{
		$this->PrepareDoc() ;
		$this->AfficheDebutDoc() ;
		$this->AfficheChaineZone() ;
		$this->AfficheCorpsDoc() ;
		$this->AfficheFinDoc() ;
	}
	protected function PrepareDoc()
	{
	}
	protected function RenduLgnLien($lgn)
	{
		$ctn = '' ;
		$ctn .= '<item>'.PHP_EOL ;
		$ctn .= '</item>'.PHP_EOL ;
		return $ctn ;
	}
	protected function AfficheDebutDoc()
	{
		echo '<?xml version="'.$this->VersionXML.'" encoding="'.$this->Encodage.'"?>
<rss version="'.$this->VersionRSS.'">
<channel>'.PHP_EOL ;
	}
	protected function AfficheCorpsDoc()
	{
	}
	protected function AfficheFinDoc()
	{
		echo '</channel>
</rss>' ;
	}
	protected function AfficheChaineZone()
	{
		$titre = '' ;
		if($this->ZoneParent->ScriptAppele->TitreDocument != '')
			$titre = $this->ZoneParent->ScriptAppele->TitreDocument ;
		if($titre == '' && $this->ZoneParent->Titre != '')
			$titre = $this->ZoneParent->Titre ;
		if($titre == '' && $this->Titre != '')
			$titre = $this->Titre ;
		if($titre != "")
		{
			echo '<title><![CDATA['.$this->EncodeTexteRss($titre).']]></title>'.PHP_EOL ;
		}
		$description = '' ;
		if($this->EstPasNul($this->ZoneParent->ScriptAppele))
		{
			if($this->ZoneParent->ScriptPourRendu->MotsCleMeta != '')
			{
				$description .= $this->ZoneParent->ScriptAppele->MotsCleMeta ;
			}
			if($this->ZoneParent->ScriptAppele->DescriptionMeta != '')
			{
				if($description != '')
					$description .= ' : ' ;
				$description .= $this->ZoneParent->ScriptAppele->DescriptionMeta ;
			}
		}
		else
		{
			if($this->ZoneParent->MotsCleMeta != '')
			{
				$description .= $this->ZoneParent->MotsCleMeta ;
			}
			if($this->ZoneParent->DescriptionMeta != '')
			{
				if($description != '')
					$description .= ' : ' ;
				$description .= $this->ZoneParent->DescriptionMeta ;
			}
		}
		if($description != "")
		{
			echo '<description><![CDATA['.$this->EncodeTexteRss($description).']]></description>'.PHP_EOL ;
		}
		echo '<link>'.htmlentities($this->ZoneParent->ObtientUrl()).'</link>'.PHP_EOL ;
	}
}