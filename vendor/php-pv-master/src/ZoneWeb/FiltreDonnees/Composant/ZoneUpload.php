<?php

namespace Pv\ZoneWeb\FiltreDonnees\Composant ;

class ZoneUpload extends \Pv\ZoneWeb\FiltreDonnees\Composant\ElementFormulaire
{
	public $TypeEditeur = "input_file_html" ;
	public $InclureErreurTelecharg = true ;
	public $InclureCheminCoteServeur = true ;
	public $AfficherCheminComplet = 0 ;
	public $CheminCoteServeurEditable = 0 ;
	public $InclureZoneSelectFichier = true ;
	public $TailleEditeurCoteServeur = "40" ;
	public $TypeElementFormulaire = "file" ;
	public $NomEltCoteSrv = "CoteSrv_" ;
	public $LibelleCoteSrv = "Chemin sur le serveur" ;
	public $InclureApercu = 1 ;
	public $LibelleViderFichier = "Vider" ;
	protected $IncorporerApercu = 0 ;
	public $LibelleApercu = "Aper&ccedil;u" ;
	public $LargeurCadreApercu = "100%" ;
	public $HauteurCadreApercu = "" ;
	public $UrlCadreApercuVide = "about:blank" ;
	public $CibleApercu = "_blank" ;
	protected function RenduDispositifBrut()
	{
		$this->CorrigeIDsElementHtml() ;
		$this->DetecteApercuIncorpore() ;
		$ctn = '' ;
		if($this->InclureZoneSelectFichier)
		{
			$ctn .= $this->RenduZoneSelectFichier() ;
		}
		if($this->Valeur != '' || (($this->InclureErreurTelecharg && $this->FiltreParent->CodeErreurTelechargement != '0')))
		{
			if($this->InclureZoneSelectFichier)
			{
				$ctn .= '<br />' ;
			}
			$ctn .= '<table>' ;
			$ctn .= '<tr>' ;
			$ctn .= '<td>'.PHP_EOL ;
			$ctn .= $this->RenduCheminCoteServeur() ;
			$ctn .= '</td>'.PHP_EOL ;
			if($this->InclureErreurTelecharg)
			{
				if($this->FiltreParent->CodeErreurTelechargement != '')
				{
					$ctn .= '<td>'.PHP_EOL ;
					$ctn .= $this->FiltreParent->LibelleErreurTelecharg ;
					$ctn .= '</td>'.PHP_EOL ;
				}
			}
			$ctn .= '</tr>'.PHP_EOL ;
			$ctn .= '</table>' ;
		}
		return $ctn ;
	}
	protected function RenduZoneSelectFichier()
	{
		$ctn = '' ;
		$ctn .= '<input name="'.$this->NomElementHtml.'"' ;
		$ctn .= ' id="'.$this->IDInstanceCalc.'"' ;
		$ctn .= ' type="'.$this->TypeElementFormulaire.'"' ;
		$ctn .= $this->RenduAttrStyleCSS() ;
		$ctn .= $this->RenduAttrsSupplHtml() ;
		// $ctn .= ' value="'.htmlentities($this->Valeur).'"' ;
		if($this->IncorporerApercu == true)
		{
			// $ctn .= ' onchange="if(this.value != \'\') { alert(this.value); document.getElementById(\'CadreApercu_'.$this->IDInstanceCalc.'\').src = this.value ; }"' ;
		}
		$ctn .= ' />' ;
		return $ctn ;
	}
	protected function RenduCheminCoteServeur()
	{
		$ctn = '' ;
		$nomEltCoteSrv = ($this->FiltreParent != '') ? $this->FiltreParent->NomEltCoteSrv : $this->NomEltCoteSrv ;
		$instrsJsViderVal = '' ;
		if($this->InclureCheminCoteServeur)
		{
			if($this->CheminCoteServeurEditable)
			{
				$valeurEnc = (trim($this->Valeur) != "") ? htmlspecialchars(trim($this->Valeur)) : "" ;
				$ctn .= $this->LibelleCoteSrv.' <input type="text" class="EditeurCheminCoteServeur" name="'.$nomEltCoteSrv.$this->NomElementHtml.'" value="'.$valeurEnc.'" size="'.$this->TailleEditeurCoteServeur.'" />' ;
			}
			else
			{
				$valeurEnc = (trim($this->Valeur) != "") ? htmlspecialchars(trim($this->Valeur)) : "" ;
				$ctn .= '<input type="hidden" name="'.$nomEltCoteSrv.$this->NomElementHtml.'" value="'.$valeurEnc.'" />' ;
				$valeur = $this->Valeur ;
				$instrsJsViderVal .= 'document.getElementById(&quot;val_'.$nomEltCoteSrv.$this->NomElementHtml.'&quot;).innerText = &quot;&quot;;' ;
				if($this->AfficherCheminComplet == 1 && $valeur != '')
				{
					$infosFich = pathinfo($valeur) ;
					$valeur = $infosFich["basename"] ;
				}
				$ctn .= '<span id="val_'.$nomEltCoteSrv.$this->NomElementHtml.'">'.htmlentities($valeur).'</span>' ;
			}
		}
		else
		{
			$ctn .= '<input type="hidden" name="'.$nomEltCoteSrv.$this->NomElementHtml.'" value="'.htmlspecialchars(trim($this->Valeur)).'" />' ;
		}
		if($this->InclureCheminCoteServeur)
		{
			$ctn .= ' &nbsp;' ;
		}
		$ctn .= '<a href="javascript:;" onclick="document.getElementsByName(\''.$nomEltCoteSrv.$this->NomElementHtml.'\')[0].value = \'\';'.$instrsJsViderVal.'">'.$this->LibelleViderFichier.'<a>&nbsp;' ;
		if($this->InclureApercu > 0 && $this->IncorporerApercu == 0 && trim($this->Valeur) != '')
		{
			if($this->InclureCheminCoteServeur)
				$ctn .= '&nbsp;&nbsp;' ;
			$ctn .= '<a href="'.htmlspecialchars($this->Valeur).'" target="'.$this->CibleApercu.'">'.$this->LibelleApercu.'</a>' ;
		}
		$ctn .= $this->RenduCadreApercu() ;
		return $ctn ;
	}
	protected function DetecteApercuIncorpore()
	{
		$this->IncorporerApercu = 0 ;
		if($this->InclureApercu == 2 && trim($this->Valeur) != '')
		{
			$infosFich = pathinfo($this->Valeur) ;
			if($infosFich["extension"] != '' && in_array(strtolower($infosFich["extension"]), array("png", "gif", "jpg", "jpeg", "html", "ppt", "pptx", "doc", "xls", "xlsx", "docx", "pdf", "mp3", "mp4", "avi")))
			{
				$this->IncorporerApercu = 1 ;
			}
		}
	}
	protected function RenduCadreApercu()
	{
		$ctn = '' ;
		if($this->IncorporerApercu == 1)
		{
			$ctn .= '<br>' ;
			$urlCadre = ($this->Valeur != '') ? $this->Valeur : $this->UrlCadreApercuVide ;
			$ctn .= '<iframe id="CadreApercu_'.$this->IDInstanceCalc.'" src="'.htmlspecialchars($urlCadre).'" defer frameborder="0"'.(($this->LargeurCadreApercu != '') ? ' width="'.$this->LargeurCadreApercu.'"' : '').(($this->HauteurCadreApercu != '') ? ' height="'.$this->HauteurCadreApercu.'"' : '').'></iframe>' ;
		}
		return $ctn ;
	}
	public function RenduEtiquette()
	{
		$ctn = '' ;
		$this->DetecteApercuIncorpore() ;
		if($this->IncorporerApercu == 0 && $this->Valeur != "")
		{
			$ctn .= '<a href="'.htmlspecialchars($this->Valeur).'" target="'.$this->CibleApercu.'">'.$this->EncodeEtiquette($this->Valeur).'</a>' ;
		}
		else
		{
			$ctn .= $this->RenduCadreApercu() ;
		}
		return '<span id="'.$this->IDInstanceCalc.'">'.$ctn.'</span>' ;
	}
}