<?php

namespace Pv\InterfPaiement ;

class InterfPaiement extends \Pv\IHM\IHM
{
	protected $_ServsVendus = array() ;
	protected $_EtatExecution ;
	protected $_Transaction ;
	protected $_CompteMarchand ;
	public $CheminImage = "images/paiement-base.png" ;
	public $CheminIcone = "" ;
	public $Titre = "Ne rien faire" ;
	public $Description = "" ;
	protected $DelaiControleTransacts = 600 ; // En secondes
	protected $MaxTransactsAControler = 5 ; // En secondes
	protected $TransactionValidee = 0 ;
	protected $DelaiExpirCfgsTransact = 24 ;
	protected $NomParamResultat = "resultat" ;
	protected $ValeurParamResultat = "" ;
	protected $ValeurParamTermine = "paiement_termine" ;
	protected $ValeurParamAnnule = "paiement_annule" ;
	public $EnregistrerTransaction = true ;
	public $UtiliserBdTransactionSoumise = 0 ;
	public $NomTableTransactSoumise = "transaction_soumise" ;
	public $NomTableTransaction = "transaction_paiement" ;
	public $MsgPaiementNonFinalise = "Votre paiement a r&eacute;ussi, mais aucune action n'a &eacute;t&eacute; trouv&eacute;e pour le suivi." ;
	public $CheminRelatifRepTransacts = "." ;
	public $AfficherErreurs404 = 0 ;
	public $TitresEtatExecution = array(
		"paiement_annule" => "Annul&eacute;",
		"paiement_termine" => "Termin&eacute;",
		"paiement_echoue" => "Echou&eacute;",
		"paiement_reussi" => "R&eacute;ussi",
	) ;
	public $TitreEtatExecutionNonTrouve = "En cours" ;
	public function ObtientUrl()
	{
		return $this->UrlRacine() ;
	}
	protected function UrlRacine()
	{
		if($this->ApplicationParent->EnModeConsole())
		{
			if($this->ApplicationParent->UrlRacine != '')
			{
				return $this->ApplicationParent->UrlRacine."/".$this->CheminFichierRelatif ;
			}
			elseif($this->ApplicationParent->NomElementActif == $this->NomElementApplication)
			{
				return $_SERVER["argv"][0] ;
			}
			else
			{
				return "" ;
			}
		}
		$url = \Pv\Misc::remove_url_params(\Pv\Misc::get_current_url()) ;
		if($this->ApplicationParent->NomElementActif == $this->NomElementApplication)
		{
			return $url ;
		}
		$url = ((isset($_SERVER["HTTPS"])) ? 'https' : 'http').'://'.$_SERVER["SERVER_NAME"].(($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':'.$_SERVER["SERVER_PORT"] : '').'/'.$this->CheminFichierRelatif ;
		return $url ;
	}
	public function CheminRepTransacts()
	{
		return realpath(dirname(__FILE__)."/../../../../../".$this->CheminRelatifRepTransacts) ;
	}
	protected function CreeBdTransaction()
	{
		return $this->ApplicationParent->CreeBdPrinc() ;
	}
	protected function LgnDonneesTransact()
	{
		return array(
			"id_transaction" => $this->_Transaction->IdTransaction,
			"designation" => $this->_Transaction->Designation,
			"montant" => $this->_Transaction->Montant,
			"monnaie" => $this->_Transaction->Monnaie,
			"nom_interf_paiemt" => $this->NomElementApplication,
			"contenu_brut" => serialize($this->_Transaction),
			"id_etat" => $this->_EtatExecution->Id,
			"timestamp_etat" => $this->_EtatExecution->TimestampCapt,
			"msg_erreur_etat" => $this->_EtatExecution->MessageErreur,
		) ;
	}
	protected function ExporteFichCfgTransition()
	{
		if($this->EnregistrerTransaction)
		{
			$this->SauveTransaction() ;
			return ;
		}
		$cheminFich = $this->CheminRepTransacts()."/".$this->_Transaction->IdTransaction.".dat" ;
		$resFich = fopen($cheminFich, "w") ;
		if($resFich != false)
		{
			fputs($resFich, serialize($this->_Transaction->Cfg)) ;
			fclose($resFich) ;
		}
	}
	protected function ImporteFichCfgTransaction()
	{
		if($this->EnregistrerTransaction == true)
		{
			$bd = $this->CreeBdTransaction() ;
			$lgn = $bd->FetchSqlRow('select * from '.$bd->EscapeTableName($this->NomTableTransaction).' where id_transaction='.$bd->ParamPrefix.'id', array('id' => $this->_Transaction->IdTransaction)) ;
			if(is_array($lgn) && count($lgn) > 0)
			{
				$this->_Transaction->ImporteParLgn($lgn) ;
			}
			return ;
		}
		$cheminFich = $this->CheminRepTransacts()."/".$this->_Transaction->IdTransaction.".dat" ;
		$ctnFich = '' ;
		if(file_exists($cheminFich))
		{
			$resFich = fopen($cheminFich, "r") ;
			if($resFich !== false)
			{
				while(! feof($resFich))
				{
					$ctnFich .= fgets($resFich) ;
				}
				fclose($resFich) ;
			}
		}
		if($ctnFich != "")
		{
			$this->_Transaction->Cfg = unserialize($ctnFich) ;
		}
	}
	protected function VideCfgsTransactsExpirs()
	{
		if($this->EnregistrerTransaction)
		{
			return ;
		}
		if(is_dir($this->CheminRepTransacts()))
		{
			$dh = opendir($this->CheminRepTransacts()) ;
			$timestampActuel = date("U") ;
			if(is_resource($dh))
			{
				while(($nomFich = readdir($dh)) !== false)
				{
					$cheminFich = $this->CheminRepTransacts()."/".$nomFich ;
					if($nomFich == '.' || $nomFich == '..' || is_dir($cheminFich))
					{
						continue ;
					}
					$infoFich = pathinfo($cheminFich) ;
					if($infoFich["extension"] != "dat")
					{
						continue ;
					}
					if($timestampActuel > filemtime($cheminFich) + $this->DelaiExpirCfgsTransact * 3600)
					{
						unlink($cheminFich) ;
					}
				}
				closedir($dh) ;
			}
		}
	}
	protected function SauveTransaction()
	{
		$bd = $this->CreeBdTransaction() ;
		$ok = false ;
		if($this->_Transaction->Montant == '')
		{
			$this->_Transaction->Montant = 0 ;
		}
		if($this->_Transaction->IdDonnees == "")
		{
			$lgnTransact = $bd->FetchSqlRow("select * from ".$bd->EscapeTableName($this->NomTableTransaction)." where id_transaction=:idTransact", array("idTransact" => $this->_Transaction->IdTransaction)) ;
			if(is_array($lgnTransact) && count($lgnTransact) > 0)
			{
				$this->_Transaction->IdDonnees = $lgnTransact["id"] ;
			}
		}
		if($this->_Transaction->IdDonnees == "")
		{
			// print_r($this->_Transaction) ;
			$lgnTransact = $this->LgnDonneesTransact() ;
			$lgnTransact["nom_fournisseur"] = $this->NomFournisseur() ;
			$ok = $bd->InsertRow(
				$this->NomTableTransaction,
				$lgnTransact
			) ;
		}
		else
		{
			$ok = $bd->UpdateRow(
				$this->NomTableTransaction,
				$this->LgnDonneesTransact(),
				"id = ".$bd->ParamPrefix."id",
				array("id" => $this->_Transaction->IdDonnees)
			) ;
		}
		return $ok ;
	}
	public function NomFournisseur()
	{
		return "base" ;
	}
	protected function InitConfig()
	{
		parent::InitConfig() ;
		$this->_EtatExecution = new \Pv\InterfPaiement\EtatExecution() ;
		$this->_CompteMarchand = $this->CreeCompteMarchand() ;
		$this->_Transaction = $this->CreeTransaction() ;
	}
	public function InsereServVendu($nom, $svc)
	{
		$this->InscritServiceVendu($nom, $svc) ;
		return $svc ;
	}
	public function InsereServiceVendu($nom, $svc)
	{
		$this->InscritServiceVendu($nom, $svc) ;
		return $svc ;
	}
	public function InscritServiceVendu($nom, & $svc)
	{
		$this->_ServsVendus[$nom] = & $svc ;
		$svc->NomElementInterfPaiemt = $nom ;
	}
	public function & ServsVendus()
	{
		$servs = $this->ApplicationParent->ServsVendus ;
		if(count($this->_ServsVendus) > 0)
		{
			$servs = array_merge($servs, $this->_ServsVendus) ;
		}
		return $servs ;
	}
	public function UrlPaiementTermine()
	{
		return $this->UrlRacine()."?".$this->NomParamResultat."=".urlencode($this->ValeurParamTermine) ;
	}
	public function UrlPaiementAnnule()
	{
		return $this->UrlRacine()."?".$this->NomParamResultat."=".urlencode($this->ValeurParamAnnule) ;
	}
	protected function CreeTransaction()
	{
		return new \Pv\InterfPaiement\Transaction() ;
	}
	protected function CreeCompteMarchand()
	{
		return new \Pv\InterfPaiement\CompteMarchand() ;
	}
	public function & Transaction()
	{
		return $this->_Transaction ;
	}
	public function IdTransaction()
	{
		return $this->_Transaction->IdTransaction ;
	}
	public function & CompteMarchand()
	{
		return $this->_CompteMarchand ;
	}
	public function DefinitEtatExecution($id, $msgErreur="")
	{
		$this->_EtatExecution->Id = $id ;
		$this->_EtatExecution->TimestampCapt = date("U") ;
		$this->_EtatExecution->MessageErreur = $msgErreur ;
		if($this->EnregistrerTransaction == true)
		{
			$this->SauveTransaction() ;
		}
	}
	public function DefinitEtatExec($id, $msgErreur="")
	{
		$this->DefinitEtatExecution($id, $msgErreur) ;
	}
	public function IdEtatExecution()
	{
		return $this->_EtatExecution->Id ;
	}
	public function MsgEtatExecution()
	{
		return $this->_EtatExecution->MessageErreur ;
	}
	public function IdEtatExec()
	{
		return $this->IdEtatExecution() ;
	}
	public function TimetampCaptTransact()
	{
		return $this->_EtatExecution->TimestampCapt ;
	}
	protected function DetermineResultatPaiement()
	{
		$this->ValeurParamResultat = "" ;
		if(isset($_GET[$this->NomParamResultat]) && in_array($_GET[$this->NomParamResultat], array($this->ValeurParamTermine, $this->ValeurParamAnnule)))
		{
			$this->ValeurParamResultat = $_GET[$this->NomParamResultat] ;
		}
	}
	protected function RestaureTransactionEnCours()
	{
		$this->DetermineResultatPaiement() ;
		if($this->ValeurParamResultat == $this->ValeurParamTermine)
		{
			$this->RestaureTransactionSession() ;
			$this->ImporteFichCfgTransaction() ;
			$this->DefinitEtatExecution("termine") ;
		}
		elseif($this->ValeurParamResultat == $this->ValeurParamAnnule)
		{
			$this->RestaureTransactionSession() ;
			$this->ImporteFichCfgTransaction() ;
			$this->DefinitEtatExecution("annule") ;
		}
	}
	protected function ImporteTransactSoumiseSession()
	{
		$envoyerErr = 0 ;
		if(isset($_SESSION[$this->IDInstanceCalc."_Transaction"]))
		{
			$idDonnees = $this->_Transaction->IdDonnees ;
			$this->_Transaction = @unserialize($_SESSION[$this->IDInstanceCalc."_Transaction"]) ;
			$this->_Transaction->IdDonnees = $idDonnees ;
			unset($_SESSION[$this->IDInstanceCalc."_Transaction"]) ;
		}
		else
		{
			$envoyerErr = 1 ;
		}
		return $envoyerErr ;
	}
	protected function ImporteTransactSoumiseBd()
	{
		$envoyerErr = 0 ;
		$idTransact = \Pv\Misc::_GET_def("idTransactSoumise") ;
		if($this->UtiliserBdTransactionSoumise == 0 || $idTransact == "")
		{
			return 1 ;
		}
		$bd = $this->CreeBdTransaction() ;
		$lgn = $bd->FetchSqlRow("select * from ".$bd->EscapeTableName($this->NomTableTransactSoumise)." where id_transaction=:id", array("id" => $idTransact)) ;
		if(! is_array($lgn) || count($lgn) == 0)
		{
			return 1 ;
		}
		$idDonnees = $this->_Transaction->IdDonnees ;
		$this->_Transaction->ImporteParLgn($lgn) ;
		$this->_Transaction->IdDonnees = $idDonnees ;
		$bd->RunSql("delete from ".$bd->EscapeTableName($this->NomTableTransactSoumise)." where id_transaction=:id", array("id" => $idTransact)) ;
		return 0 ;
	}
	protected function ExporteTransactSoumiseSession()
	{
		$_SESSION[$this->IDInstanceCalc."_Transaction"] = serialize($this->_Transaction) ;
	}
	protected function ExporteTransactSoumiseBd()
	{
		$bd = $this->CreeBdTransaction() ;
		$ok = $bd->RunSql(
			"insert into ".$bd->EscapeTableName($this->NomTableTransactSoumise)." (id_transaction, nom_interface_paiement, designation, montant, monnaie, infos_suppl, cfg) values (".$bd->ParamPrefix."id_transaction, ".$bd->ParamPrefix."nom_interface_paiement, ".$bd->ParamPrefix."designation, ".$bd->ParamPrefix."montant, ".$bd->ParamPrefix."monnaie, ".$bd->ParamPrefix."infos_suppl, ".$bd->ParamPrefix."cfg)", 
			array_merge($this->_Transaction->ExporteVersLgn(), array("nom_interface_paiement" => $this->NomElementApplication))
		) ;
		return $ok ;
	}
	protected function DetermineTransactionSoumise()
	{
		$envoyerErr = $this->ImporteTransactSoumiseSession() ;
		if($envoyerErr == 1)
		{
			$envoyerErr = $this->ImporteTransactSoumiseBd() ;
		}
		if($this->_Transaction == null)
		{
			$envoyerErr = 1 ;
		}
		if($envoyerErr)
		{
			if($this->AfficherErreurs404 == 1)
			{
				Header("HTTP/1.0 401 Unauthorized\r\n") ;
			}
			else
			{
				$this->DefinitEtatExec("transaction_invalide", "Aucune transaction n'a ete soumise pour paiement.") ;
				$this->AfficheErreurHtml() ;
			}
			exit ;
		}
		else
		{
			// print_r($this->_Transaction) ;
			$this->ExporteFichCfgTransition() ;
		}
	}
	protected function PrepareTransaction()
	{
		$nomServiceVendu = $this->_Transaction->Cfg->NomServiceVendu ;
		$servsVendus = $this->ServsVendus() ;
		if($nomServiceVendu == '' || ! isset($servsVendus[$nomServiceVendu]))
		{
			$this->DefinitEtatExecution("svc_apr_paiement_inexistant", "Aucune action n'a ete definie pour le suivi du reglement de la transaction") ;
		}
		else
		{
			$servsVendus[$nomServiceVendu]->Prepare($this->_Transaction) ;
		}
	}
	protected function SoumetTransaction()
	{
	}
	protected function TermineTransaction()
	{
	}
	protected function TransactionEnCours()
	{
		return 0 ;
	}
	protected function TransactionEffectuee()
	{
		return $this->_EtatExecution->Id == "termine" || $this->TransactionReussie() || $this->TransactionEchouee() ;
	}
	protected function TransactionReussie()
	{
		return $this->_EtatExecution->Id == "paiement_reussi" ;
	}
	protected function TransactionEchouee()
	{
		return $this->_EtatExecution->Id == "paiement_echoue" || $this->_EtatExecution->Id == "paiement_exception" || $this->_EtatExecution->Id == "paiement_expire" ;
	}
	protected function TransactionAnnulee()
	{
		return $this->_EtatExecution->Id == "annule" || $this->_EtatExecution->Id == "paiement_annule" ;
	}
	protected function ConfirmeTransactionReussieAuto()
	{
		$this->ImporteFichCfgTransaction() ;
		$servsVendus = $this->ServsVendus() ;
		if($this->_Transaction->Cfg->NomServiceVendu != '' && isset($servsVendus[$this->_Transaction->Cfg->NomServiceVendu]))
		{
			$serviceVendu = & $servsVendus[$this->_Transaction->Cfg->NomServiceVendu] ;
			$serviceVendu->AdopteInterfPaiemt($this->_Transaction->Cfg->NomServiceVendu, $this) ;
			if($serviceVendu->EstEffectue($this->_Transaction))
			{
				$serviceVendu->Rembourse($this->_Transaction) ;
			}
			else
			{
				$serviceVendu->ConfirmeSucces($this->_Transaction) ;
			}
		}
		else
		{
			echo '<p style="color:red">'.$this->MsgPaiementNonFinalise.'</p>' ;
			exit ;
		}
	}
	protected function ConfirmeTransactionReussie()
	{
	}
	protected function ConfirmeTransactionEchoueeAuto()
	{
		$this->ImporteFichCfgTransaction() ;
		$servsVendus = $this->ServsVendus() ;
		if($this->_Transaction->Cfg->NomServiceVendu != '' && isset($servsVendus[$this->_Transaction->Cfg->NomServiceVendu]))
		{
			$serviceVendu = & $servsVendus[$this->_Transaction->Cfg->NomServiceVendu] ;
			$serviceVendu->AdopteInterfPaiemt($this->_Transaction->Cfg->NomServiceVendu, $this) ;
			$serviceVendu->ConfirmeEchec($this->_Transaction) ;
		}
	}
	protected function ConfirmeTransactionEchouee()
	{
	}
	protected function ConfirmeTransactionAnnuleeAuto()
	{
		$servsVendus = $this->ServsVendus() ;
		if($this->_Transaction->Cfg->NomServiceVendu != '' && isset($servsVendus[$this->_Transaction->Cfg->NomServiceVendu]))
		{
			$serviceVendu = & $servsVendus[$this->_Transaction->Cfg->NomServiceVendu] ;
			$serviceVendu->AdopteInterfPaiemt($this->_Transaction->Cfg->NomServiceVendu, $this) ;
			$serviceVendu->Annule($this->_Transaction) ;
		}
	}
	protected function ConfirmeTransactionAnnulee()
	{
	}
	protected function ConfirmeTransactionEnAttente()
	{
	}
	protected function ConfirmeTransactionInvalide()
	{
	}
	protected function ValideVerifTransact()
	{
		$this->DefinitEtatExecution("verification_ok") ;
	}
	protected function AfficheErreursTransaction()
	{
		if($this->_EtatExecution->Id != "paiement_reussi")
		{
			if($this->AfficherErreurs404 == 1)
			{
				Header("HTTP/1.0 401 Unauthorized ".$this->_EtatExecution->Id." ".$this->_EtatExecution->MessageErreur."\r\n") ;
			}
			else
			{
				$this->AfficheErreurHtml() ;
			}
			exit ;
		}
	}
	protected function AfficheErreurHtml()
	{
		echo '<!doctype html>
<html>
<head>
<title>'.$this->Titre.' - Erreur #'.$this->_EtatExecution->Id.'</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body align="center">
<h3>'.$this->Titre.'</h3>
<hr />
<h1>ERREUR #'.$this->_EtatExecution->Id.'</h1>
<p>'.strtoupper($this->MsgEtatExecution()).'</p>
</body>
</html>' ;
	}
	protected function SauveTransactionSession()
	{
		$_SESSION[$this->NomElementApplication."_id_transaction"] = $this->_Transaction->IdTransaction ;
	}
	protected function RestaureTransactionSession()
	{
		if(isset($_SESSION[$this->NomElementApplication."_id_transaction"]))
		{
			$this->_Transaction->IdTransaction = $_SESSION[$this->NomElementApplication."_id_transaction"] ;
			unset($_SESSION[$this->NomElementApplication."_id_transaction"]) ;
		}
	}
	public function Execute()
	{
		session_start();
		$this->VideCfgsTransactsExpirs() ;
		$this->RestaureTransactionEnCours() ;
		if($this->_Transaction->Montant > 0)
		{
			if($this->TransactionEnCours())
			{
				return ;
			}
			if($this->TransactionEffectuee())
			{
				if($this->TransactionReussie())
				{
					$this->ConfirmeTransactionReussieAuto() ;
					$this->ConfirmeTransactionReussie() ;
				}
				else
				{
					$this->ConfirmeTransactionEchoueeAuto() ;
					$this->ConfirmeTransactionEchouee() ;
				}
			}
			elseif($this->TransactionAnnulee())
			{
				$this->ConfirmeTransactionAnnuleeAuto() ;
				$this->ConfirmeTransactionAnnulee() ;
			}
			else
			{
				$this->ConfirmeTransactionEnAttente() ;
			}
			$this->TermineTransaction() ;
			return ;
		}
		$this->DefinitEtatExecution("verification_en_cours", "Verification de la conformite du paiement demande") ;
		$this->DetermineTransactionSoumise() ;
		$this->PrepareTransaction() ;
		if($this->_EtatExecution->Id == "verification_ok")
		{
			$this->SauveTransactionSession() ;
			$this->SoumetTransaction() ;
		}
		else
		{
			$this->ConfirmeTransactionInvalide() ;
			$this->AfficheErreursTransaction() ;
		}
	}
	public function PrepareProcessus()
	{
		$ok = true ;
		if($this->UtiliserBdTransactionSoumise == 1)
		{
			$ok = $this->ExporteTransactSoumiseBd() ;
		}
		else
		{
			$ok = false ;
		}
		if(! $ok)
		{
			$this->ExporteTransactSoumiseSession() ;
		}
		return $ok ;
	}
	public function DemarreProcessus()
	{
		$this->PrepareProcessus() ;
		$urlRedirect = $this->UrlRacine() ;
		if($this->UtiliserBdTransactionSoumise == 1)
		{
			$urlRedirect .= '?idTransactSoumise='.urlencode($this->_Transaction->IdTransaction) ;
		}
		\Pv\Misc::redirect_to($urlRedirect) ;
	}
	public function RemplitTablTransactsPaie(& $tabl)
	{
		$bd = $this->CreeBdTransaction() ;
		$tabl->FournisseurDonnees = new PvFournisseurDonneesSql() ;
		$tabl->FournisseurDonnees->BaseDonnees = $this->CreeBdTransaction() ;
		$tabl->FournisseurDonnees->RequeteSelection = $this->NomTableTransaction ;
		$this->FltIdTransactPaie = $tabl->InsereFltSelectHttpGet("id_transaction", "id_transaction = <self>") ;
		$this->FltIdTransactPaie->Libelle = "N&deg; Transaction" ;
		$this->FltDesign = $tabl->InsereFltSelectHttpGet("designation", $bd->SqlIndexOf("designation", "<self>").' > 0') ;
		$this->FltDesign->Libelle = "Designation" ;
		$tabl->SensColonneTri = "desc" ;
		$tabl->InsereDefColCachee("nom_interf_paiemt") ;
		$tabl->InsereDefColTimestamp("timestamp_etat", "Date") ;
		$tabl->InsereDefColHtml('${interf_paiemt}', "Moyen de paiement") ;
		$tabl->InsereDefCol("id_transaction", "N&deg; Transaction") ;
		$tabl->InsereDefCol("designation", "Designation") ;
		$tabl->InsereDefColHtml('${titre_etat}', "Etat") ;
		$tabl->InsereDefCol("montant", "Montant") ;
		$tabl->InsereDefColCachee("id_etat") ;
		$tabl->SourceValeursSuppl = new \Pv\InterfPaiement\SrcValsSupplTransact() ;
		$tabl->SourceValeursSuppl->InterfPaiemtParent = & $this ;
	}
	public function ControleTransactionsEnAttente()
	{
		if($this->EnregistrerTransaction == 0)
		{
			return ;
		}
		$bd = $this->CreeBdTransaction() ;
		$lgnsTransact = array() ;
		do
		{
			$lgnsTransact = $bd->LimitSqlRows(
				"select * from ".$bd->EscapeTableName($this->NomTableTransaction)." where nom_interf_paiemt=".$bd->ParamPrefix."nom and id_etat not in ('paiement_reussi', 'paiement_annule', 'paiement_echoue', 'paiement_expire') and timestamp_etat + ".$this->DelaiControleTransacts." <= ".date("U"),
				array("nom" => $this->NomElementApplication),
				0, $this->MaxTransactsAControler
			) ;
			if(! is_array($lgnsTransact))
			{
				break ;
			}
			foreach($lgnsTransact as $i => $lgnTransact)
			{
				$this->_Transaction = $this->CreeTransaction() ;
				$this->_Transaction->ImporteParLgn($lgnTransact) ;
				$this->_EtatExecution->Id = $lgnTransact["id_etat"] ;
				$this->_EtatExecution->TimestampCapt = $lgnTransact["timestamp_etat"] ;
				$this->ControleTransactionEnAttente() ;
				if(! in_array($this->_EtatExecution->Id, array('paiement_reussi', 'paiement_annule', 'paiement_echoue', 'paiement_expire', 'paiement_exception')))
				{
					$this->DefinitEtatExecution("verification_ok") ;
				}
				else
				{
					if($this->TransactionEffectuee())
					{
						if($this->TransactionReussie())
						{
							$this->ConfirmeTransactionReussieAuto() ;
							$this->ConfirmeTransactionReussie() ;
						}
						else
						{
							$this->ConfirmeTransactionEchoueeAuto() ;
							$this->ConfirmeTransactionEchouee() ;
						}
					}
					elseif($this->TransactionAnnulee())
					{
						$this->ConfirmeTransactionAnnuleeAuto() ;
						$this->ConfirmeTransactionAnnulee() ;
					}
					$this->TermineTransaction() ;
				}
			}
		}
		while(is_array($lgnsTransact) && count($lgnsTransact) > 0) ;
	}
	protected function ControleTransactionEnAttente()
	{
	}
}
