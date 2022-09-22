<?php

namespace Pv\ApiRestful\Auth ;

class Distant extends Auth
{
	protected function CleIdMembre()
	{
		return get_class($this).'|Dm3rw6~' ;
	}
	protected function VideSessionsInactives(& $api)
	{
		$bd = $api->BDMembership() ;
		$membership = & $api->Membership ;
		$ok = $bd->RunSql('delete from '.$bd->EscapeTableName($api->NomTableSession).' where (remember = 0 and '.$bd->SqlAddSeconds('date_action', $api->DelaiExpirSession).' <= '.$bd->SqlNow().') or (remember = 1 and '.$bd->SqlAddDays('date_action', $api->TotalJoursExpirDevice).' <= '.$bd->SqlNow().')') ;
		// echo $bd->LastSqlText ;
		// print_r($bd) ;
		$this->MessageErreur = $bd->ConnectionException ;
		return $ok ;
	}
	public function IdentifieMembre(& $api, $login, $motPasse)
	{
		$bd = $api->BDMembership() ;
		$membership = & $api->Membership ;
		$idMembre = $membership->ValidateConnection($login, $motPasse) ;
		return $idMembre ;
	}
	public function CreeSession(& $api, $idMembre, $device, $sauvegarder=0)
	{
		$this->MessageErreur = "" ;
		if($this->MessageErreur != '')
		{
			return ;
		}
		$bd = $api->BDMembership() ;
		$membership = & $api->Membership ;
		$token = $api->CrypteurToken->encode($idMembre.".".date("YmdHis").".".uniqid()) ;
		$cleIdMembre = $this->CleIdMembre() ;
		$ok = $bd->RunSql(
			'insert into '.$bd->EscapeTableName($api->NomTableSession).' (member_id, token, date_creation, date_action, device, remember)
values ('.$bd->SqlEncrypt1($bd->ParamPrefix.'member_id', $bd->ParamPrefix.'key_member_id').', '.$bd->ParamPrefix.'token, '.$bd->SqlNow().', '.$bd->SqlNow().', '.$bd->ParamPrefix.'device, '.$bd->ParamPrefix.'remember)',
			array(
				"member_id" => $idMembre,
				"key_member_id" => $cleIdMembre,
				"token" => $token,
				"device" => $device,
				"remember" => ($sauvegarder == 1) ? 1 : 0
			)
		) ;
		$this->MessageErreur = $bd->ConnectionException ;
		if(! $ok)
		{
			return "" ;
		}
		return $token ;
	}
	public function SupprimeSession(& $api)
	{
		if($api->Requete->EnteteAuthType == 'bearer')
		{
			$token = $api->Requete->EnteteAuthCredentials ;
			$bd = $api->BDMembership() ;
			$ok = $bd->RunSql(
				'delete from '.$bd->EscapeVariableName($api->NomTableSession).' where token='.$bd->ParamPrefix.'token',
				array("token" => $token),
			) ;
			return $ok ;
		}
		else
		{
			return false ;
		}
	}
	public function ChargeSession(& $api)
	{
		$this->VideSessionsInactives($api) ;
		if($api->Requete->EnteteAuthType == 'bearer')
		{
			$token = $api->Requete->EnteteAuthCredentials ;
			$bd = $api->BDMembership() ;
			$cleIdMembre = $this->CleIdMembre() ;
			$idMembre = $bd->FetchSqlValue(
				'select '.$bd->SqlDecrypt1('member_id', $bd->ParamPrefix."key_member_id").' member_id from '.$bd->EscapeVariableName($api->NomTableSession).' where token='.$bd->ParamPrefix.'token',
				array(
					"token" => $token, "key_member_id" => $cleIdMembre
				),
				'member_id',
				0
			) ;
			if($idMembre !== null)
			{
				if($idMembre == 0 && $api->Membership->GuestMemberId > 0)
				{
					$idMembre = $api->Membership->GuestMemberId ;
				}
				if($idMembre > 0)
				{
					$api->Membership->LoadMember($idMembre) ;
					$bd->RunSql(
						'update '.$bd->EscapeVariableName($api->NomTableSession).' set date_action='.$bd->SqlNow().' where token='.$bd->ParamPrefix.'token',
						array("token" => $token)
					) ;
				}
			}
			else
			{
				$api->Reponse->ConfirmeErreurInterne() ;
			}
		}
	}
}
