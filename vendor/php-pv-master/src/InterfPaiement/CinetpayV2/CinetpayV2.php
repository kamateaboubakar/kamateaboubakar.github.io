<?php

namespace Pv\InterfPaiement\CinetpayV2 ;

class CinetpayV2 extends \Pv\InterfPaiement\InterfPaiement
{
	public $ApiKeyCompteMarchand = "" ;
	public $SiteIdCompteMarchand = "" ;
	public $ChannelsTransaction = "" ;
	public $Titre = "Cinetpay" ;
	public $TitreSoumetFormPaiement = "Paiement par Paypal" ;
	public $MsgSoumetFormPaiement  = "Paiement en cours, veuillez patienter..." ;
	public $CheminImage = "https://cinetpay.com/images/logo.png" ;
	public $NomTableTransactCinetpay = "transaction_cinetpay2" ;
	public function CreeBdCinetpay()
	{
		return $this->CreeBdTransaction() ;
	}
	public function UrlPaiementNotif()
	{
		return $this->UrlRacine()."?".$this->NomParamResultat."=notifie&idTransact=".urlencode($this->_Transaction->IdTransaction) ;
	}
	public function NomFournisseur()
	{
		return "cinetpay_v2" ;
	}
	protected function CreeTransaction()
	{
		$transact = new \Pv\InterfPaiement\CinetpayV2\Transaction() ;
		$transact->Channels = $this->ChannelsTransaction ;
		return $transact ;
	}
	protected function CreeCompteMarchand()
	{
		$compte = new \Pv\InterfPaiement\CinetpayV2\CompteMarchand() ;
		$compte->ApiKey = $this->ApiKeyCompteMarchand ;
		$compte->SiteId = $this->SiteIdCompteMarchand ;
		return $compte ;
	}
	protected function PrepareTransaction()
	{
		parent::PrepareTransaction() ;
		if($this->_EtatExecution->Id != "verification_en_cours")
		{
			return ;
		}
		$bd = $this->CreeBdCinetpay() ;
		$this->_Transaction->Montant = intval($this->_Transaction->Montant) ;
		if($this->_Transaction->Monnaie == "CFA")
		{
			$this->_Transaction->Monnaie = "XOF" ;
		}
		$ok = $bd->RunSql(
			"insert into ".$bd->EscapeTableName($this->NomTableTransactCinetpay)." (id_transaction, date_envoi, designation, montant, monnaie)
values (".$bd->ParamPrefix."idTransact, ".$bd->SqlNow().", ".$bd->ParamPrefix."designation, ".$bd->ParamPrefix."montant, ".$bd->ParamPrefix."monnaie)",
			array(
				"idTransact" => $this->_Transaction->IdTransaction,
				"designation" => $this->_Transaction->Designation,
				"montant" => $this->_Transaction->Montant,
				"monnaie" => $this->_Transaction->Monnaie,
			)
		) ;
		if(! $ok)
		{
			$this->DefinitEtatExec("verification_echoue", "Exception SQL : ".$bd->ConnectionException) ;
			return ;
		}
		$this->DefinitEtatExec("verification_ok") ;
	}
	protected function RenduEnteteDocument()
	{
		return '<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>'.$this->TitreSoumetFormPaiement.'</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
	.sdk {
		display: block;
		position: absolute;
		background-position: center;
		text-align: center;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
	}
</style>
<script src="https://cdn.cinetpay.com/seamless/main.js"></script>
<script type="text/javascript">
function checkout() {
	CinetPay.setConfig({
		apikey: '.json_encode($this->_CompteMarchand->ApiKey).',
		site_id: '.json_encode($this->_CompteMarchand->SiteId).',//YOUR_SITE_ID
		notify_url: "'.$this->UrlPaiementNotif().'",
		mode: "PRODUCTION"
	});
	CinetPay.getCheckout({
		transaction_id: document.getElementById("transaction_id").value,
		amount: parseInt(document.getElementById("amount").value),
		currency: document.getElementById("currency").value,
		invoice_data: {},
		metadata: "",
		lang: '.json_encode($this->_CompteMarchand->Langage).',
		channels: '.json_encode($this->_Transaction->Channels).',
		description: document.getElementById("description").value,
		customer_id: document.getElementById("customer_id").value,
		customer_name: document.getElementById("customer_name").value,
		customer_surname:document.getElementById("customer_surname").value,
		customer_email: document.getElementById("customer_email").value,
		customer_phone_number: document.getElementById("customer_phone_number").value,
		customer_address :document.getElementById("customer_address").value,
		customer_country : document.getElementById("customer_country").value,
		customer_state : document.getElementById("customer_state").value,
		customer_zip_code : document.getElementById("customer_zip_code").value
	});
	CinetPay.waitResponse(function(data) {
		document.getElementById("ctn_retour").value = JSON.stringify(data) ;
		if (data.status == "REFUSED") {
			document.getElementById("confirme_transact").action = "?'.$this->NomParamResultat.'='.urlencode($this->ValeurParamAnnule).'" ;
			document.getElementById("msg_err_retour").value = data.description ;
			document.getElementById("code_err_retour").value = data.message ;
			document.getElementById("confirme_transact").submit() ;
		} else if (data.status == "ACCEPTED") {
			document.getElementById("confirme_transact").action = "?'.$this->NomParamResultat.'='.urlencode($this->ValeurParamTermine).'" ;
			document.getElementById("confirme_transact").submit() ;
		}
	});
	CinetPay.onError(function(data) {
		document.getElementById("ctn_retour").value = JSON.stringify(data) ;
		document.getElementById("msg_err_retour").value = data.description ;
		document.getElementById("code_err_retour").value = data.message ;
		document.getElementById("confirme_transact").action = "?'.$this->NomParamResultat.'='.urlencode($this->ValeurParamAnnule).'" ;
		document.getElementById("confirme_transact").submit() ;
	});
}
</script>
</head>
<body align="center" onload="checkout()">' ;
	}
	protected function RenduEnteteCorpsDocument()
	{
		return '' ;
	}
	protected function RenduPiedCorpsDocument()
	{
		return '' ;
	}
	protected function RenduPiedDocument()
	{
		return '</body>
</html>' ;
	}
	protected function CtnHtmlSoumetTransaction()
	{
		$ctn = '' ;
		$ctn .= $this->RenduEnteteDocument().PHP_EOL ;
		$ctn .= $this->RenduEnteteCorpsDocument().PHP_EOL ;
		if($this->MsgSoumetFormPaiement != '')
		{
			$ctn .= '<div align="center">'.$this->MsgSoumetFormPaiement.'</div>'.PHP_EOL ;
		}
		$ctn .= '<div style="display:none">
<form id="confirme_transact" action="about:blank" method="post">
<input type="hidden" name="transaction_id" id="transaction_id" value="'.htmlspecialchars($this->_Transaction->IdTransaction).'" />
<input type="hidden" name="amount" id="amount" value="'.htmlspecialchars($this->_Transaction->Montant).'" />
<input type="hidden" name="description" id="description" value="'.htmlspecialchars($this->_Transaction->Designation).'" />
<input type="hidden" name="currency" id="currency" value="'.htmlspecialchars($this->_Transaction->Monnaie).'" />
<input type="hidden" name="customer_id" id="customer_id" value="'.htmlspecialchars($this->_Transaction->CustomerId).'" />
<input type="hidden" name="customer_name" id="customer_name" value="'.htmlspecialchars($this->_Transaction->CustomerName).'" />
<input type="hidden" name="customer_surname" id="customer_surname" value="'.htmlspecialchars($this->_Transaction->CustomerSurname).'" />
<input type="hidden" name="customer_phone_number" id="customer_phone_number" value="'.htmlspecialchars($this->_Transaction->CustomerPhoneNumber).'" />
<input type="hidden" name="customer_email" id="customer_email" value="'.htmlspecialchars($this->_Transaction->CustomerEmail).'" />
<input type="hidden" name="customer_address" id="customer_address" value="'.htmlspecialchars($this->_Transaction->CustomerAddress).'" />
<input type="hidden" name="customer_city" id="customer_city" value="'.htmlspecialchars($this->_Transaction->CustomerCity).'" />
<input type="hidden" name="customer_country" id="customer_country" value="'.htmlspecialchars($this->_Transaction->CustomerCountry).'" />
<input type="hidden" name="customer_state" id="customer_state" value="'.htmlspecialchars($this->_Transaction->CustomerState).'" />
<input type="hidden" name="customer_zip_code" id="customer_zip_code" value="'.htmlspecialchars($this->_Transaction->CustomerZipCode).'" />
<input type="hidden" name="ctn_retour" id="ctn_retour" value="" />
<input type="hidden" name="code_err_retour" id="code_err_retour" value="" />
<input type="hidden" name="msg_err_retour" id="msg_err_retour" value="" />
<input type="submit" value="Soumettre" />
</form>
</div>' ;
		$ctn .= $this->RenduPiedCorpsDocument().PHP_EOL ;
		$ctn .= $this->RenduPiedDocument().PHP_EOL ;
		return $ctn ;
	}
	protected function SoumetTransaction()
	{
		echo $this->CtnHtmlSoumetTransaction() ;
	}
	protected function RestaureTransactionEnCours()
	{
		$this->DetermineResultatPaiement() ;
		if($this->ValeurParamResultat == $this->ValeurParamTermine)
		{
			if(isset($_POST["transaction_id"]))
			{
				$this->_Transaction->IdTransaction = $_POST["transaction_id"] ;
				$this->ImporteFichCfgTransaction() ;
				$this->DefinitEtatExecution("termine") ;
				$this->AnalyseTransactionPostee() ;
			}
		}
		elseif($this->ValeurParamResultat == $this->ValeurParamAnnule)
		{
			if(isset($_POST["transaction_id"]))
			{
				$this->_Transaction->IdTransaction = $_POST["transaction_id"] ;
				$this->ImporteFichCfgTransaction() ;
				$this->DefinitEtatExecution("annule") ;
				$bd = $this->CreeBdCinetpay() ;
				$bd->RunSql(
					"update ".$bd->EscapeTableName($this->NomTableTransactCinetpay)." set date_annule=".$bd->SqlNow().", est_annule=1, ctn_res_retour=".$bd->ParamPrefix."ctnRetour, msg_err_retour=".$bd->ParamPrefix."msgErrRetour, code_err_retour=".$bd->ParamPrefix."codeErrRetour where id_transaction=:idTransact",
					array(
						"idTransact" => $this->_Transaction->IdTransaction,
						"ctnRetour" => $_POST["ctn_retour"],
						"msgErrRetour" => $_POST["msg_err_retour"],
						"codeErrRetour" => $_POST["code_err_retour"],
					)
				) ;
			}
		}
	}
	protected function AnalyseTransactionPostee()
	{
		$this->_Transaction->IdTransaction = $_POST["transaction_id"] ;
		$this->_Transaction->Montant = $_POST["amount"] ;
		$this->_Transaction->Monnaie = $_POST["currency"] ;
		$this->_Transaction->Designation = $_POST["description"] ;
		$bd = $this->CreeBdCinetpay() ;
		$ok = $bd->RunSql(
			"update ".$bd->EscapeTableName($this->NomTableTransactCinetpay)." set date_retour=".$bd->SqlNow().", est_regle = ".$bd->ParamPrefix."estRegle, ctn_res_retour=".$bd->ParamPrefix."ctnRetour where id_transaction=".$bd->ParamPrefix."idTransact",
			array(
				"idTransact" => $this->_Transaction->IdTransaction,
				"estRegle" => 1,
				"ctnRetour" => $_POST["ctn_retour"],
			)
		) ;
		if($ok)
		{
			$this->DefinitEtatExec("paiement_reussi") ;
		}
		else
		{
			$this->DefinitEtatExec("paiement_exception", "Exception SQL : ".$bd->ConnectionException) ;
		}
	}
}
