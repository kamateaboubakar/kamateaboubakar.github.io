<?php

namespace Pv\ApiRestful\Filtre ;

class HttpUpload extends Filtre
{
	public $Role = "http_upload" ;
	public $TypeLiaisonParametre = "post" ;
	public $InfosTelechargement = array() ;
	public $CheminDossier = "." ;
	public $CheminFichierDest = "" ;
	public $CheminFichierSrc = "" ;
	public $DejaTelecharge = 0 ;
	public $NettoyerCaractsFichier = 1 ;
	public $ExtensionsAcceptees = array() ;
	public $ExtensionsRejetees = array('pl', 'cgi', 'html', 'xhtml', 'html5', 'html4', 'xml', 'xss', 'rss', 'xlt', 'php', 'phtml', 'inc', 'js', 'vbs', 'py', 'bat', 'sh', 'cmd', 'exe', 'msi', 'bin', 'apk', 'com', 'command', 'cpl', 'action', 'csh', 'gadget', 'inf1', 'ins', 'inx', 'ipa', 'isu', 'job', 'jse', 'ksh', 'lnk', 'msc', 'msp', 'mst', 'osx', 'out', 'paf', 'pif', 'prg', 'ps1', 'reg', 'rgs', 'run', 'scr', 'sct', 'shb', 'shs', 'u3p', 'vb', 'vbe', 'vbs', 'vbRoute', 'workflow', 'ws', 'wsf', 'wsh') ;
	public $CheminFichierClient = "" ;
	public $CodeErreurTelechargement = "0" ;
	public $CheminFichierSoumis = "" ;
	public $NomFichierSelect = "" ;
	public $ExtFichierSelect = "" ;
	public $NomEltCoteSrv = "CoteSrv_" ;
	public $LibelleErreurTelecharg = '' ;
	public $FormatFichierTelech = '' ;
	public $SourceTelechargement = '' ;
	public $CodeErreurMauvaiseExt = '501' ;
	public $LibelleErreurAucunFich = 'Aucun fichier n\'a &eacute;t&eacute; soumis' ;
	public $LibelleErreurMauvaiseExt = 'Mauvais format pour le fichier soumis.' ;
	public $CodeErreurDeplFicTelecharg = '502' ;
	public $LibelleErreurDeplFicTelecharg = 'Le deplacement du fichier sur le serveur a &eacute;chou&eacute;. V&eacute;rifiez que vous avez les droits en ecriture.' ;
	public $CodeErreurFicSoumisInexist = '503' ;
	public $ToujoursRenseignerFichier = 0 ;
	public $NePasInclureSiVide = 0 ;
	public $LibelleErreurFicSoumisInexist = 'Le fichier soumis n\'existe pas.' ;
	public function AccepteVidsSeulem()
	{
		$this->ExtensionsAcceptees = array('mp4', 'avi', 'mpeg', 'flv', 'mkv', '3gp') ;
	}
	public function AccepteImgsSeulem()
	{
		$this->ExtensionsAcceptees = array('jpg', 'jpeg', 'png', 'gif', 'svg') ;
	}
	public function AccepteDocsSeulem()
	{
		$this->ExtensionsAcceptees = array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'odt') ;
	}
	public function AccepteTxtsSeulem()
	{
		$this->ExtensionsAcceptees = array('txt', 'log') ;
	}
	public function TelechargementSoumis()
	{
		return $this->SourceTelechargement == 'files' ? 1 : 0 ;
	}
	protected function NettoieCaractsFichier($nomFich)
	{
		$result = preg_replace('/[^a-z0-9\_\.]/i', '_', $nomFich) ;
		return $result ;
	}
	public function ObtientValeurParametre()
	{
		if($this->DejaTelecharge == 1)
		{
			return $this->CheminFichierDest ;
		}
		if(! isset($_FILES[$this->NomParametreLie]) && ! isset($_POST[$this->NomEltCoteSrv.$this->NomParametreLie]))
		{
			return $this->ValeurVide ;
		}
		if(isset($_FILES[$this->NomParametreLie]) && $_FILES[$this->NomParametreLie]["error"] != 4)
		{
			$this->SourceTelechargement = 'files' ;
			$this->InfosTelechargement = $_FILES[$this->NomParametreLie] ;
			$this->CheminFichierSrc = $this->InfosTelechargement["tmp_name"] ;
			$this->CheminFichierClient = $this->InfosTelechargement["name"] ;
			$infosFichier = pathinfo($this->CheminFichierClient) ;
			$this->ExtFichierSelect = (isset($infosFichier["extension"])) ? $infosFichier["extension"] : "" ;
			$this->NomFichierSelect = $infosFichier["basename"] ;
			if($this->ExtFichierSelect != '')
			{
				$this->NomFichierSelect = substr($this->NomFichierSelect, 0, strlen($this->NomFichierSelect) - strlen(".".$infosFichier["extension"])) ;
			}
			if($this->NettoyerCaractsFichier == 1)
			{
				$ancFich = $this->NomFichierSelect ;
				$this->NomFichierSelect = $this->NettoieCaractsFichier($this->NomFichierSelect) ;
			}
			if($this->FormatFichierTelech != '')
			{
				$this->NomFichierSelect = _parse_pattern(
					$this->FormatFichierTelech,
					array(
						"Cle" => uniqid(),
						"NombreAleatoire" => rand(0, 10000),
						"NomFichier" => $this->NomFichierSelect,
						"Timestamp" => date("U"),
						"Date" => date("YmdHis")
					)
				) ;
				// print $this->NomFichierSelect ;
			}
			if($this->ExtFichierSelect != "")
			{
				$this->NomFichierSelect .= '.'.$this->ExtFichierSelect ;
			}
		}
		else
		{
			$this->SourceTelechargement = 'post' ;
			if(isset($_POST[$this->NomEltCoteSrv.$this->NomParametreLie]))
			{
				$this->CheminFichierSoumis = $_POST[$this->NomEltCoteSrv.$this->NomParametreLie] ;
			}
			if($this->CheminFichierSoumis != "")
			{
				$cheminFichierSoumis = realpath(dirname($_SERVER["Route_FILENAME"])."/".$this->CheminFichierSoumis) ;
				$cheminDossier = realpath(dirname($_SERVER["Route_FILENAME"])."/".$this->CheminDossier) ;
				if($this->CheminFichierSoumis != '' && file_exists($cheminFichierSoumis))
				{
					$infosFichier = pathinfo($cheminFichierSoumis) ;
					$this->NomFichierSelect = str_replace("\\", "/", substr($cheminFichierSoumis, strlen($cheminDossier) + 1)) ;
					$this->ExtFichierSelect = (isset($infosFichier["extension"])) ? $infosFichier["extension"] : "" ;
					// echo $this->NomFichierSelect.' kkk <br>' ;
				}
			}
			else
			{
				$this->NomFichierSelect = "" ;
				$this->ExtFichierSelect = "" ;
			}
			// print_r("Doss : ".$this->CheminDossier) ;
			// print_r($infosFichier) ;
		}
		if((count($this->ExtensionsAcceptees) > 0 && ! in_array(strtolower($this->ExtFichierSelect), array_map("strtolower", $this->ExtensionsAcceptees))) || (count($this->ExtensionsRejetees) > 0 && in_array(strtolower($this->ExtFichierSelect), array_map("strtolower", $this->ExtensionsRejetees))))
		{
			$this->CodeErreurTelechargement = $this->CodeErreurMauvaiseExt ;
			$this->LibelleErreurTelecharg = $this->LibelleErreurMauvaiseExt ;
			return $this->ValeurVide ;
		}
		$this->CheminFichierDest = $this->ValeurVide ;
		if($this->NomFichierSelect != "")
		{
			$this->CheminFichierDest = $this->CheminDossier. "/" .$this->NomFichierSelect ;
		}
		if($this->SourceTelechargement == 'files')
		{
			// echo $this->CheminFichierSrc.' '.$this->CheminFichierDest.'<br>' ;
			$ok = move_uploaded_file($this->CheminFichierSrc, $this->CheminFichierDest) ;
			if(! $ok)
			{
				$this->CodeErreurTelechargement = $this->CodeErreurDeplFicTelecharg ;
				$this->LibelleErreurTelecharg = $this->LibelleErreurDeplFicTelecharg ;
				return $this->ValeurVide ;
			}
		}
		else
		{
			if($this->ToujoursRenseignerFichier == 1 && $this->CheminFichierDest == "")
			{
				$this->CodeErreurTelechargement = $this->CodeErreurFicSoumisInexist ;
				$this->LibelleErreurTelecharg = $this->LibelleErreurFicSoumisInexist ;
				return $this->ValeurVide ;
			}
		}
		$this->DejaTelecharge = 1 ;
		// echo $this->NomParametreLie.' '.$this->CheminFichierDest.'<br>' ;
		return $this->CheminFichierDest ;
	}
}
