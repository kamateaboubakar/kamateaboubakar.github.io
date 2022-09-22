<?php

namespace Pv\ZoneWeb\Commande ;

class InscriptionMembre extends AjoutElement
{
	public function ExecuteInstructions()
	{
		parent::ExecuteInstructions() ;
		$form = & $this->FormulaireDonneesParent ;
		$membership = & $form->ZoneParent->Membership ;
		$script = & $form->ScriptParent ;
		$email = ($membership->LoginWithEmail == 0) ? $script->FiltreEmail->Lie() : $script->FiltreLogin->Lie() ;
		$membership = & $script->ZoneParent->Membership ;
		if($this->StatutExecution == 1 && $script->DoitConfirmMail())
		{
			$params = $form->ExtraitValeursParametre($form->FiltresEdition) ;
			$paramsUrlConfirm = array(
				"login_confirm" => $script->FiltreLogin->Lie(),
				"email_confirm" => $email,
				"code_confirm" => $script->CodeConfirmMail(),
			) ;
			if($script->AutoriserUrlsRetour == 1)
			{
				$paramsUrlConfirm[$script->NomParamUrlRetour] = $script->ValeurUrlRetour ;
			}
			$params["url"] = $script->ObtientUrlParam($paramsUrlConfirm) ;
			$sujetMail = _parse_pattern($script->SujetMailConfirm, $params) ;
			$corpsMail = _parse_pattern($script->CorpsMailConfirm, $params) ;
			send_html_mail($email, $sujetMail, $corpsMail, $script->EmailEnvoiConfirm) ;
			$this->ConfirmeSucces($script->MsgSuccesEnvoiMailConfirm) ;
		}
		elseif($this->StatutExecution == 1)
		{
			$row = $membership->FetchMemberRowByLogin($script->FiltreLogin->Lie()) ;
			if($script->EnvoiMailSucces == 1)
			{
				$row["login_member"] = $script->FiltreLogin->Lie() ;
				$row["password_member"] = $script->FiltreMotPasse->Lie() ;
				$sujetMail = _parse_pattern($script->SujetMailSuccesConfirm, $row) ;
				$corpsMail = _parse_pattern($script->CorpsMailSuccesConfirm, $row) ;
				send_html_mail($email, $sujetMail, $corpsMail, $script->EmailEnvoiConfirm) ;
			}
			if($script->ConnecterNouveauMembre == 1 || ($script->AutoriserUrlsRetour== 1 && $script->ValeurUrlRetour != ''))
			{
				$script->AutoConnecteNouveauMembre($row["MEMBER_ID"]) ;
				if($script->AutoriserUrlsRetour== 1 && $script->ValeurUrlRetour != '')
				{
					redirect_to($script->ValeurUrlRetour) ;
				}
				else
				{
					redirect_to($script->UrlAutoConnexionMembre) ;
				}
			}
			else
			{
				$form->AnnuleLiaisonParametres() ;
			}
		}
	}
}
