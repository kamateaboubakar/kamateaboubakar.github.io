<?php

namespace Rpa2p\Job\TypeActivite ;

class ResultExec
{
	public $TotalSucces = 0 ;
	public $TotalEchecs = 0 ;
	public $Delai = 0 ;
	public $Statut = "" ;
	public $EstSucces = false ;
	public $CodeErreur = 0 ;
	public $MsgErreur = "" ;
	public $ContenuHtml = "" ;
	public $NomFichBrut = "" ;
	public $NomFichImage = "" ;
	public $ContenuIllustr = "" ;
	public $Infos = array() ;
	protected function AssigneCtnHtml($msgs='')
	{
		if(is_array($msgs))
		{
		}
		elseif(is_object($msgs))
		{
		}
		elseif(is_string($msgs))
		{
			if($msgs == '')
			{
				$msgs = 'Exécution réussie' ;
			}
			$msgs = array($msgs) ;
		}
		$this->ContenuHtml = join(", ", $msgs) ;
	}
	public function ConfirmeSucces($msgs='', $totalSucces=1, $ctnIllustr='')
	{
		$this->AssigneCtnHtml($msgs) ;
		$this->Statut = "PASS" ;
		$this->EstSucces = true ;
		$this->TotalSucces = $totalSucces ;
		$this->TotalEchecs = 0 ;
		$this->ContenuIllustr = $ctnIllustr ;
	}
	public function RenseigneErreur($msgs='', $totalEchecs=1, $totalSucces=0, $ctnIllustr='')
	{
		$this->AssigneCtnHtml($msgs) ;
		$this->Statut = "FAIL" ;
		$this->EstSucces = false ;
		$this->TotalEchecs = $totalEchecs ;
		$this->TotalSucces = $totalSucces ;
		$this->ContenuIllustr = $ctnIllustr ;
	}
}
