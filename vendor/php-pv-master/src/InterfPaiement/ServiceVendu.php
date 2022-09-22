<?php

namespace Pv\InterfPaiement ;

class ServiceVendu
{
	public $NomElementInterfPaiemt ;
	public $NomZoneWebRendu ;
	public $InterfPaiemtParent ;
	public $UrlSucces = "" ;
	public $UrlEchec = "" ;
	public $LargeurBoiteDialogue = "600" ;
	public function & ApplicationParent()
	{
		return $this->InterfPaiemtParent->ApplicationParent ;
	}
	protected function DefinitEtatExecution($id, $msg="")
	{
		$this->InterfPaiemtParent->DefinitEtatExecution($id, $msg) ;
	}
	protected function DefinitEtatExec($id, $msg="")
	{
		$this->InterfPaiemtParent->DefinitEtatExec($id, $msg) ;
	}
	public function AdopteInterfPaiemt($nom, & $interf)
	{
		$this->NomElementInterfPaiemt = $nom ;
		$this->InterfPaiemtParent = & $interf ;
		$this->ApplicationParent = & $interf->ApplicationParent ;
	}
	public function Prepare(& $transaction)
	{
	}
	public function EstEffectue(& $transaction)
	{
		return 0 ;
	}
	public function Rembourse(& $transaction)
	{
	}
	public function ConfirmeSucces(& $transaction)
	{
	}
	public function ConfirmeEchec(& $transaction)
	{
	}
	public function Annule(& $transaction)
	{
	}
	protected function AfficheBoiteDialogue($niveau, $titre, $message="")
	{
		echo '<!doctype html>
<html>
<head>
<title>'.$titre.'</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
.boite-dialogue-0 { border : 1px solid #ea9797 }
.boite-dialogue-0 th { background-color : #ea9797 }
.boite-dialogue-1 { border : 1px solid #97c2ea }
.boite-dialogue-1 th { background-color : #97c2ea }
.boite-dialogue-2 { border : 1px solid #eadb97 }
.boite-dialogue-2 th { background-color : #eadb97 }
</style>
</head>
<body align="center" style="background-color:#EDEDED">
<table class="boite-dialogue-'.$niveau.'" align="center" width ="'.$this->LargeurBoiteDialogue.'" cellspacing=0 cellpadding="4">
<tr>
<th>'.$titre.'</th>
</tr>
<tr>
<td>'.$message.'</td>
</tr>
<tr>
<td align="center"><a href="'.(($niveau == 1) ? $this->UrlSucces : $this->UrlEchec).'">Terminer</a></td>
</tr>
</table>
</body>
</html>' ;
		exit ;
	}
}