<?php

namespace Rpa2p\Job\TypeActivite ;

class RobotFramework extends TypeActivite
{
	public $NomsFiltreEdit = array("nom_fich_robot") ;
	public $DernMsgErreur = "" ;
	public $MaxEssais = 8 ;
	public function Id()
	{
		return 'robotframework' ;
	}
	public function Titre()
	{
		return 'RobotFramework' ;
	}
	public function ClasseFa()
	{
		return 'fas fa-robot' ;
	}
	public function RemplitFormEdit(& $form)
	{
		$this->FltFichRobot = $form->InsereFltEditHttpUpload("nom_fich_robot", \Rpa2p\Config\Chemin::UPLOAD_ACTIVITES, "") ;
		$this->FltFichRobot->Libelle = "Fichier ROBOT" ;
		$this->FltFichRobot->ExtensionsAcceptees = array("robot") ;
		$this->NomsFiltreEdit[] = "nom_fich_robot" ;
	}
	public function AppliqueActCmdEdit(& $actCmd)
	{
		$cmd = & $actCmd->CommandeParent ;
		$form = & $cmd->FormulaireDonneesParent ;
		if($this->FltFichRobot->Invisible == false)
		{
			$chemFichRobot = $this->FltFichRobot->Lie() ;
			if($form->InclureElementEnCours == false)
			{
				$bd = $form->ScriptParent->CreeBdPrinc() ;
				$idActivit = $bd->FetchSqlValue('select max(id) id from rpapp_activite where id_job=:0', array($form->ScriptParent->FltJob->Lie()), "id") ;
			}
			else
			{
				$idActivit = $form->ScriptParent->FltId->Lie() ;
			}
			$repActivit = \Rpa2p\Config\Chemin::REP_ACTIVITES. DIRECTORY_SEPARATOR . $idActivit ;
			if(! is_dir($repActivit))
			{
				mkdir($repActivit) ;
			}
			if($chemFichRobot == '')
			{
				return ;
			}
			file_put_contents(
				$repActivit. DIRECTORY_SEPARATOR . "main.robot",
				$form->ApplicationParent->EncrypteFich(file_get_contents($chemFichRobot), \Rpa2p\Config\Cryptage::CLE_FICH_ROBOT)
			) ;
			$logActivit = \Rpa2p\Config\Chemin::LOG_ACTIVITES. DIRECTORY_SEPARATOR . $idActivit ;
			if(! is_dir($logActivit))
			{
				mkdir($logActivit) ;
			}
		}
	}
	public function ValideCritrEdit(& $critere)
	{
		if($this->FltFichRobot->Invisible == false)
		{
			$chemFichRobot = $this->FltFichRobot->Lie() ;
			if($chemFichRobot == '' && $critere->FormulaireDonneesParent->InclureElementEnCours == false)
			{
				$critere->MessageErreur = 'Veuillez specifier un fichier ROBOT valide' ;
				return false ;
			}
		}
	}
	public function DoitReessayer()
	{
		// echo "Err : ".$this->DernMsgErreur.PHP_EOL.PHP_EOL ;
		return (stripos($this->DernMsgErreur, "net::ERR_CONNECTION_RESET") !== false || stripos($this->DernMsgErreur, "InvalidElementStateException") !== false) ;
	}
	public function ExecuteInstructions(& $lgn, & $bd, & $tacheProg)
	{
		$this->VideLogsRobot() ;
		$this->DernMsgErreur = "" ;
		$ctnRobot = $tacheProg->ApplicationParent->DecrypteFich(file_get_contents($this->RepActivit.DIRECTORY_SEPARATOR ."main.robot"), \Rpa2p\Config\Cryptage::CLE_FICH_ROBOT) ;
		foreach($tacheProg->VarsFich as $n => $v)
		{
			$ctnRobot = preg_replace('/^(\$\{'.preg_quote($n).'\})(\s+).+/i', '\1\2'.$v, $ctnRobot) ;
			$ctnRobot = preg_replace('/^(\$\{'.preg_quote($n).' query string\})(\s+).+/i', '\1\2'.urlencode($v), $ctnRobot) ;
			$ctnRobot = preg_replace('/^(\$\{'.preg_quote($n).' html\})(\s+).+/i', '\1\2'.htmlentities($v), $ctnRobot) ;
			$ctnRobot = preg_replace('/^(\$\{'.preg_quote($n).' attr html\})(\s+).+/i', '\1\2'.htmlspecialchars($v), $ctnRobot) ;
		}
		file_put_contents($this->RepActivit.DIRECTORY_SEPARATOR ."instance.robot", $ctnRobot) ;
		ob_start() ;
		shell_exec("robot ".$this->RepActivit.DIRECTORY_SEPARATOR ."instance.robot") ;
		ob_end_clean() ;
		unlink($this->RepActivit.DIRECTORY_SEPARATOR ."instance.robot") ;
		$outFile = new \Alk\RobotFramework\Output($this->RepActivit.DIRECTORY_SEPARATOR ."output.xml") ;
		$this->ResultExec->NomFichBrut = "output.xml" ;
		$dateFin = date("Y-m-d H:i:s") ;
		$this->ResultExec->Statut = $outFile->status() ;
		$this->ResultExec->EstSucces = $outFile->pass() ;
		$this->ResultExec->Delai = $outFile->elapsed() ;
		$this->ResultExec->TotalSucces = $outFile->passCount() ;
		$this->ResultExec->TotalEchecs = $outFile->failCount() ;
		$tests = $outFile->tests() ;
		$ctnKw = '' ;
		foreach($tests as $i => $test)
		{
			if($ctnKw != '')
			{
				$ctnKw .= '<br>' ;
			}
			$ctnKw .= '<b>'.htmlentities($test->name).'</b> : <span style="color:'.$tacheProg->colorStatusText($test->status).'">'.(($test->status == 'PASS') ? 'OK' : 'NOK').'</span> ('.$test->elapsed.' sec)' ;
		}
		$ctnDetails = $ctnKw ;
		if($ctnDetails != '' && $outFile->errorMessage != '')
		{
			$ctnDetails .= '<br>' ;
		}
		if($outFile->errorMessage != '')
		{
			$ctnDetails .= '<span style="color:maroon">'.htmlentities($outFile->errorMessage).'</span>' ;
			$this->DernMsgErreur = $outFile->errorMessage ;
			$this->ResultExec->MsgErreur = (strlen($outFile->errorMessage) > 100) ? substr($outFile->errorMessage, 0, 100)."..." : $outFile->errorMessage ;
		}
		$this->ResultExec->ContenuHtml = $ctnDetails ;
		// Recueil des infos
		$chemFichInfos = $this->RepActivit.DIRECTORY_SEPARATOR . "infos.txt" ;
		if(file_exists($chemFichInfos))
		{
			$lgnsInfos = explode("\n", file_get_contents($chemFichInfos)) ;
			foreach($lgnsInfos as $i => $lgnInfo)
			{
				$lgnInfo = trim($lgnInfo) ;
				if($lgnInfo == '')
				{
					continue ;
				}
				$info = explode(";", $lgnInfo, 3) ;
				$this->ResultExec->Infos[$info[0]] = new InfoResultExec($info) ;
			}
		}
		copy($this->RepActivit.DIRECTORY_SEPARATOR . "log.html", $this->LogActivit. DIRECTORY_SEPARATOR . "log.html") ;
		copy($this->RepActivit.DIRECTORY_SEPARATOR . "report.html", $this->LogActivit. DIRECTORY_SEPARATOR . "report.html") ;
	}
	protected function VideLogsRobot()
	{
		$dh = opendir($this->RepActivit) ;
		if(is_resource($dh))
		{
			while(($fileName = readdir($dh)) !== false)
			{
				if($fileName == "." || $fileName == "..")
				{
					continue ;
				}
				$fileInfos = pathinfo($fileName) ;
				if(! isset($fileInfos["extension"]) || ! in_array(strtolower($fileInfos["extension"]), array("jpg", "png", "log", "html", "xml", "txt")))
				{
					continue ;
				}
				unlink($this->RepActivit.DIRECTORY_SEPARATOR . $fileName) ;
			}
			closedir($dh) ;
		}
		$dh = opendir($this->LogActivit) ;
		if(is_resource($dh))
		{
			while(($fileName = readdir($dh)) !== false)
			{
				if($fileName == "." || $fileName == "..")
				{
					continue ;
				}
				unlink($this->LogActivit.DIRECTORY_SEPARATOR . $fileName) ;
			}
			closedir($dh) ;
		}
	}
	public function TermineExecution(& $lgnActivit, & $bd, & $tacheProg)
	{
		$this->ResultExec->NomFichImage = $this->ExtraitImgSurbrill() ;
		if($this->ResultExec->NomFichImage != '')
		{
			$imgInfos = pathinfo($this->ResultExec->NomFichImage) ;
			copy($this->RepActivit.DIRECTORY_SEPARATOR . $this->ResultExec->NomFichImage, $this->LogActivit.DIRECTORY_SEPARATOR . "result.".$imgInfos["extension"]) ;
		}
	}
	protected function ExtraitImgSurbrill()
	{
		$timestmp = 0 ;
		$nomFichImg = '' ;
		$dh = opendir($this->RepActivit) ;
		while(($fileName = readdir($dh)) !== false)
		{
			if($fileName == "." || $fileName == "..")
			{
				continue ;
			}
			$fileInfos = pathinfo($fileName) ;
			if(! isset($fileInfos["extension"]) || ! in_array(strtolower($fileInfos["extension"]), array("jpg", "png")))
			{
				continue ;
			}
			if(filemtime($this->RepActivit. DIRECTORY_SEPARATOR . $fileName) > $timestmp)
			{
				$timestmp = filemtime($this->RepActivit. DIRECTORY_SEPARATOR . $fileName) ;
				$nomFichImg = $fileName ;
			}
		}
		closedir($dh) ;
		return $nomFichImg ;
	}
}
