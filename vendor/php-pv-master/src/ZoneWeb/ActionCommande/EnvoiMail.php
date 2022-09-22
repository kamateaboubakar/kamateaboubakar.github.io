<?php

namespace Pv\ZoneWeb\ActionCommande ;

class EnvoiMail extends \Pv\ZoneWeb\ActionCommande\ActionCommande
{
	public $TypeMail = "html" ;
	public $De = "" ;
	public $A = "" ;
	public $Cc = "" ;
	public $Cci = "" ;
	public $FiltreA = null ;
	public $FiltreDe = null ;
	public $FiltreCc = null ;
	public $FiltreCci = null ;
	public $FormatSujetMessage = "" ;
	public $SujetMessage = "" ;
	public $FormatContenuMessage = "" ;
	public $ContenuMessage = "" ;
	public $PiecesJointes = array() ;
	protected function ConstruitContenuMessage()
	{
		$valeurFiltres = $this->FormulaireDonneesParent->ExtraitValeursFiltres($this->FiltresCibles) ;
		$this->SujetMessage = \Pv\Misc::_parse_pattern($this->FormatSujetMessage, $valeursFiltres) ;
		$this->ContenuMessage = \Pv\Misc::_parse_pattern($this->FormatContenuMessage, $valeursFiltres) ;
	}
	public function Execute()
	{
		if($this->FiltreDe != null)
		{
			$this->De = $this->FiltreDe->Lie() ;
		}
		if($this->FiltreA != null)
		{
			$this->A = $this->FiltreA->Lie() ;
		}
		if($this->FiltreCc != null)
		{
			$this->Cc = $this->FiltreCc->Lie() ;
		}
		if($this->FiltreCci != null)
		{
			$this->Cci = $this->FiltreCci->Lie() ;
		}
		$this->ConstruitMessage() ;
		if($this->TypeMail == 'text')
		{
			\Pv\Misc::send_plain_mail($this->A, $this->SujetMessage, $this->ContenuMessage, $this->De, $this->Cc, $this->Cci) ;
		}
		else
		{
			\Pv\Misc::send_mail_with_attachments($this->A, $this->SujetMessage, $this->ContenuMessage, $this->PiecesJointes, $this->De, $this->Cc, $this->Cci) ;
		}
	}
}