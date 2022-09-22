<?php

namespace Pv\ApiRestful\RouteMembership ;

class ChangeMotPasse extends \Pv\ApiRestful\Route\Filtrable
{
	public $AutoriserAjout = 0 ;
	public $AutoriserModif = 1 ;
	public $AutoriserSuppr = 0 ;
	public $MsgMotPasseIncorrect = "L'ancien mot de passe est incorrect" ;
	public $AutoriserDesact = 0 ;
	protected function PrepareExecution()
	{
		parent::PrepareExecution() ;
		$api = & $this->ApiParent ;
		$mb = & $this->ApiParent->Membership ;
		$bd = & $mb->Database ;
		$this->FltIdMembre = $this->InsereFltSelectFixe("id_connecte", $api->IdMembreConnecte(), $bd->EscapeVariableName($mb->IdMemberColumn)." = <self>") ;
		if($mb->PasswordMemberExpr == '')
		{
			$this->FltAncMotPasse = $this->InsereFltSelectHttpCorps("ancien_mot_passe", $bd->EscapeVariableName($mb->PasswordMemberColumn)." = <self>") ;
		}
		else
		{
			if(stripos($mb->PasswordMemberExpr, '<self>') !== false)
			{
				$expr = str_ireplace('<self>', $bd->EscapeVariableName($mb->PasswordMemberColumn), $mb->PasswordMemberExpr) ;
				$this->FltAncMotPasse = $this->InsereFltSelectHttpCorps("ancien_mot_passe", $expr." = <self>") ;
			}
			else
			{
				$this->FltAncMotPasse = $this->InsereFltSelectHttpCorps("ancien_mot_passe", $mb->PasswordMemberExpr."(".$bd->EscapeVariableName($mb->PasswordMemberColumn).") = <self>") ;
			}
		}
		$this->FltNouvMotPasse = $this->InsereFltEditHttpCorps("nouveau_mot_passe", "") ;
	}
	protected function AppliqueEdition()
	{
		$api = & $this->ApiParent ;
		$mb = & $this->ApiParent->Membership ;
		$bd = & $mb->Database ;
		if($mb->ValidateConnection($api->LoginMembreConnecte(), $this->FltAncMotPasse->Lie()))
		{
			$passwordVal = $bd->ParamPrefix."mot_passe" ;
			if($mb->PasswordMemberExpr != '')
			{
				if(stripos($mb->PasswordMemberExpr, "<self>") !== false)
				{
					$passwordVal = str_ireplace("<self>", $bd->ParamPrefix."mot_passe", $mb->PasswordMemberExpr) ;
				}
				else
				{
					$passwordVal = $mb->PasswordMemberExpr."(".$bd->ParamPrefix."mot_passe)" ;
				}
			}
			$ok = $bd->RunSql(
				"update ".$bd->EscapeTableName($mb->MemberTable)." set ".$bd->EscapeVariableName($mb->PasswordMemberColumn)."=".$passwordVal." where ".$bd->EscapeVariableName($mb->PasswordMemberColumn)."=".$bd->ParamPrefix."id",
				array(
					"mot_passe" => $this->FltNouvMotPasse->Lie(),
					"id" => $this->FltIdMembre->Lie(),
				)
			) ;
			if(! $ok)
			{
				$this->RenseigneException("Exception SQL : ".$bd->ConnectionException) ;
			}
			return $ok ;
		}
		else
		{
			$this->RenseigneErreur($this->MsgMotPasseIncorrect) ;
		}
	}
}