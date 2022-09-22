<?php

namespace Rpa2p ;

class Membership extends \Pv\Membership\Sql
{
	public $MemberTable = "rpapp_member" ;
	public $ProfileTable = "rpapp_profile" ;
	public $RoleTable = "rpapp_role" ;
	public $PrivilegeTable = "rpapp_privilege" ;
	public $ADServerMemberColumn = "ad_server_id" ;
	public $ADActivatedMemberColumn = "ad_activated" ;
	public $ADServerTable = "rpapp_ad_server" ;
	public $PasswordMemberExpr = "PASSWORD" ;
	public $RootMemberId = "1" ;
	protected function InitConfig(& $parent)
	{
		parent::InitConfig($parent) ;
		$this->Database = new BDPrinc() ;
	}
}
