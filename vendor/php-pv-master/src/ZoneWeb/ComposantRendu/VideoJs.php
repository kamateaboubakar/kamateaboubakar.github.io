<?php

namespace Pv\ZoneWeb\ComposantRendu ;

class VideoJs extends PvComposantJSFiltrable
{
	public $AttrsTag ;
	public $CheminFichierCSS = "css/video-js.min.css" ;
	public $CheminFichierJs = "js/video.min.js" ;
	public $InclureVtt = 0 ;
	public $CheminFichierVttJs = "js/videojs-vtt.js" ;
	public $CheminFichierSwf = "video-js.swf" ;
	public $NomColonneCheminVideo = "chemin_video" ;
	public $NomColonneTitre ;
	public $Largeur ;
	public $MessageAucunElement = 'Aucune vid&eacute;o trouv&eacute;e' ;
	public $MessageMauvaiseConfig = 'Le composant n\'a pas &eacute;t&eacute; configur&eacute; correctement.' ;
	public $ElementsEnCours = null ;
	public static $TypesMimeDefaut = array(
		'mp4' => 'video/mp4',
		'mpeg' => 'video/mpeg',
		'avi' => 'video/avi',
		'msvideo' => 'video/msvideo',
		'qt' => 'video/quicktime',
		'3gp' => 'video/3gpp',
		'mp3' => 'audio/mp3',
		'ogg' => 'audio/ogg',
		'wav' => 'audio/wav',
	) ;
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->AttrsTag = new \Pv\ZoneWeb\ComposantRendu\AttrsTagVideoJs() ;
	}
	protected function RenduSourceBrut()
	{
		$ctn = '' ;
		$ctn .= $this->RenduContenuCSS($this->CheminFichierCSS) ;
		$ctn .= $this->RenduLienJs($this->CheminFichierJs) ;
		if($this->InclureVtt == 1)
		{
			$ctn .= $this->RenduLienJs($this->CheminFichierVttJs) ;
		}
		$ctn .= $this->RenduContenuJs('videojs.options.flash.swf = '.svc_json_encode($this->CheminFichierJs)) ;
		return $ctn ;
	}
	public function CalculeElementsRendu()
	{
		$this->ElementsEnCours = $this->FournisseurDonnees->SelectElements(array(), $this->ObtientFiltresSelection()) ;
	}
	protected function RenduDispositifBrutSpec()
	{
		$ctn = '' ;
		if(! is_array($this->ElementsEnCours))
		{
			$ctn .= '<p class="Erreur">'.htmlentities($this->FournisseurDonnees->MessageException()).'</p>' ;
			return $ctn ;
		}
		if(count($this->ElementsEnCours) > 0)
		{
			if(! isset($this->ElementsEnCours[0][$this->NomColonneCheminVideo]))
			{
				$ctn .= '<p class="Erreur">'.$this->MessageMauvaiseConfig.'</p>' ;
				return $ctn ;
			}
			$ctn .= '<video id="'.$this->IDInstanceCalc.'" class="video-js '.$this->AttrsTag->Skin.'"' ;
			$ctn .= (($this->AttrsTag->InclureControles == 1) ? ' controls' : '').' preload="'.$this->AttrsTag->Preload.'" width="'.$this->Largeur.'" height="'.$this->Hauteur.'" data-setup="'.htmlspecialchars(svc_json_encode($this->AttrsTag->DataSetup)).'"' ;
			$ctn .= '>'.PHP_EOL ;
			$extsTypesMime = array_keys(\Pv\ZoneWeb\ComposantRendu\VideoJs::$TypesMimeDefaut) ;
			foreach($this->ElementsEnCours as $i => $lgn)
			{
				$cheminVideo = $lgn[$this->NomColonneCheminVideo] ;
				if($cheminVideo == '')
				{
					continue ;
				}
				$info = pathinfo($cheminVideo) ;
				$extension = strtolower($info["extension"]) ;
				if(! in_array($extension, $extsTypesMime))
				{
					continue ;
				}
				$titre = (isset($lgn[$this->NomColonneTitre])) ? $lgn[$this->NomColonneTitre] : substr($info["basename"], strlen($info["basename"]) - strlen($info["basename"]), strlen($info["basename"])) ;
				$ctn .= '<source src="'.htmlspecialchars($cheminVideo).'" title="'.htmlspecialchars($titre).'" type="'.\Pv\ZoneWeb\ComposantRendu\VideoJs::$TypesMimeDefaut[$extension].'" >'.PHP_EOL ;
			}
			$ctn .= '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>'.PHP_EOL ;
			$ctn .= '</video>' ;
		}
		else
		{
			$ctn .= '<p>'.$this->MessageAucunElement.'</p>' ;
		}
		return $ctn ;
	}
}