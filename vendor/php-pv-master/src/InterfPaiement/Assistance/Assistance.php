<?php

namespace Pv\InterfPaiement\Assistance ;

class Assistance extends \Pv\InterfPaiement\InterfPaiement
{
	public $NomTableAssistance = "transaction_assistance" ;
	public $Titre = "Assistance" ;
	public $CheminImage = "images/paiement-assistance.png" ;
	public $TitreDocument = "Paiement assist&eacute;" ;
	public $LargeurFormSoumetTransact = 600 ;
	public $NomZoneAdmin = "" ;
	public $LibelleEmail1 = "Email 1" ;
	public $LibelleEmail2 = "Email 2" ;
	public $LibelleTel1 = "T&eacute;l&eacute;phone 1" ;
	public $LibelleTel2 = "T&eacute;l&eacute;phone 2" ;
	public $MessagePrinc = "Veuillez entrer vos coordonn&eacute;es de contact. Nous vous assisterons dans la proc&eacute;dure de paiement." ;
	protected function CreeBdAssistance()
	{
		return $this->CreeBdTransaction() ;
	}
	public function NomFournisseur()
	{
		return "assistance" ;
	}
	protected function CreeTransaction()
	{
		return new \Pv\InterfPaiement\Assistance\Transaction() ;
	}
	protected function CreeCompteMarchand()
	{
		return new \Pv\InterfPaiement\Assistance\CompteMarchand() ;
	}
	protected function RestaureTransactionEnCours()
	{
		parent::RestaureTransactionEnCours() ;
		if($this->IdEtatExecution() == "termine" && isset($_SESSION["paiement_assistance_en_cours"]))
		{
			$this->_Transaction->IdTransaction = \Pv\Misc::_POST_def("id_transaction") ;
			$this->_Transaction->Montant = \Pv\Misc::_POST_def("montant") ;
			$this->_Transaction->Monnaie = \Pv\Misc::_POST_def("monnaie") ;
			$this->_Transaction->Designation = \Pv\Misc::_POST_def("designation") ;
			$email1 = \Pv\Misc::_POST_def("email1") ;
			$email2 = \Pv\Misc::_POST_def("email2") ;
			$tel1 = \Pv\Misc::_POST_def("tel1") ;
			$tel2 = \Pv\Misc::_POST_def("tel2") ;
			$bd = $this->CreeBdAssistance() ;
			$ok = $bd->InsertRow(
				$this->NomTableAssistance,
				array("id_transaction" => $this->_Transaction->IdTransaction, "email1" => $email1, "email2" => $email2, "tel1" => $tel1, "tel2" => $tel2)
			) ;
			if($ok)
			{
				$this->DefinitEtatExecution("paiement_reussi") ;
			}
			else
			{
				$this->DefinitEtatExecution("paiement_echoue", $bd->ConnectionException) ;
			}
			unset($_SESSION["paiement_assistance_en_cours"]) ;
		}
		else
		{
			$this->DefinitEtatExecution("paiement_echoue", "Requete de paiement incorrecte") ;
		}
	}
	protected function TransactionEnCours()
	{
		return 0 ;
	}
	protected function AnalyseTransactionPostee()
	{
	}
	protected function PrepareTransaction()
	{
		parent::PrepareTransaction() ;
		if($this->_EtatExecution->Id != "verification_en_cours")
		{
			return ;
		}
		$this->DefinitEtatExecution("verification_ok") ;
	}
	protected function SoumetTransaction()
	{
		$_SESSION["paiement_assistance_en_cours"] = 1 ;
		echo $this->CtnSoumetTransaction() ;			
	}
	protected function RenduEnteteDocument()
	{
		$ctn = '' ;
		$ctn .= '<!doctype html>'.PHP_EOL ;
		$ctn .= '<html>'.PHP_EOL ;
		$ctn .= '<head>'.PHP_EOL ; ;
		$ctn .= '<title>'.$this->TitreDocument.'</title>'.PHP_EOL ;
		$ctn .= '</head>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduPiedDocument()
	{
		$ctn = '' ;
		$ctn .= '</html>' ;
		return $ctn ;
	}
	protected function RenduEnteteCorpsDocument()
	{
		$ctn = '' ;
		$ctn .= '<body>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduPiedCorpsDocument()
	{
		$ctn = '' ;
		$ctn .= '</body>'.PHP_EOL ;
		return $ctn ;
	}
	protected function RenduFormSoumetTransaction()
	{
		$ctn = '' ;
		$ctn .= '<p>'.$this->MessagePrinc.'</p>'.PHP_EOL ;
		$ctn .= '<p>Transaction N&deg;'.htmlentities($this->_Transaction->IdTransaction).' : '.htmlentities($this->_Transaction->Designation).' ('.htmlentities($this->_Transaction->Montant).' '.$this->_Transaction->Monnaie.')</p>'.PHP_EOL ;
		$ctn .= '<form action="?'.$this->NomParamResultat.'='.urlencode($this->ValeurParamTermine).'" method="post">'.PHP_EOL ;
		$ctn .= '<table cellspacing="0" cellpadding="4" width="'.$this->LargeurFormSoumetTransact.'">'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<th width="33%" valign="top">'.PHP_EOL ;
		$ctn .= $this->LibelleEmail1. PHP_EOL ;
		$ctn .= '</th>'.PHP_EOL ; 
		$ctn .= '<td width="*" valign="top">'.PHP_EOL ;
		$ctn .= '<input type="email" name="email1" value="'.htmlspecialchars(\Pv\Misc::_POST_def("email1")).'" />'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<th valign="top">'.PHP_EOL ;
		$ctn .= $this->LibelleEmail2. PHP_EOL ;
		$ctn .= '</th>'.PHP_EOL ;
		$ctn .= '<td valign="top">'.PHP_EOL ;
		$ctn .= '<input type="email" name="email2" value="'.htmlspecialchars(\Pv\Misc::_POST_def("email2")).'" />'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<th valign="top">'.PHP_EOL ;
		$ctn .= $this->LibelleTel1. PHP_EOL ;
		$ctn .= '</th>'.PHP_EOL ;
		$ctn .= '<td valign="top">'.PHP_EOL ;
		$ctn .= '<input type="text" name="tel1" value="'.htmlspecialchars(\Pv\Misc::_POST_def("tel1")).'" />'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<th valign="top">'.PHP_EOL ;
		$ctn .= $this->LibelleTel2. PHP_EOL ;
		$ctn .= '</th>'.PHP_EOL ;
		$ctn .= '<td valign="top">'.PHP_EOL ;
		$ctn .= '<input type="text" name="tel2" value="'.htmlspecialchars(\Pv\Misc::_POST_def("tel2")).'" />'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '<tr>'.PHP_EOL ;
		$ctn .= '<td colspan="2" align="center">'.PHP_EOL ;
		$ctn .= '<input type="submit" value="Valider" />'.PHP_EOL ;
		$ctn .= '<input type="hidden" name="id_transaction" value="'.htmlspecialchars($this->_Transaction->IdTransaction).'" />'.PHP_EOL ;
		$ctn .= '<input type="hidden" name="designation" value="'.htmlspecialchars($this->_Transaction->Designation).'" />'.PHP_EOL ;
		$ctn .= '<input type="hidden" name="montant" value="'.htmlspecialchars($this->_Transaction->Montant).'" />'.PHP_EOL ;
		$ctn .= '<input type="hidden" name="monnaie" value="'.htmlspecialchars($this->_Transaction->Monnaie).'" />'.PHP_EOL ;
		$ctn .= '</td>'.PHP_EOL ;
		$ctn .= '</tr>'.PHP_EOL ;
		$ctn .= '</table>'.PHP_EOL ;
		$ctn .= '</form>'.PHP_EOL ;
		return $ctn ;
	}
	protected function CtnSoumetTransaction()
	{
		$ctn = '' ;
		$ctn .= $this->RenduEnteteDocument() ;
		$ctn .= $this->RenduEnteteCorpsDocument() ;
		$ctn .= $this->RenduFormSoumetTransaction() ;
		$ctn .= $this->RenduPiedCorpsDocument() ;
		$ctn .= $this->RenduPiedDocument() ;
		return $ctn ;
	}
	protected function ControleTransactionEnAttente()
	{
		// $this->DefinitEtatExecution("paiement_echoue") ;
	}
}
