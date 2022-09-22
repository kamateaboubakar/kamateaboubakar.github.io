<?php

namespace Pv\Membership ;

class Sql extends \Pv\Membership\Membership
{
	public $Database ;
	public $IdMemberNotFoundValue = -1 ;
	public $MemberTable = "membership_member" ;
	public $EnableMemberColumn = "enabled" ;
	public $LoginWithEmail = 0 ;
	public $LockedMemberTrueValue = "1" ;
	public $LockedMemberColumn = "locked" ;
	public $TotalRetryMemberColumn = "total_retry" ;
	public $MaxConnectionRetry = 3 ;
	public $LockMemberEnabled = 0 ;
	public $IdMemberColumn = "id" ;
	public $IdMemberIgnoreUpdate = 1 ;
	public $IdMemberInsertExpr = "" ;
	public $LoginMemberCaseInsensitive = 1 ;
	public $LoginMemberColumn = "login_member" ;
	public $LoginMemberAlias = "" ;
	public $LoginMemberLabel = "Login" ;
	public $PasswordMemberColumn = "password_member" ;
	public $PasswordMemberExpr = "PASSWORD" ;
	public $PasswordMemberAlias = "" ;
	public $PasswordMemberLabel = "Mot de passe" ;
	public $EmailMemberColumn = "email" ;
	public $EmailMemberAlias = "" ;
	public $EmailMemberLabel = "Email" ;
	public $FirstNameMemberColumn = "first_name" ;
	public $FirstNameMemberAlias = "" ;
	public $FirstNameMemberLabel = "Prenom" ;
	public $LastNameMemberColumn = "last_name" ;
	public $LastNameMemberAlias = "" ;
	public $LastNameMemberLabel = "Nom" ;
	public $AddressMemberColumn = "address" ;
	public $AddressMemberAlias = "" ;
	public $AddressMemberLabel = "Adresse" ;
	public $ContactMemberColumn = "contact" ;
	public $ContactMemberAlias = "" ;
	public $ContactMemberLabel = "Contacts" ;
	public $ADActivatedMemberColumn = "" ;
	public $ADActivatedMemberAlias = "" ;
	public $ADActivatedMemberLabel = "Authentifier par AD" ;
	public $ADActivatedMemberTrueValue = "1" ;
	public $ADServerMemberColumn = '' ;
	public $ADServerMemberAlias = '' ;
	public $ADServerMemberLabel = 'Serveur Active Directory' ;
	public $EnableMemberAlias = "" ;
	public $EnableMemberLabel = "Activer" ;
	public $EnableMemberTrueValue = "1" ;
	public $MustChangePasswordMemberColumn = "" ;
	public $MustChangePasswordMemberAlias = "" ;
	public $MustChangePasswordMemberLabel = "Doit changer le mot de passe" ;
	public $MustChangePasswordMemberTrueValue = "1" ;
	public $ADServerTable = 'membership_ad_server' ;
	public $IdADServerColumn = 'id' ;
	public $IdADServerAlias = '' ;
	public $IdADServerLabel = 'ID' ;
	public $HostADServerColumn = 'host' ;
	public $HostADServerAlias = '' ;
	public $HostADServerLabel = 'Hote' ;
	public $PortADServerColumn = 'port' ;
	public $PortADServerAlias = '' ;
	public $PortADServerLabel = 'Port' ;
	public $DomainADServerColumn = 'domain' ;
	public $DomainADServerAlias = '' ;
	public $DomainADServerLabel = 'Domaine' ;
	public $DnADServerColumn = 'dn' ;
	public $DnADServerAlias = '' ;
	public $DnADServerLabel = 'DN' ;
	public $UseProtocolV3ADServerColumn = 'use_protocol_v3' ;
	public $UseProtocolV3ADServerAlias = '' ;
	public $UseProtocolV3ADServerLabel = 'Utiliser le protocole V3' ;
	public $UseProtocolV3ADServerDefaultValue = 1 ;
	public $FollowReferralsADServerColumn = 'follow_referrals' ;
	public $FollowReferralsADServerAlias = '' ;
	public $FollowReferralsADServerLabel = 'Suivre les redirections de connexion' ;
	public $FollowReferralsADServerDefaultValue = 1 ;
	public $EnableADServerColumn = "active" ;
	public $EnableADServerAlias = "" ;
	public $EnableADServerLabel = "Actif" ;
	public $DisableMemberOnDelete = 1 ;
	public $DisableRoleOnDelete = 1 ;
	public $DisableProfileOnDelete = 1 ;
	public $ProfileMemberColumn = "profile_id" ;
	public $ProfileMemberForeignKey = "id" ;
	public $ProfileMemberAlias = "" ;
	public $ProfileMemberLabel = "Profil" ;
	public $ProfileTable = "membership_profile" ;
	public $IdProfileInsertExpr = "" ;
	public $IdProfileIgnoreUpdate = 1 ;
	public $IdProfileColumn = "id" ;
	public $TitleProfileColumn = "title" ;
	public $TitleProfileAlias = "" ;
	public $TitleProfileFormatErrorLabel = "Le titre n'a pas le bon format" ;
	public $TitleProfileFormatErrorAlias = "" ;
	public $TitleProfileFoundErrorLabel = "Le titre d&eacute;fini est d&eacute;j&agrave; utilis&eacute;" ;
	public $TitleProfileFoundErrorAlias = "" ;
	public $TitleProfileLabel = "Titre" ;
	public $DescriptionProfileColumn = "description" ;
	public $DescriptionProfileAlias = "" ;
	public $DescriptionProfileLabel = "Description" ;
	public $EnableProfileColumn = "enabled" ;
	public $EnableProfileAlias = "" ;
	public $EnableProfileTrueValue = "1" ;
	public $EnableProfileLabel = "Activer" ;
	public $RoleListProfileLabel = "Roles" ;
	public $RoleListProfileAlias = "roles" ;
	public $PrivilegeTable = "membership_privilege" ;
	public $IdPrivilegeInsertExpr = "" ;
	public $IdPrivilegeIgnoreUpdate = 1 ;
	public $IdPrivilegeColumn = "id" ;
	public $EnablePrivilegeColumn = "active" ;
	public $EnablePrivilegeTrueValue = "1" ;
	public $ProfilePrivilegeColumn = "profile_id" ;
	public $ProfilePrivilegeForeignKey = "id" ;
	public $RolePrivilegeColumn = "role_id" ;
	public $RolePrivilegeForeignKey = "id" ;
	public $RoleTable = "membership_role" ;
	public $IdRoleColumn = "id" ;
	public $NameRoleColumn = "name" ;
	public $NameRoleLabel = "Code" ;
	public $NameRoleAlias = "" ;
	public $NameRoleFormatErrorLabel = "Le nom n'a pas le bon format" ;
	public $NameRoleFormatErrorAlias = "" ;
	public $NameRoleFoundErrorLabel = "Le nom d&eacute;fini est d&eacute;j&agrave; utilis&eacute;" ;
	public $NameRoleFoundErrorAlias = "" ;
	public $TitleRoleColumn = "title" ;
	public $TitleRoleAlias = "" ;
	public $TitleRoleLabel = "Titre" ;
	public $SimilarProfileFoundErrorLabel = "Un profil avec le m&ecirc;me titre existe d&eacute;j&agrave;" ;
	public $SimilarProfileFoundErrorAlias = "" ;
	public $SimilarRoleFoundErrorLabel = "Un r&ocirc;le avec le m&ecirc;me titre ou le m&ecirc;me nom existe d&eacute;j&agrave;" ;
	public $SimilarRoleFoundErrorAlias = "" ;
	public $DescriptionRoleColumn = "description" ;
	public $DescriptionRoleAlias = "" ;
	public $DescriptionRoleLabel = "Description" ;
	public $EnableRoleColumn = "enabled" ;
	public $EnableRoleLabel = "Activer" ;
	public $EnableRoleTrueValue = "1" ;
	public $EnableRoleAlias = "" ;
	public $ProfileListRoleLabel = "Profils" ;
	public $ProfileListRoleAlias = "" ;
	public $IdRoleInsertExpr = "" ;
	public $IdRoleIgnoreUpdate = 1 ;
	public $OldPasswordMemberLabel = "Mot de passe actuel" ;
	public $OldPasswordMemberAlias = "" ;
	public $NewPasswordMemberLabel = "Nouveau mot de passe" ;
	public $NewPasswordMemberAlias = "" ;
	public $ConfirmPasswordMemberLabel = "Confirmer le mot de passe" ;
	public $ConfirmPasswordMemberAlias = "" ;
	public $NewPasswordMemberFormatErrorLabel = "Le nouveau mot de passe n'a pas le bon format." ;
	public $NewPasswordMemberFormatErrorAlias = "" ;
	public $LoginMemberFormatErrorLabel = "Le login a un mauvais format" ;
	public $LoginMemberFormatErrorAlias = "" ;
	public $PasswordMemberFormatErrorLabel = "Le mot de passe a un mauvais format" ;
	public $PasswordMemberFormatErrorAlias = "" ;
	public $LastNameMemberFormatErrorLabel = "Le nom doit avoir au moins 2 caract&egrave;res et 255 au maximum" ;
	public $LastNameMemberFormatErrorAlias = "" ;
	public $FirstNameMemberFormatErrorLabel = "Le prenom doit avoir au moins 2 caract&egrave;res et 255 au maximum" ;
	public $FirstNameMemberFormatErrorAlias = "" ;
	public $EmailMemberFormatErrorLabel = "L'adresse email a un mauvais format" ;
	public $EmailMemberFormatErrorAlias = "" ;
	public $SimilarMemberFoundErrorLabel = "Un membre avec le m&ecirc;me login, le m&ecirc;me mot de passe ou le m&ecirc;me email existe d&eacute;j&agrave;" ;
	public $SimilarMemberFoundErrorAlias = "" ;
	public $ConfirmPasswordMemberMatchLabel = "Vous n'avez pas confirm&eacute; le mot de passe" ;
	public $ConfirmPasswordMemberMatchAlias = "" ;
	public $ChangePasswordMemberSameLabel = "L'ancien mot de passe et le nouveau ne peuvent pas etre pareils" ;
	public $ChangePasswordMemberSameAlias = "" ;
	public $OldPasswordMemberMatchLabel = "L'ancien mot de passe n'est pas correct" ;
	public $TitleRoleFormatErrorLabel = "Le titre du r&ocirc;le n'a pas le bon format" ;
	public $OldPasswordMemberMatchAlias = "" ;
	public $IdAlternateRoleAfterDelete = 0 ;
	public $IdAlternatePrivilegeAfterDelete = 0 ;
	public $LdapConnections = array() ;
	public $TriggerInsertProfileRow = 1 ;
	public $TriggerDeleteProfileRow = 1 ;
	public $ConfirmSetPasswordEnabled = 0 ;
	// The membership will insert into privilege tables the
	public $TriggerInsertRoleRow = 1 ;
	public $TriggerDeleteRoleRow = 1 ;
	public $LastValidateError = '' ;
	protected $LdapConn = false ;
	const VALIDATE_ERROR_NONE = "" ;
	const VALIDATE_ERROR_MEMBER_NOT_FOUND = "member_not_found" ;
	const VALIDATE_ERROR_MEMBER_NOT_ENABLED = "member_not_enabled" ;
	const VALIDATE_ERROR_PASSWORD_INCORRECT = "password_incorrect" ;
	const VALIDATE_ERROR_AD_AUTH_FAILED = "ad_auth_failed" ;
	const VALIDATE_ERROR_AD_SERVER_CONNECT_ERROR = "ad_auth_connect_error" ;
	const VALIDATE_ERROR_AD_PASSWORD_EMPTY = "ad_password_empty" ;
	const VALIDATE_ERROR_AD_SERVER_NOT_FOUND = "ad_server_not_found" ;
	const VALIDATE_ERROR_AD_SERVER_DISABLED = "ad_server_disabled" ;
	const VALIDATE_ERROR_OTHER = "member_connection_impossible" ;
	const VALIDATE_ERROR_DB_ERROR = "db_connection_failed" ;
	public function FetchSimilarRole($idRoleExclude, $nameRole='', $titleRole='')
	{
		$sql = 'select * from ('.$this->SqlAllRoles().') ROLE_TABLE where ROLE_ID <> '.$this->Database->ParamPrefix.'roleId and (ROLE_NAME = '.$this->Database->ParamPrefix.'roleName or ROLE_TITLE = '.$this->Database->ParamPrefix.'roleTitle)' ;
		$row = $this->Database->FetchSqlRow($sql, array('roleId' => $idRoleExclude, 'roleName' => $nameRole, 'roleTitle' => $titleRole)) ;
		return $row ;
	}
	public function FetchSimilarProfile($idProfileExclude, $titleProfile='')
	{
		$sql = 'select * from ('.$this->SqlAllProfiles().') PROFILE_TABLE where PROFILE_ID <> '.$this->Database->ParamPrefix.'profileId and PROFILE_TITLE = '.$this->Database->ParamPrefix.'profileTitle' ;
		$row = $this->Database->FetchSqlRow($sql, array('profileId' => $idProfileExclude, 'profileTitle' => $titleProfile)) ;
		return $row ;
	}
	public function FetchSimilarMember($idMemberExclude, $login, $password='', $email='')
	{
		$sql = 'select * from ('.$this->SqlAllMembers().') MEMBER_TABLE where MEMBER_ID <> '.$this->Database->ParamPrefix.'memberId and (MEMBER_LOGIN = '.$this->Database->ParamPrefix.'login OR MEMBER_PASSWORD = '.$this->Database->ParamPrefix.'memberPassword OR MEMBER_EMAIL='.$this->Database->ParamPrefix.'email)' ;
		$row = $this->Database->FetchSqlRow(
			$sql,
			array(
				'memberId' => $idMemberExclude,
				'login' => $login,
				'memberPassword' => $password,
				'email' => $email,
			)
		) ;
		return $row ;
	}
	public function ADActivatedMemberFalseValue()
	{
		return ($this->ADActivatedMemberTrueValue == "1") ? 0 : "1" ;
	}
	public function MustChangePasswordMemberFalseValue()
	{
		return ($this->MustChangePasswordMemberTrueValue == "1") ? 0 : "1" ;
	}
	public function EnableMemberFalseValue()
	{
		return ($this->EnableMemberTrueValue == "1") ? 0 : "1" ;
	}
	public function EnableProfileFalseValue()
	{
		return ($this->EnableProfileTrueValue == "1") ? 0 : "1" ;
	}
	public function EnablePrivilegeFalseValue()
	{
		return ($this->EnablePrivilegeTrueValue == "1") ? 0 : "1" ;
	}
	public function SqlAllMembers()
	{
		$sql = '' ;
		if($this->MemberTable == '' || $this->ProfileTable == '' || $this->IdMemberColumn == '' || $this->LoginMemberColumn == '' || $this->PasswordMemberColumn == '' || $this->IdProfileColumn == '')
		{
			die('Definition du membership non compl&ecirc;te !!!') ;
		}
		$sql .= 'SELECT 1 MEMBER_REQUEST' ;
		$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->IdMemberColumn).' MEMBER_ID' ;
		{
			$sql .= ', ' ;
			if($this->LoginMemberCaseInsensitive)
			{
				$sql .= 'lower(' ;
			}
			$sql .= $this->Database->EscapeFieldName("MEMBER_TABLE", $this->LoginMemberColumn) ;
			if($this->LoginMemberCaseInsensitive)
			{
				$sql .= ')' ;
			}
			$sql .= ' MEMBER_LOGIN' ;
		}
		$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->PasswordMemberColumn).' MEMBER_PASSWORD' ;
		if($this->EmailMemberColumn != '')
		{
			if($this->LoginWithEmail == 0)
			{
				$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->EmailMemberColumn).' MEMBER_EMAIL' ;
			}
			else
			{
				$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->LoginMemberColumn).' MEMBER_EMAIL' ;
			}
		}
		if($this->FirstNameMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->FirstNameMemberColumn).' MEMBER_FIRST_NAME' ;
		}
		if($this->LastNameMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->LastNameMemberColumn).' MEMBER_LAST_NAME' ;
		}
		if($this->AddressMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->AddressMemberColumn).' MEMBER_ADDRESS' ;
		}
		if($this->ContactMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->ContactMemberColumn).' MEMBER_CONTACT' ;
		}
		if($this->EnableMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->EnableMemberColumn).' MEMBER_ENABLE' ;
		}
		if($this->ADActivatedMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->ADActivatedMemberColumn).' MEMBER_AD_ACTIVATED' ;
		}
		if($this->MustChangePasswordMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->MustChangePasswordMemberColumn).' MEMBER_MUST_CHANGE_PASSWORD' ;
		}
		else
		{
			$sql .= ', 0 MEMBER_MUST_CHANGE_PASSWORD' ;
		}
		if($this->ADServerMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->ADServerMemberColumn).' MEMBER_AD_SERVER' ;
		}
		else
		{
			$sql .= ', \'\' MEMBER_AD_SERVER' ;
		}
		if($this->LockMemberEnabled && $this->LockedMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->LockedMemberColumn).' MEMBER_LOCKED' ;
		}
		else
		{
			$sql .= ', \'\' MEMBER_LOCKED' ;
		}
		if($this->LockMemberEnabled && $this->TotalRetryMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->TotalRetryMemberColumn).' MEMBER_TOTAL_RETRY' ;
		}
		else
		{
			$sql .= ', \'\' MEMBER_TOTAL_RETRY' ;
		}
		if($this->ProfileMemberColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->ProfileMemberColumn).' MEMBER_PROFILE' ;
		}
		$sql .= ', '.$this->Database->EscapeFieldName("PROFILE_TABLE", $this->IdProfileColumn).' PROFILE_ID' ;
		if($this->TitleProfileColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("PROFILE_TABLE", $this->TitleProfileColumn).' PROFILE_TITLE' ;
		}
		if($this->DescriptionProfileColumn != '')
		{
			$sql .= ', '.$this->Database->EscapeFieldName("PROFILE_TABLE", $this->DescriptionProfileColumn).' PROFILE_DESCRIPTION' ;
		}
		$sql .= $this->ExtraColsAllMembers() ;
		$sql .= ' FROM '.$this->Database->EscapeTableName($this->MemberTable).' MEMBER_TABLE LEFT JOIN '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE on '.$this->Database->EscapeFieldName("MEMBER_TABLE", $this->ProfileMemberColumn).' = '.$this->Database->EscapeFieldName("PROFILE_TABLE", $this->ProfileMemberForeignKey) ;
		$sql .= $this->ExtraExprAllMembers() ;
		return $sql ;
	}
	protected function ExtraColsAllMembers()
	{
	}
	protected function ExtraExprAllMembers()
	{
	}
	public function SqlAllRoles()
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= $this->Database->EscapeFieldName($this->RoleTable, $this->IdRoleColumn).' ROLE_ID' ;
		$sql .= ", ".(($this->NameRoleColumn != '') ? $this->Database->EscapeFieldName($this->RoleTable, $this->NameRoleColumn) : "''").' ROLE_NAME' ;
		$sql .= ", ".(($this->TitleRoleColumn != '') ? $this->Database->EscapeFieldName($this->RoleTable, $this->TitleRoleColumn) : "''").' ROLE_TITLE' ;
		$sql .= ", ".(($this->DescriptionRoleColumn != '') ? $this->Database->EscapeFieldName($this->RoleTable, $this->DescriptionRoleColumn) : "''").' ROLE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableRoleColumn != '') ? $this->Database->EscapeFieldName($this->RoleTable, $this->EnableRoleColumn) : "''").' ROLE_ENABLED' ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->RoleTable) ;
		return $sql ;
	}
	public function SqlAllProfiles()
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= $this->Database->EscapeFieldName('PROFILE_TABLE', $this->IdProfileColumn).' PROFILE_ID' ;
		$sql .= ", ".(($this->TitleProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->TitleProfileColumn) : "''").' PROFILE_TITLE' ;
		$sql .= ", ".(($this->DescriptionProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->DescriptionProfileColumn) : "''").' PROFILE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->EnableProfileColumn) : "''").' PRIVILEGE_ENABLED' ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE' ;
		return $sql ;
	}
	public function SqlProfilesForNewRole()
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= '0 ROLE_ID' ;
		$sql .= ", ".$this->Database->EscapeFieldName('PROFILE_TABLE', $this->IdProfileColumn).' PROFILE_ID' ;
		$sql .= ", ".(($this->NameRoleColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->TitleProfileColumn) : "''").' PROFILE_TITLE' ;
		$sql .= ", ".(($this->DescriptionProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->DescriptionProfileColumn) : "''").' PROFILE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->EnableProfileColumn) : "'0'").' PROFILE_ENABLED' ;
		$sql .= ", 1 PRIVILEGE_ENABLED" ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE' ;
		return $sql ;
	}
	public function SqlProfilesForRole($idRole=0)
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= $this->Database->EscapeFieldName('ROLE_TABLE', $this->IdRoleColumn).' ROLE_ID' ;
		$sql .= ", ".$this->Database->EscapeFieldName('PROFILE_TABLE', $this->IdProfileColumn).' PROFILE_ID' ;
		$sql .= ", ".(($this->NameRoleColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->TitleProfileColumn) : "''").' PROFILE_TITLE' ;
		$sql .= ", ".(($this->DescriptionProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->DescriptionProfileColumn) : "''").' PROFILE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableProfileColumn != '') ? $this->Database->EscapeFieldName('PROFILE_TABLE', $this->EnableProfileColumn) : "'0'").' PROFILE_ENABLED' ;
		$sql .= ", ".(($this->EnablePrivilegeColumn != '') ? $this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->EnablePrivilegeColumn) : "'0'").' PRIVILEGE_ENABLED' ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE' ;
		$sql .= ' left join '.$this->Database->EscapeTableName($this->PrivilegeTable).' PRIVILEGE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->ProfilePrivilegeForeignKey).' = '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->ProfilePrivilegeColumn) ;
		$sql .= ' left join '.$this->Database->EscapeTableName($this->RoleTable).' ROLE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->RolePrivilegeForeignKey).' = '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->RolePrivilegeColumn) ;
		return $sql ;
	}
	public function SqlRolesForNewProfile()
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= '0 PROFILE_ID' ;
		$sql .= ", ".$this->Database->EscapeFieldName('ROLE_TABLE', $this->IdRoleColumn).' ROLE_ID' ;
		$sql .= ", ".(($this->TitleRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->TitleRoleColumn) : "''").' ROLE_TITLE' ;
		$sql .= ", ".(($this->DescriptionRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->DescriptionRoleColumn) : "''").' ROLE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->EnableRoleColumn) : "'0'").' ROLE_ENABLED' ;
		$sql .= ", 1 PRIVILEGE_ENABLED" ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->RoleTable).' ROLE_TABLE' ;
		return $sql ;
	}
	public function SqlRolesForProfile($idProfile=0)
	{
		$sql = '' ;
		$sql .= 'select ' ;
		$sql .= $this->Database->EscapeFieldName('PROFILE_TABLE', $this->IdProfileColumn).' PROFILE_ID' ;
		$sql .= ", ".$this->Database->EscapeFieldName('ROLE_TABLE', $this->IdRoleColumn).' ROLE_ID' ;
		$sql .= ", ".(($this->NameRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->NameRoleColumn) : "''").' ROLE_NAME' ;
		$sql .= ", ".(($this->TitleRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->TitleRoleColumn) : "''").' ROLE_TITLE' ;
		$sql .= ", ".(($this->DescriptionRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->DescriptionRoleColumn) : "''").' ROLE_DESCRIPTION' ;
		$sql .= ", ".(($this->EnableRoleColumn != '') ? $this->Database->EscapeFieldName('ROLE_TABLE', $this->EnableRoleColumn) : "'0'").' ROLE_ENABLED' ;
		$sql .= ", ".(($this->EnablePrivilegeColumn != '') ? $this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->EnablePrivilegeColumn) : "'0'").' PRIVILEGE_ENABLED' ;
		$sql .= ' from '.$this->Database->EscapeTableName($this->RoleTable).' ROLE_TABLE' ;
		$sql .= ' left join '.$this->Database->EscapeTableName($this->PrivilegeTable).' PRIVILEGE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->RolePrivilegeForeignKey).' = '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->RolePrivilegeColumn) ;
		$sql .= ' left join '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->ProfilePrivilegeForeignKey).' = '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->ProfilePrivilegeColumn) ;
		return $sql ;
	}
	protected function SqlValidateConnection()
	{
		$sql = 'SELECT ALL_MEMBER_TABLE.*' ;
		{
			$sql .= ', ' ;
			if($this->LoginMemberCaseInsensitive)
			{
				$sql .= 'lower(' ;
			}
			$sql .= $this->Database->ParamPrefix.'RequestLogin' ;
			if($this->LoginMemberCaseInsensitive)
			{
				$sql .= ')' ;
			}
			$sql .= ' REQUEST_LOGIN' ;
		}
		{
			$sql .= ', ' ;
			if($this->PasswordMemberExpr != '')
			{
				if(stripos($this->PasswordMemberExpr, '<self>') !== false)
				{
					$sql .= str_replace('<self>', $this->Database->ParamPrefix.'RequestPassword', $this->PasswordMemberExpr) ;
				}
				else
				{
					$sql .= $this->PasswordMemberExpr ;
					$sql .= '(' ;
					$sql .= $this->Database->ParamPrefix.'RequestPassword' ;
					$sql .= ')' ;
				}
			}
			else
			{
				$sql .= $this->Database->ParamPrefix.'RequestPassword' ;
			}
			$sql .= ' REQUEST_PASSWORD' ;
		}
		$sql .= ' FROM ('.$this->SqlAllMembers().') ALL_MEMBER_TABLE WHERE MEMBER_LOGIN='.$this->Database->ParamPrefix.'RequestLogin' ;
		if($this->EnableMemberColumn != '')
		{
			$sql .=  ' AND MEMBER_ENABLE='.$this->Database->ParamPrefix.'CorrectEnabled' ;
		}
		if($this->LockMemberEnabled && $this->LockedMemberColumn != '')
		{
			$sql .=  ' AND MEMBER_LOCKED='.$this->Database->ParamPrefix.'UnlockedValue' ;
		}
		return $sql ;
	}
	public function LockedMemberFalseValue()
	{
		return ($this->LockedMemberTrueValue == 1) ? 0 : '1' ;
	}
	public function ValidateConnection($login, $password)
	{
		$sql = $this->SqlValidateConnection() ;
		$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_NONE ;
		$params = array('RequestLogin' => $login, 'RequestPassword' => $password) ;
		if($this->EnableMemberColumn != "")
		{
			$params["CorrectEnabled"] = $this->EnableMemberTrueValue ;
		}
		if($this->LockMemberEnabled && $this->LockedMemberColumn != "")
		{
			$params["UnlockedValue"] = $this->LockedMemberFalseValue() ;
		}
		$requestRow = $this->Database->FetchSqlRow(
			$sql,
			$params
		) ;
		$idMember = $this->IdMemberNotFoundValue ;
		$ok = 0 ;
		if(is_array($requestRow))
		{
			if(count($requestRow))
			{
				if(($this->EnableMemberColumn == '' || $requestRow["MEMBER_ENABLE"] == $this->EnableMemberTrueValue) && (! $this->LockMemberEnabled || ($this->LockedMemberColumn != '' && $requestRow["MEMBER_LOCKED"] == $this->LockedMemberFalseValue())))
				{
					$adActivated = 0 ;
					if($this->ADActivatedMemberColumn != "")
					{
						if($requestRow["MEMBER_AD_ACTIVATED"] == $this->ADActivatedMemberTrueValue)
						{
							$adActivated = 1 ;
						}
					}
					if($adActivated == 0)
					{
						// print $requestRow["REQUEST_PASSWORD"].' and '.$requestRow["MEMBER_PASSWORD"].'<br>' ;
						if($requestRow["REQUEST_PASSWORD"] == $requestRow["MEMBER_PASSWORD"])
						{
							$idMember = $requestRow["MEMBER_ID"] ;
							if($this->LockMemberEnabled && $this->LockedMemberColumn != '')
							{
								$this->UnlockMember($requestRow["MEMBER_ID"]) ;
							}
						}
						else
						{
							$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_PASSWORD_INCORRECT ;
							if($this->LockMemberEnabled && $this->LockedMemberColumn != '')
							{
								$this->UpdateTotalRetry($requestRow["MEMBER_ID"]) ;
							}
						}
					}
					else
					{
						$db = & $this->Database ;
						$adServerRow = $this->Database->FetchSqlRow("select * from ".$db->EscapeTableName($this->ADServerTable)." where ".$db->EscapeFieldName($this->ADServerTable, $this->IdADServerColumn)." = :id", array("id" => $requestRow["MEMBER_AD_SERVER"])) ;
						if(is_array($adServerRow))
						{
							if(count($adServerRow) > 0)
							{
								$ok = $this->ValidateADAuthentification($login, $password, $adServerRow) ;
								if($ok)
								{
									$idMember = $requestRow["MEMBER_ID"] ;
								}
							}
							else
							{
								$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_NOT_FOUND ;
							}
						}
						else
						{
							$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_NOT_FOUND ;
						}
					}
				}
				else
				{
					$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_MEMBER_NOT_ENABLED ;
				}
			}
			else
			{
				$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_MEMBER_NOT_FOUND ;
			}
		}
		else
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_DB_ERROR ;
		}
		return $idMember ;
	}
	protected function ValidateADAuthentification($login, $password, & $adServerRow)
	{
		$user = $login ;
		if($adServerRow[$this->DomainADServerColumn] != "")
		{
			$user = $login.'@'.$adServerRow[$this->DomainADServerColumn] ;
		}
		$password = trim($password) ;
		if(empty($password))
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_PASSWORD_EMPTY ;
			$this->CloseLdapConn() ;
			return 0 ;
		}
		$this->LdapConn = ldap_connect($adServerRow[$this->HostADServerColumn], $adServerRow[$this->PortADServerColumn]) ;
		if(! is_resource($this->LdapConn))
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_CONNECT_ERROR ;
			return 0 ;
		}
		if($adServerRow[$this->UseProtocolV3ADServerColumn] == 1 && ! ldap_set_option($this->LdapConn, LDAP_OPT_PROTOCOL_VERSION, 3))
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_CONNECT_ERROR ;
			$this->CloseLdapConn() ;
			return 0 ;
		}
		if($adServerRow[$this->FollowReferralsADServerColumn] == 1 && ! ldap_set_option($this->LdapConn, LDAP_OPT_REFERRALS, 0))
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_SERVER_CONNECT_ERROR ;
			$this->CloseLdapConn() ;
			return 0 ;
		}
		$success = @ldap_bind($this->LdapConn, $user, $password);
		if(! $success)
		{
			$this->LastValidateError = \Pv\Membership\Sql::VALIDATE_ERROR_AD_AUTH_FAILED ;
			$this->CloseLdapConn() ;
			return 0 ;
		}
		$this->CloseLdapConn() ;
		return ($success) ? 1 : 0 ;
	}
	public function CloseLdapConn()
	{
		if(is_resource($this->LdapConn))
		{
			ldap_unbind($this->LdapConn) ;
		}
	}
	public function UpdateTotalRetry($memberId)
	{
		// Update total retry
		$updateTotalRetrySql = 'update '.$this->Database->EscapeTableName($this->MemberTable).' set '.$this->Database->EscapeFieldName($this->MemberTable, $this->TotalRetryMemberColumn).' = '.$this->Database->EscapeFieldName($this->MemberTable, $this->TotalRetryMemberColumn).' + 1 where '.$this->Database->EscapeFieldName($this->MemberTable, $this->IdMemberColumn).' = '.$this->Database->ParamPrefix.'idMember' ;
		$ok = $this->Database->RunSql($updateTotalRetrySql, array('idMember' => $memberId)) ;
		return $this->LockMember($memberId) ;
	}
	public function LockMember($memberId)
	{
		// Lock member
		$sqlLock = 'update '.$this->Database->EscapeTableName($this->MemberTable).' set '.$this->Database->EscapeFieldName($this->MemberTable, $this->LockedMemberColumn).' = '.$this->Database->ParamPrefix.'lockedValue where '.$this->Database->EscapeFieldName($this->MemberTable, $this->IdMemberColumn).' = '.$this->Database->ParamPrefix.'idMember and '.$this->Database->EscapeFieldName($this->MemberTable, $this->TotalRetryMemberColumn).'='.$this->Database->ParamPrefix.'maxRetry' ;
		return $this->Database->RunSql(
			$sqlLock,
			array(
				'idMember' => $memberId,
				'lockedValue' => $this->LockedMemberTrueValue,
				'maxRetry' => $this->MaxConnectionRetry,
			)
		) ;
	}
	public function UnlockMember($memberId)
	{
		// Unlock member
		$sqlUnlock = 'update '.$this->Database->EscapeTableName($this->MemberTable).' set '.$this->Database->EscapeFieldName($this->MemberTable, $this->LockedMemberColumn).' = '.$this->Database->ParamPrefix.'unlockedValue, '.$this->Database->EscapeFieldName($this->MemberTable, $this->TotalRetryMemberColumn).'=0 where '.$this->Database->EscapeFieldName($this->MemberTable, $this->IdMemberColumn).' = '.$this->Database->ParamPrefix.'idMember' ;
		$ok = $this->Database->RunSql(
			$sqlUnlock,
			array(
				'idMember' => $memberId,
				'lockedValue' => $this->LockedMemberFalseValue()
			)
		) ;
		return $ok ;
	}
	protected function SqlFetchMemberRow($memberId)
	{
		$sql = 'SELECT ALL_MEMBER_TABLE.* FROM ('.$this->SqlAllMembers().') ALL_MEMBER_TABLE WHERE MEMBER_ID='.$this->Database->ParamPrefix.'MemberId' ;
		return $sql ;
	}
	protected function SqlFetchMemberRowByLogin($login)
	{
		$sql = 'SELECT ALL_MEMBER_TABLE.* FROM ('.$this->SqlAllMembers().') ALL_MEMBER_TABLE WHERE MEMBER_LOGIN='.$this->Database->ParamPrefix.'MemberLogin' ;
		return $sql ;
	}
	public function FetchMemberRow($memberId)
	{
		$row = $this->Database->FetchSqlRow(
			$this->SqlFetchMemberRow($memberId),
			array(
				"MemberId" => $memberId
			)
		) ;
		return $row ;
	}
	public function FetchMemberRowByLogin($memberId)
	{
		return $this->Database->FetchSqlRow(
			$this->SqlFetchMemberRowByLogin($memberId),
			array(
				"MemberLogin" => $memberId
			)
		) ;
	}
	protected function SqlAllPrivileges()
	{
		$sql = "" ;
		$sql .= 'SELECT 0 PROFILE_REQUEST' ;
		if($this->IdProfileColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->IdProfileColumn).' PROFILE_ID' ;
		}
		if($this->TitleProfileColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->TitleProfileColumn).' PROFILE_TITLE' ;
		}
		if($this->DescriptionProfileColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->DescriptionProfileColumn).' PROFILE_DESCRIPTION' ;
		}
		if($this->IdPrivilegeColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->IdPrivilegeColumn).' PRIVILEGE_ID' ;
		}
		if($this->RolePrivilegeColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->RolePrivilegeColumn).' PRIVILEGE_ROLE' ;
		}
		if($this->ProfilePrivilegeColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->ProfilePrivilegeColumn).' PRIVILEGE_PROFILE' ;
		}
		if($this->EnablePrivilegeColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->EnablePrivilegeColumn).' PRIVILEGE_ENABLED' ;
		}
		if($this->IdRoleColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->IdRoleColumn).' ROLE_ID' ;
		}
		if($this->NameRoleColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->NameRoleColumn).' ROLE_NAME' ;
		}
		if($this->TitleRoleColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->TitleRoleColumn).' ROLE_TITLE' ;
		}
		if($this->DescriptionRoleColumn != "")
		{
			$sql .= ', '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->DescriptionRoleColumn).' ROLE_DESCRIPTION' ;
		}
		$sql .= ' FROM '.$this->Database->EscapeTableName($this->RoleTable).' ROLE_TABLE' ;
		$sql .= ' LEFT JOIN '.$this->Database->EscapeTableName($this->PrivilegeTable).' PRIVILEGE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('ROLE_TABLE', $this->RolePrivilegeForeignKey).'='.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->RolePrivilegeColumn) ;
		$sql .= ' LEFT JOIN '.$this->Database->EscapeTableName($this->ProfileTable).' PROFILE_TABLE' ;
		$sql .= ' ON '.$this->Database->EscapeFieldName('PRIVILEGE_TABLE', $this->ProfilePrivilegeColumn).'='.$this->Database->EscapeFieldName('PROFILE_TABLE', $this->ProfilePrivilegeForeignKey) ;
		return $sql ;
	}
	protected function SqlFetchProfileRows()
	{
		$sql = 'SELECT T1.* FROM ('.$this->SqlAllPrivileges().') T1 WHERE PROFILE_ID='.$this->Database->ParamPrefix.'ProfileId' ;
		return $sql ;
	}
	public function FetchProfileRows($profileId)
	{
		return $this->Database->FetchSqlRows(
			$this->SqlFetchProfileRows(),
			array(
				"ProfileId" => $profileId
			)
		) ;
	}
	public function InsertMemberRow($memberRow)
	{
		if($this->PasswordMemberExpr != '')
		{
			if(stripos($this->PasswordMemberExpr, '<self>') !== false)
			{
				$memberRow[$this->Database->ExprKeyName][$this->PasswordMemberColumn] = str_ireplace('<self>', $this->ExprParamPattern, $this->PasswordMemberExpr) ;
			}
			else
			{
				$memberRow[$this->Database->ExprKeyName][$this->PasswordMemberColumn] = $this->PasswordMemberExpr.'('.$this->ExprParamPattern.')' ;
			}
		}
		$ok = $this->Database->InsertRow(
			$this->MemberTable,
			$memberRow
		) ;
		return $ok ;
	}
	public function UpdateMemberRow($memberId, $memberRow)
	{
		if($this->PasswordMemberExpr != '')
		{
			if(stripos($this->PasswordMemberExpr, '<self>') !== false)
			{
				$memberRow[$this->Database->ExprKeyName][$this->PasswordMemberColumn] = str_ireplace('<self>', $this->ExprParamPattern, $this->PasswordMemberExpr) ;
			}
			else
			{
				$memberRow[$this->Database->ExprKeyName][$this->PasswordMemberColumn] = $this->PasswordMemberExpr.'('.$this->ExprParamPattern.')' ;
			}
		}
		$ok = $this->Database->UpdateRow(
			$this->MemberTable,
			$memberRow,
			$this->Database->EscapeFieldName($this->MemberTable, $this->IdMemberColumn).' = '.$this->Database->ParamPrefix.'IdCurrentMember',
			array(
				'IdCurrentMember' => $memberId
			)
		) ;
		return $ok ;
	}
	public function DeleteMemberRow($memberId)
	{
		$ok = $this->Database->DeleteRow(
			$this->MemberTable,
			$this->Database->EscapeFieldName($this->MemberTable, $this->IdMemberColumn).' = '.$this->Database->ParamPrefix.'IdCurrentMember',
			array('IdCurrentMember' => $memberId)
		) ;
		return $ok ;
	}
	public function InsertProfileRow($profileRow)
	{
		$ok = $this->Database->InsertRow(
			$this->ProfileTable,
			$profileRow
		) ;
		return $ok ;
	}
	public function UpdateProfileRow($profileId, $profileRow)
	{
		$ok = $this->Database->UpdateRow(
			$this->ProfileTable,
			$profileRow,
			$this->Database->EscapeFieldName($this->ProfileTable, $this->IdProfileColumn).' = '.$this->Database->ParamPrefix.'IdCurrentProfile',
			array(
				'IdCurrentProfile' => $profileId
			)
		) ;
		return $ok ;
	}
	public function DeleteProfileRow($profileId)
	{
		$ok = $this->Database->DeleteRow(
			$this->ProfileTable,
			$this->Database->EscapeFieldName($this->ProfileTable, $this->IdProfileColumn).' = '.$this->Database->ParamPrefix.'IdCurrentProfile',
			array('IdCurrentProfile' => $profileId)
		) ;
		return $ok ;
	}
	public function InsertRoleRow($roleRow)
	{
		$ok = $this->Database->InsertRow(
			$this->RoleTable,
			$roleRow
		) ;
		return $ok ;
	}
	public function UpdateRoleRow($roleId, $roleRow)
	{
		$ok = $this->Database->UpdateRow(
			$this->RoleTable,
			$roleRow,
			$this->Database->EscapeFieldName($this->RoleTable, $this->IdRoleColumn).' = '.$this->Database->ParamPrefix.'IdCurrentRole',
			array(
				'IdCurrentRole' => $roleId
			)
		) ;
		return $ok ;
	}
	public function DeleteRoleRow($roleId)
	{
		$ok = $this->Database->DeleteRow(
			$this->RoleTable,
			$this->Database->EscapeFieldName($this->RoleTable, $this->IdRoleColumn).' = '.$this->Database->ParamPrefix.'IdCurrentRole',
			array('IdCurrentRole' => $roleId)
		) ;
		return $ok ;
	}
}		