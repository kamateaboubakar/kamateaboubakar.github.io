<?php

namespace Rpa2p\Job\TypeNotif ;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email extends TypeNotif
{
	protected $CaptureImgs = true ;
	public function Id()
	{
		return 'email' ;
	}
	public function Titre()
	{
		return 'Email' ;
	}
	public function TitreParam(& $lgn)
	{
		$ctn = 'Envoyer un mail' ;
		if($lgn["param1_notif"] != '')
		{
			$ctn .= ' à: '.htmlentities($lgn["param1_notif"]) ;
		}
		if($lgn["param2_notif"] != '')
		{
			$ctn .= ' en CC : '.htmlentities($lgn["param2_notif"]) ;
		}
		return $ctn ;
	}
	public function RemplitFormEdit(& $form)
	{
		$this->FltTo = $form->InsereFltEditHttpPost("param1_notif", "param1_notif") ;
		$this->FltTo->Libelle = 'Destinataires (séparer par ", ")' ;
		$comp = $this->FltTo->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$comp->TotalLignes = 4 ;
		$this->FltCc = $form->InsereFltEditHttpPost("param2_notif", "param2_notif") ;
		$this->FltCc->Libelle = 'CC (séparer par ", ")' ;
		$comp = $this->FltCc->RemplaceComposant(new \Pv\ZoneWeb\FiltreDonnees\Composant\ZoneMultiligne) ;
		$comp->TotalLignes = 4 ;
	}
	public function EnvoieAlerte(& $lgnJob, & $bd, & $tacheProg)
	{
		$sujet = $this->ExtraitSujetMailJob($lgnJob) ;
		$this->EnvoieMailJob($sujet, $lgnJob) ;
	}
	public function ExtraitSujetMailJob($lgnJob)
	{
		$sujet = '' ;
		$tauxEchec = intval($lgnJob["total_echecs"] / ($lgnJob["total_echecs"] + $lgnJob["total_succes"]) * 100) ;
		$sujet = $lgnJob["total_succes"]." / ".($lgnJob["total_echecs"] + $lgnJob["total_succes"])." Reussi(s) - ".$lgnJob["titre_job"]." - ".$lgnJob["titre_application"]." le ".date("d/m/Y H:i:s") ;
		return $sujet ;
	}
	protected function EnvoieMailJob($sujet, $lgn)
	{
		$dests = $lgn["param1_notif"] ;
		$cc = $lgn["param2_notif"] ;
		$this->EnvoieMail(
			$sujet,
			'<p>Bonjour,</p>
<p>Ci-dessous le statut des activit&eacute;s RPA de '.htmlentities($lgn["titre_job"]).' - '.htmlentities($lgn["titre_application"]).' :</p>'.PHP_EOL . $this->ContenuHtmlExec. PHP_EOL .
'<p>Cordialement</p>',
			array(),
			(($dests != '') ? $dests : \Rpa2p\Config\Mail::DESTS_MAIL),
			(($cc != '') ? $cc : \Rpa2p\Config\Mail::CC_MAIL)
		) ;
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
			/*
			foreach($files as $i => $filePath)
			{
				$mail->addAttachment($filePath);
			}
			*/

			//Content
			$mail->isHTML(true); //Set email format to HTML
			$mail->Subject = $subject ;
			$mail->Body = $body ;

			$mail->send();
			ini_set("SMTP", $oldSmtp) ;
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}	
}
