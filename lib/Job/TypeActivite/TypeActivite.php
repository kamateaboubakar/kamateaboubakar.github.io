<?php

namespace Rpa2p\Job\TypeActivite ;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class TypeActivite
{
	public $ResultExec ;
	public $MaxEssais = 2 ;
	public $NomsFiltreEdit = array() ;
	public function DoitReessayer()
	{
		return false ;
	}
	public function Id()
	{
		return 'base' ;
	}
	public function Titre()
	{
		return 'Base' ;
	}
	public function ClasseFa()
	{
		return 'fa fa-cog' ;
	}
	public function __construct()
	{
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
	public function Demarre(& $lgnActivit, & $bd, & $tacheProg)
	{
		$this->ResultExec = new ResultExec() ;
		$this->IdActivit = $lgnActivit["id"] ;
		$this->RepActivit = \Rpa2p\Config\Chemin::REP_ACTIVITES. DIRECTORY_SEPARATOR . $this->IdActivit ;
		$this->LogActivit = dirname(__FILE__)."/../".\Rpa2p\Config\Chemin::CHEM_REL_TACHE_BIN. DIRECTORY_SEPARATOR .\Rpa2p\Config\Chemin::LOG_ACTIVITES. DIRECTORY_SEPARATOR . $this->IdActivit ;
		if(is_dir($this->RepActivit))
		{
			chdir($this->RepActivit) ;
		}
		$this->PrepareExecution($lgnActivit, $bd, $tacheProg) ;
		$this->ExecuteInstructions($lgnActivit, $bd, $tacheProg) ;
		$this->TermineExecution($lgnActivit, $bd, $tacheProg) ;
	}
	public function PrepareExecution(& $lgnActivit, & $bd, & $tacheProg)
	{
	}
	public function ExecuteInstructions(& $lgnActivit, & $bd, & $tacheProg)
	{
	}
	public function TermineExecution(& $lgnActivit, & $bd, & $tacheProg)
	{
	}
	protected function ExtraitImgSurbrill()
	{
	}
	protected function EnvoieMail($subject, $body, $files=array(), $to='', $cc='')
	{
		$mail = new PHPMailer(\Rpa2p\Config\Mail::ENABLE_MAIL_EXCEPTS);

		try {
			//Server settings
			$oldSmtp = ini_set("SMTP", \Rpa2p\Config\Mail::HOTE_SMTP) ;
			$mail->SMTPDebug = \Rpa2p\Config\Mail::DEBUG_MAILS ;
			
			$mail->Host = \Rpa2p\Config\Mail::HOTE_SMTP ;
			$mail->Port = \Rpa2p\Config\Mail::PORT_SMTP ;
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

			//Recipients
			$mail->setFrom(\Rpa2p\Config\Mail::EXPEDIT_MAIL);
			$addrs = explode(", ", $to) ;
			if($to != "")
			{
				foreach($addrs as $i => $addr)
				{
					$mail->addAddress($addr);
				}
			}
			
			if($cc != "")
			{
				$addrs = explode(", ", $cc) ;
				foreach($addrs as $i => $addr)
				{
					$mail->addCC($addr);
				}
			}

			//Attachments
			foreach($files as $i => $filePath)
			{
				$mail->addAttachment($filePath);
			}

			//Content
			$mail->isHTML(true); //Set email format to HTML
			$mail->Subject = $subject ;
			$mail->Body = $body ;

			$mail->send();
			ini_set("SMTP", $oldSmtp) ;
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}}
