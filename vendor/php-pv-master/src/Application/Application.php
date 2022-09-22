<?php

namespace Pv\Application ;

class Application extends \Pv\Objet\Objet
{
	public $CheminIconeElem = "images/icone-elem-app.png" ;
	public $CheminMiniatureElem = "images/miniature-elem-app.png" ;
	public $ValeurUniteTache = 5 ;
	public $AutoDetectChemRelFichierActif = 1 ;
	public $ModelesOperation = array() ;
	public $IHMs = array() ;
	public $SystTrad ;
	public $BasesDonnees = array() ;
	public $ServsPersists = array() ;
	static $InclureAliasesCompsFltsDonnees = false ;
	static $AliasesCompsFltsDonnees = array() ;
	public $TachesProgs = array() ;
	public $ServsVendus = array() ;
	public $Elements = array() ;
	public $CheminFichierElementActifFixe = "" ;
	public $CheminFichierElementActif = "" ;
	public $CheminFichierAbsolu = "" ;
	public $CheminFichierRelatif = "../.." ;
	public $NomElementActif = "" ;
	public $ElementActif = null ;
	public $ElementHorsLigne = null ;
	public $DebogageActive = 0 ;
	public $CtrlTachesProgs ;
	public $CtrlServsPersists ;
	public $ChemRelRegServsPersists ;
	public $NomsInterfsPaiement = array() ;
	public $NomZoneRenduInterfPaiement ;
	public $UrlRacine ;
	public $MessageAucunElement = "Aucun element declare dans l'application" ;
	public $MessageElementNonTrouve = "Aucun element ne correspond a ce fichier" ;
	public $CheminDossierRacine ;
	public function ObtientChemRelRegServsPersists()
	{
		return dirname(__FILE__)."/".$this->CheminFichierRelatif."/".$this->ChemRelRegServsPersists ;
	}
	public function Debogue($niveau, $message)
	{
		if(! $this->DebogageActive)
		{
			return ;
		}
	}
	public function ChargeConfig()
	{
		$this->ChargeBasesDonnees() ;
		$this->ChargeSystTrad() ;
		$this->ChargeTachesProgs() ;
		$this->ChargeServsPersists() ;
		$this->ChargeIHMs() ;
		$this->ChargeServsVendus() ;
		$this->ChargeElementHorsLigne() ;
	}
	public function InscritCtrlTachesProgs($nomElem='ctrlTachesProgs')
	{
		$this->CtrlTachesProgs = new \Pv\TacheCtrl\ProgsApp() ;
		$this->InscritTacheProg($nomElem, $this->CtrlTachesProgs) ;
	}
	public function InscritCtrlServsPersists($nomElem='ctrlTachesProgs')
	{
		$this->CtrlServsPersists = new \Pv\TacheProg\CtrlServsPersists() ;
		$this->InscritTacheProg($nomElem, $this->CtrlServsPersists) ;
		return $this->CtrlServsPersists ;
	}
	public function & InscritStopServsPersists($nomElem='stopTachesProgs')
	{
		$stopServsPersists = new \Pv\ServicePersist\Stop() ;
		$this->InscritTacheProg($nomElem, $stopServsPersists) ;
		return $stopServsPersists ;
	}
	public function & InscritArretServsPersists($cheminRelatif="", $nomElem='stopTachesProgs')
	{
		$stopServsPersists = $this->InscritStopServsPersists($nomElem) ;
		$stopServsPersists->CheminFichierRelatif = $cheminRelatif ;
		return $stopServsPersists ;
	}
	protected function ChargeBasesDonnees()
	{
	}
	public function & InsereInterfPaiement($nom, $interf)
	{
		$interf = $this->InsereIHM($nom, $interf) ;
		$this->NomsInterfsPaiement[] = $nom ;
		return $interf ;
	}
	public function & InterfsPaiement()
	{
		$results = array() ;
		foreach($this->NomsInterfsPaiement as $i => $nom)
		{
			$results[$nom] = & $this->IHMs[$nom] ;
		}
		return $results ;
	}
	public function & InterfPaiement($nom)
	{
		$result = null ;
		if(! in_array($nom, $this->NomsInterfsPaiement))
		{
			return $result ;
		}
		return $this->IHMs[$nom] ;
	}
	public function ExisteInterfPaiement($nom)
	{
		return in_array($nom, $this->NomsInterfsPaiement) ;
	}
	public function Traduit($nomExpr, $params=array(), $valParDefaut='', $nomTrad='')
	{
		return $this->SystTrad->Execute($nomExpr, $params, $valParDefaut, $nomTrad) ;
	}
	public function ActiveTraducteur($nomTrad)
	{
		return $this->SystTrad->ActiveTraducteur($nomTrad) ;
	}
	public function CreeSystTrad()
	{
		return new \Pv\Traduction\Systeme() ;
	}
	protected function ChargeSystTrad()
	{
		$this->SystTrad = $this->CreeSystTrad() ;
		$this->SystTrad->ChargeConfig() ;
	}
	protected function ChargeServsVendus()
	{
	}
	protected function ChargeIHMs()
	{
	}
	protected function ChargeTachesProgs()
	{
	}
	protected function ChargeServsPersists()
	{
	}
	protected function ChargeElementHorsLigne()
	{
		$this->ElementHorsLigne = null ;
	}
	public function InscritElement($nom, & $element)
	{
		if(isset($this->Elements[$nom]))
		{
			die("Impossible d'inscrire l'element ".$nom.". Il existe deja.") ;
		}
		$this->Elements[$nom] = & $element ;
		$element->AdopteApplication($nom, $this) ;
	}
	public function & InsereBaseDonnees($nom, $bd)
	{
		$this->InscritBaseDonnees($nom, $bd) ;
		return $bd ;
	}
	public function & InsereTacheProg($nom, $tacheProg)
	{
		$this->InscritTacheProg($nom, $tacheProg) ;
		return $tacheProg ;
	}
	public function & InsereServPersist($nom, $srvPersist)
	{
		$this->InscritServPersist($nom, $srvPersist) ;
		return $srvPersist ;
	}
	public function & InsereServsProcessus($nom, $srvProc, $totalInstances=2)
	{
		$servs = array() ;
		for($i=0; $i<$totalInstances; $i++)
		{
			$servs[$i] = $this->InsereServPersist($nom."_".$i, $srvProc) ;
			$servs[$i]->ArgsParDefaut["no_processus"] = $i ;
		}
		return $servs ;
	}
	protected function & InsereServiceVendu($nom, $svc)
	{
		return $this->InsereServVendu($nom, $svc) ;
	}
	protected function & InsereServVendu($nom, $svc)
	{
		$svc->NomElementInterfPaiemt = $nom ;
		$this->ServsVendus[$nom] = & $svc ;
		return $svc ;
	}
	public function & InsereIHM($nom, $ihm)
	{
		$this->InscritIHM($nom, $ihm) ;
		return $ihm ;
	}
	public function InscritIHM($nom, & $ihm)
	{
		$this->IHMs[$nom] = & $ihm ;
		$this->InscritElement($nom, $ihm) ;
	}
	public function InscritTacheProg($nom, & $tacheProg)
	{
		$this->TachesProgs[$nom] = & $tacheProg ;
		$this->InscritElement($nom, $tacheProg) ;
	}
	public function InscritServPersist($nom, & $srvPersist)
	{
		$this->ServsPersists[$nom] = & $srvPersist ;
		$this->InscritElement($nom, $srvPersist) ;
	}
	public function InscritBaseDonnees($nom, & $bd)
	{
		$this->BasesDonnees[$nom] = & $bd ;
		// $this->InscritElement($nom, $bd) ;
	}
	public function EnregIHM(& $ihm)
	{
		$this->InscritIHM($ihm->IDInstance, $ihm) ;
	}
	public function DeclareIHM($nom='', $classeIHM='', $cheminFichier='')
	{
		if(! class_exists($classeIHM))
		{
			die("La classe $classIHM n'existe pas. Elle ne peut pas etre inscrte comme zone IHM") ;
		}
		$ihm = new $classeIHM() ;
		if($nom == '')
		{
			$nom = "IHM_".(count($this->IHMs) + 1) ;
		}
		$ihm->CheminFichierRelatif = $cheminFichier ;
		$nomPropriete = 'IHM'.ucfirst($nom) ;
		$this->$nomPropriete = & $ihm ;
		$this->InscritIHM($nom, $ihm) ;
	}
	public function DetecteElementActif()
	{
		$ok = 0 ;
		$this->DetecteCheminFichierElementActif() ;
		$this->ElementActif = $this->ValeurNulle() ;
		$this->NomElementActif = "" ;
		$cles = array_keys($this->Elements) ;
		foreach($cles as $i => $cle)
		{
			$element = & $this->Elements[$cle] ;
			$ok = $element->EstActif($this->CheminFichierAbsolu, $this->CheminFichierElementActif) ;
			if($ok)
			{
				$this->NomElementActif = $element->NomElementApplication ;
				$this->ElementActif = & $element ;
				break ;
			}
		}
		return $ok ;
	}
	public function ExecuteElementActif()
	{
		if($this->EstPasNul($this->ElementActif))
		{
			$this->ElementActif->ChargeConfig() ;
			$this->ElementActif->Execute() ;
		}
		else
		{
			if($this->EstPasNul($this->ElementHorsLigne))
			{
				$this->ElementHorsLigne->ChargeConfig() ;
				$this->ElementHorsLigne->Execute() ;
			}
			else
			{
				if(count($this->Elements) == 0)
				{
					echo $this->MessageAucunElement ;
				}
				else
				{
					echo $this->MessageElementNonTrouve ;
				}
			}
		}
	}
	public function ExecuteElement($nomElem)
	{
		$this->ChargeConfig() ;
		if(isset($this->Elements[$nomElem]))
		{
			$this->NomElementActif = $nomElem ;
			$this->ElementActif = & $this->Elements[$nomElem] ;
		}
		$this->ExecuteElementActif() ;
	}
	public function EnModeConsole()
	{
		return (php_sapi_name() == "cli" || (isset($_SERVER["argv"]) && isset($_SERVER["argv"][0]) && ! isset($_SERVER["SCRIPT_FILENAME"]))) ;
	}
	public function ContenuRequeteHttp()
	{
		$ctn = '' ;
		$ctn .= $_SERVER["REQUEST_METHOD"]." ".$_SERVER["SERVER_PROTOCOL"]." ".$_SERVER["REQUEST_URI"].$_SERVER["QUERY_STRING"]."\r\n" ;
		$entetes = apache_request_headers() ;
		foreach($entetes as $nom => $valeur)
		{
			$ctn .= $nom." : ".$valeur."\r\n" ;
		}
		$ctn .= "\r\n".file_get_contents("php://input") ;
		return $ctn ;
	}
	protected function DetecteCheminFichierElementActif()
	{
		$this->CheminFichierAbsolu = dirname(__FILE__) ;
		if($this->CheminFichierRelatif != "")
		{
			$this->CheminFichierAbsolu .= "/".$this->CheminFichierRelatif ;
		}
		$this->CheminFichierAbsolu = realpath($this->CheminFichierAbsolu) ;
		if($this->AutoDetectChemRelFichierActif == 0)
		{
			$this->CheminFichierElementActif = $this->CheminFichierElementActifFixe ;
			return ;
		}
		if($this->EnModeConsole())
		{
			$this->CheminFichierElementActif = $_SERVER["argv"][0] ;
		}
		else
		{
			$this->CheminFichierElementActif = $_SERVER["SCRIPT_FILENAME"] ;
		}
		$this->CheminFichierElementActif = realpath($this->CheminFichierElementActif) ;
		// echo "Chemin actif : ".$this->CheminFichierElementActif."\n" ;
	}
	public function Execute()
	{
		// print_r("MMMM ".count($this->IHMs)) ;
		$this->ChargeConfig() ;
		$this->DetecteElementActif() ;
		$this->ExecuteElementActif() ;
	}
	public static function TelechargeUrl($url, $valeurPost=array(), $async=1)
	{
		$parts = parse_url($url) ;
		$port = $parts["port"] != '' ? $parts["port"] : (($parts["scheme"] == "https") ? 443 : 80) ;
		$chainePostee = \Pv\Misc::http_build_query_string($valeurPost) ;
		$res = false ;
		$fh = fsockopen($parts["host"], $port, $errno, $errstr, 30) ;
		if ($fh)
		{
			if($chainePostee == '')
			{
				$ctn = "GET ".$parts["path"]."?".$parts["query"]." HTTP/1.0\r\n";
				$ctn .= "Host: ".$parts["host"].":".$port."\r\n" ;
				$ctn .= "Content-Type: text/html\r\n" ;
				$ctn .= "Connection: Close\r\n\r\n" ;
			}
			else
			{
				$ctn = "POST ".$parts["path"]."?".$parts["query"]." HTTP/1.1\r\n";
				$ctn .= "Host: ".$parts["host"].":".$port."\r\n" ;
				$ctn .= "Content-Type: application/x-www-form-urlencoded\r\n" ;
				$ctn .= "Content-Length: ".strlen($chainePostee)."\r\n" ;
				$ctn .= "Connection: Close\r\n\r\n" ;
				$ctn .= $chainePostee ;
				// print $ctn ;
			}
			$ok = fputs($fh, $ctn) ;
			if($async == 0)
			{
				$res = '' ;
				while(! feof($fh))
				{
					$res .= fgets($fh) ;
				}
			}
			else
			{
				$res = $ok ;
			}
			fclose($fh) ;
		}
		return $res ;
	}
	public static function TelechargeShell($commande, $async=1)
	{
		$fh = popen($commande, "r") ;
		if($async == 1)
		{
			if($fh !== false)
			{
				pclose($fh) ;
				return 1 ;
			}
			else
			{
				return 0 ;
			}
		}
		$res = '' ;
		while(! feof($fh))
		{
			$res .= fgets($fh) ;
		}
		pclose($fh) ;
		return $res ;
	}
	public static function ObtientCheminPHP()
	{
		$os = \Pv\Application\Application::ObtientOS() ;
		$phpbin = preg_replace("@/lib(64)?/.*$@", "/bin/php", ini_get("extension_dir"));
		$execPath = dirname($phpbin)."/php" ;
		if($os == 'Windows')
			$execPath .= ".exe" ;
		return $execPath ;
	}
	public static function EncodeArgsShell($args)
	{
		$cmd = '' ;
		foreach($args as $nom => $val)
		{
			$cmd .= ' -'.$nom.'='.escapeshellarg($val) ;
		}
		return $cmd ;
	}
	public static function TelechargeCmd($adresse, $args=array(), $valeurPost='', $async=1)
	{
		$proc = new \Pv\Common\ProcessPipe\ProcessPipe() ;
		$cmd = $adresse ;
		if(is_array($args) && count($args) > 0)
		{
			$cmd .= \Pv\Application\Application::EncodeArgsShell($args) ;
		}
		$result = false ;
		if($proc->Open($cmd))
		{
			if($valeurPost != '')
			{
				$proc->Write($valeurPost) ;
			}
			$result = false ;
			if($async)
			{
				$proc->Close() ;
				return true ;
			}
			$error = $proc->GetError() ;
			if($error == '')
			{
				$ctn = $proc->ReadUntilEOF() ;
			}
			$proc->Close() ;
		}
		return $result ;
	}
	public static function ObtientOS()
	{
		$os = (PHP_OS == "WINNT" || PHP_OS == "WIN32") ? 'Windows' : 'Linux' ;
		return $os ;
	}
	public function & ZoneRenduInterfPaiement()
	{
		$zoneWeb = new \Pv\ZoneWeb\ZoneWebSimple() ;
		if($this->NomZoneRenduInterfPaiement != '' && isset($this->IHMs[$this->NomZoneRenduInterfPaiement]))
		{
			$zoneWeb = & $this->IHMs[$this->NomZoneRenduInterfPaiement] ;
		}
		return $zoneWeb ;
	}
	public function CreeBDPrinc()
	{
		return new \Pv\DB\Connection\Connection() ;
	}
	public function CreeDBPrinc()
	{
		return new \Pv\DB\Connection\Connection() ;
	}
	public function CreeFournisseurDonneesPrinc()
	{
		return $this->CreeFournPrinc() ;
	}
	public function CreeFournDonneesPrinc()
	{
		return $this->CreeFournPrinc() ;
	}
	public function CreeFournPrinc()
	{
		$fourn = new \Pv\FournisseurDonnees\Sql() ;
		$fourn->BaseDonnees = $this->CreeBDPrinc() ;
		return $fourn ;
	}
}
