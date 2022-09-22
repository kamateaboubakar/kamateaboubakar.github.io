<?php

namespace Pv\InterfPaiement ;

class Transaction
{
	public $IdDonnees ;
	public $IdTransaction ;
	public $Designation ;
	public $Montant ;
	public $Langage ;
	public $Monnaie ;
	public $InfosSuppl ;
	public $ContenuRetourBrut = null ;
	public $Cfg = null ;
	public function __construct()
	{
		$this->IdTransaction = uniqid() ;
		$this->Cfg = new \Pv\InterfPaiement\CfgTransact() ;
	}
	public function ImporteParLgn($lgn)
	{
		$this->IdDonnees = $lgn["id"] ;
		$this->IdTransaction = $lgn["id_transaction"] ;
		$this->Designation = $lgn["designation"] ;
		$this->Montant = $lgn["montant"] ;
		$this->Monnaie = $lgn["monnaie"] ;
		// $this->InfosSuppl = $lgn["infos_suppl"] ;
		$this->ContenuRetourBrut = null ;
		$this->Cfg = new \Pv\InterfPaiement\CfgTransact() ;
		if($lgn["contenu_brut"] != '')
		{
			$transactTemp = @unserialize($lgn["contenu_brut"]) ;
			if($transactTemp != null)
			{
				$this->Cfg = $transactTemp->Cfg ;
			}
		}
	}
	public function ExporteVersLgn()
	{
		return array(
			"id_transaction" => $this->IdTransaction,
			"designation" => $this->Designation,
			"montant" => $this->Montant,
			"monnaie" => $this->Monnaie,
			"infos_suppl" => $this->InfosSuppl,
			"cfg" => ($this->Cfg != null) ? serialize($this->Cfg) : ''
		) ;
	}
}