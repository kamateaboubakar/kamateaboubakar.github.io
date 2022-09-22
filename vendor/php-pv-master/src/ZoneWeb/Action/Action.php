<?php

namespace Pv\ZoneWeb\Action ;

class Action extends \Pv\Objet\Objet
{
	public $ZoneParent ;
	public $NomElementZone = "" ;
    /*
     * Script parent
     * 
     * @var \Pv\ZoneWeb\Script\Script
     */
	public $ScriptParent ;
	public $NomElementScript = "" ;
	public $ComposantRenduParent ;
	public $NomElementComposantRendu = "" ;
	public $Params = array() ;
	public $Privileges = array() ;
	public $NecessiteMembreConnecte = 0 ;
	public $ApplicationParent ;
	public function IdMembreSession()
	{
		if($this->ZoneParent->NomClasseMembership == '')
		{
			return -1 ;
		}
		$classe = $this->ZoneParent->NomClasseMembership ;
		$membership = new $classe($this->ZoneParent) ;
		$idSession = $membership->GetSessionValue($membership->SessionMemberKey) ;
		return $idSession ;
	}
	public function EstAccessible()
	{
		if(! $this->NecessiteMembreConnecte)
		{
			return 1 ;
		}
		return $this->ZoneParent->PossedePrivileges($this->Privileges) ;
	}
	public function Invoque($params=array(), $valeurPost=array(), $async=1)
	{
		$urlAct = $this->ObtientUrl($params) ;
		return \Pv\Application\Application::TelechargeUrl($urlAct, $valeurPost, $async) ;
	}
	public function InstrsJsAppelAjax($params=array(), $valeurPost=array(), $cfg=null)
	{
		if($cfg == null)
		{
			$cfg = new \Pv\ZoneWeb\Action\CfgAppelAction() ;
		}
		$urlAct = $this->ObtientUrl($params) ;
		$methode = (! empty($valeurPost) && count($valeurPost) > 0) ? "POST" : "GET" ;
		return 'var xhttp_'.$this->IDInstanceCalc.' = new XMLHttpRequest();
xhttp_'.$this->IDInstanceCalc.'.onreadystatechange = function() {
if (xhttp_'.$this->IDInstanceCalc.'.readyState == 4)
{
if(xhttp_'.$this->IDInstanceCalc.'.status == 200)
{
'.$cfg->InstrsSucces.'
}
else
{
'.$cfg->InstrsEchec.'
}
}
else
{
'.$cfg->InstrsChargement.'
}
}
xhttp_'.$this->IDInstanceCalc.'.open("'.$methode.'", '.svc_json_encode($urlAct).', '.svc_json_encode($cfg->Async).') ;
xhttp_'.$this->IDInstanceCalc.'.send() ;' ;
	}
	public function InsereAppelAjax($params=array(), $valeurPost=array(), $cfg=null)
	{
		$this->ZoneParent->InsereContenuCSS($params, $valeurPost, $cfg) ;
	}
	public function ObtientUrl($params=array())
	{
		if($this->EstPasNul($this->ScriptParent))
		{
			$url = \Pv\Misc::update_url_params(
				$this->ScriptParent->ObtientUrl(),
				array_merge(
					$this->Params,
					$params,
					array($this->ZoneParent->NomParamActionAppelee => $this->NomElementZone)
				)
			) ;
			return $url ;
		}
		if($this->EstNul($this->ZoneParent))
		{
			return false ;
		}
		$chaineParams = \Pv\Misc::http_build_query_string(array_merge($this->Params, $params)) ;
		if($chaineParams != '')
			$chaineParams = "&".$chaineParams ;
		$url = $this->ZoneParent->ObtientUrl()."?".urlencode($this->ZoneParent->NomParamActionAppelee).'='.urlencode($this->NomElementZone).$chaineParams ;
		return $url ;
	}
	public function ObtientUrlFmt($params=array(), $autresParams=array())
	{
		$url = $this->ObtientUrl($autresParams) ;
		foreach($params as $nom => $val)
		{
			$url .= '&'.urlencode($nom).'='.$val ;
		}
		return $url ;
	}
	public function AdopteZone($nom, & $zone)
	{
		$this->ZoneParent = & $zone ;
		$this->NomElementZone = $nom ;
		$this->ApplicationParent = & $zone->ApplicationParent ;
	}
	public function AdopteScript($nom, & $script)
	{
		$this->ScriptParent = & $script ;
		$this->NomElementScript = $nom ;
		$this->AdopteZone($this->ScriptParent->NomElementZone."_".$this->NomElementScript, $script->ZoneParent) ;
	}
	public function AdopteComposantRendu($nom, & $composant)
	{
		$this->ComposantRenduParent = & $composant ;
		$this->NomElementComposantRendu = $nom ;
		$this->AdopteScript($this->ComposantRenduParent->NomElementScript."_".$this->NomElementComposantRendu, $composant->ScriptParent) ;
	}
	public function Accepte($valeurAction)
	{
		// echo 'Nom elem : '.$valeurAction ;
		return ($this->NomElementZone == $valeurAction) ? 1 : 0 ;
	}
	public function Execute()
	{
	}
}

