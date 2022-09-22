<?php

namespace Rpa2p\Job\TypeNotif ;

class TypeNotif
{
	public $ContenuHtmlExec ;
	public $ContenuImgsExec ;
	protected $CaptureImgs = false ;
	static $StylesAlerte = array(
		'info' => 'color:blue',
		'success' => 'color:green',
		'warning' => 'color:orange',
		'danger' => 'color:red',
	) ;
	public function Id()
	{
		return 'base' ;
	}
	public function Titre()
	{
		return 'Base' ;
	}
	public function TitreParam(& $lgn)
	{
		return $this->Titre() ;
	}
	public function RemplitFormEdit(& $form)
	{
	}
	public function AppliqueActCmdEdit(& $actCmd)
	{
	}
	public function ValideCritrEdit(& $critere)
	{
	}
	public function PrepareExecJob(& $lgnJob, & $bd, & $tacheProg)
	{
		$this->ContenuHtmlExec = '' ;
		$this->ContenuHtmlExec = '<table width="100%" cellspacing="0" cellpadding="4" border=1 bordercolor="#4E4E4E" class="table table-striped">
	<thead>
	<tr>
	<th>Activit&eacute;</th>
	<th>Statut</th>
	<th>Delai (s)</th>
	<th>Details</th>
	</tr>
	</thead>
	<tbody>' ;
	}
	public function AnalyseExecActivit(& $lgn, & $typeActivite, & $bd, & $tacheProg)
	{
		$this->ContenuHtmlExec .= '<tr>
	<th align="left">'.htmlentities($lgn["titre"]).'</th>
	<td align="center" style="color:'.$tacheProg->ColorStatusText($typeActivite->ResultExec->Statut).'">'.(($typeActivite->ResultExec->EstSucces) ? "R&eacute;ussi" : "Echou&eacute;").'</td>
	<td align="right">'.$typeActivite->ResultExec->Delai.'</td>
	<td align="left">' ;
		if(count($typeActivite->ResultExec->Infos) > 0)
		{
			$totInfos = 0 ;
			foreach($typeActivite->ResultExec->Infos as $n => $info)
			{
				if($totInfos > 0)
				{
					$this->ContenuHtmlExec .= "; " ;
				}
				$styleCss = (isset(TypeNotif::$StylesAlerte[$info->NiveauAlerte])) ? TypeNotif::$StylesAlerte[$info->NiveauAlerte] : '' ;
				$this->ContenuHtmlExec .= '<b style="background-color:yellow">'.htmlentities($n).'</b> : <span style="'.$styleCss.'" class="text-'.htmlspecialchars($info->NiveauAlerte).'">'.htmlentities($info->Valeur).'</span>'.PHP_EOL ;
				$totInfos ++ ;
			}
			$this->ContenuHtmlExec .= '<br>' ;
		}
		$this->ContenuHtmlExec .= $typeActivite->ResultExec->ContenuHtml ;
		$this->ContenuHtmlExec .= '</td>
	</tr>'.PHP_EOL ;
		if($this->CaptureImgs == true)
		{
			$this->ContenuImgsExec .= '<p align="center">'.PHP_EOL ;
			if($typeActivite->ResultExec->NomFichImage != "")
			{
				$ctnBrutImg = base64_encode(file_get_contents($typeActivite->RepActivit.DIRECTORY_SEPARATOR . $typeActivite->ResultExec->NomFichImage)) ;
				$this->ContenuImgsExec .= '<img src="data:image/png;base64,'.$ctnBrutImg.'" /><br>'.PHP_EOL ;
			}
			elseif($typeActivite->ResultExec->ContenuIllustr != '')
			{
				$this->ContenuImgsExec .= '<pre>'.$typeActivite->ResultExec->ContenuIllustr.'</pre>' ;
			}
			elseif($typeActivite->ResultExec->ContenuHtml != '')
			{
				$this->ContenuImgsExec .= $typeActivite->ResultExec->ContenuHtml ;
			}
			else
			{
				$this->ContenuImgsExec .= "(Aucun aperçu)" ;
			}
			$this->ContenuImgsExec .= '<br>
<b>'.htmlentities($lgn["titre"]).'</b></p>' ;
		}
	}
	public function TermineExecJob(& $lgnJob, & $bd, & $tacheProg)
	{
		$this->ContenuHtmlExec .= '</tbody>
</table>
<br>' ;
		if($this->CaptureImgs)
		{
			$this->ContenuHtmlExec .= $this->ContenuImgsExec ;
		}
		$this->EnvoieAlerte($lgnJob, $bd, $tacheProg) ;
	}
	protected function EnvoieAlerte(&$lgnJob, &$bd, &$tacheProg)
	{
	}
	public function ContenuHtml()
	{
		return $this->ContenuHtmlExec ;
	}
}
