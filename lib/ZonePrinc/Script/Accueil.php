<?php

namespace Rpa2p\ZonePrinc\Script ;

class Accueil extends Script
{
	public $NecessiteMembreConnecte = false ;
	public $TitreDocument = "Tableau de bord" ;
	public $Titre = "Tableau de bord" ;
	public function DetermineEnvironnement()
	{
		if(! $this->PossedeMembreConnecte())
		{
			\Pv\Misc::redirect_to($this->ZoneParent->ScriptConnexion->ObtientUrl()) ;
		}
		parent::DetermineEnvironnement() ;
		$this->DetermineTablPrinc() ;
	}
	protected function DetermineTablPrinc()
	{
		$this->Tabl1 = $this->InsereTablPrinc() ;
		$this->Tabl1->ChargeConfig() ;
		$this->Tabl1->TriPossible = false ;
		$this->Tabl1->FournisseurDonnees = $this->CreeFournPrinc() ;
		$this->Tabl1->FournisseurDonnees->RequeteSelection = "(select t1.id, t1.statut, t1.date_fin, ROUND((t1.total_succes / (t1.total_succes + t1.total_echecs))*100) taux_dispo, t2.nom nom_job, t3.titre titre_application
from (select max(id) id_exec from rpapp_exec_job group by id_job) t0
inner join rpapp_exec_job t1 on t0.id_exec=t1.id
inner join rpapp_job t2 on t1.id_job=t2.id
inner join rpapp_application t3 on t2.id_application=t3.id
where t2.actif=1)" ;
		$this->Tabl1->InsereDefColCachee("id") ;
		$this->Tabl1->InsereDefColCachee("taux_dispo") ;
		$this->Tabl1->InsereDefCol("id") ;
		$this->Tabl1->InsereDefCol("titre_application", "Application") ;
		$this->Tabl1->InsereDefCol("nom_job", "Job") ;
		$this->Tabl1->InsereDefColChoix(
			"taux_dispo_fmt",
			"Statut",
			"case
	when taux_dispo < 60 then 'red'
	when taux_dispo < 100 then 'orange'
	else 'green'
end",
			array(
				"red" => '<span class="text-danger">${taux_dispo} %</span>',
				"orange" => '<span class="text-warning">${taux_dispo} %</span>',
				"green" => '<span class="text-success">${taux_dispo} %</span>',
			)
		) ;
		$this->Tabl1->InsereDefColDateTimeFr("date_fin", "Date") ;
	}
	public function RenduSpecifique()
	{
		$ctn = '' ;
		$ctn .= '<br />' ;
		$ctn .= '<div class="card">' ;
		$ctn .= '<div class="card-body text-center">' ;
		$ctn .= '<a class="btn btn-1 " href="?appelleScript=listeJobs" style = "  
		background-image: linear-gradient(to right, orange 0%, red 51%, orange 100%);
		background-position: right center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-align: center;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Les-Jobs</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-2 " href="?appelleScript=listeEnvs" style = "  
		background-image: linear-gradient(to left, orange 0%, red 51%, orange 100%);
		background-position: left center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-align: center;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Environnements</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-3  " href="?appelleScript=listeApps" style = "  
		background-image: linear-gradient(to right, orange 0%, red 51%, orange 100%);
		background-position: right center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-align: center;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Applications</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-4  " href="?appelleScript=listeMembres" style = "  
		background-image: linear-gradient(to left, orange 0%, red 51%, orange 100%);
		background-position: left center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Membres</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-4  " href="?appelleScript=listeProprietes" style = "  
		background-image: linear-gradient(to right, orange 0%, red 51%, orange 100%);
		background-position: right center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Propriétés</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-4  " href="?appelleScript=listeVars" style = "  
		background-image: linear-gradient(to left, orange 0%, red 51%, orange 100%);
		background-position: left center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Variable</i></a>' ;
		$ctn .= '&nbsp;' ;
		$ctn .= '<a class="btn btn-5 " href="?appelleScript=listeSupervisJob" target="_supervision" style = "  
		background-image: linear-gradient(to right, orange 0%, red 51%, orange 100%);
		background-position: right center;
		flex: 1 1 auto;
		margin: 10px;
		padding: 20px;
		text-align: center;
		text-transform: uppercase;
		transition: 0.5s;
		background-size: 200% auto;
		color: white;
		box-shadow: 0 0 20px #eee;
		border-radius: 30px;">Supervision</i></a>' ;
		$ctn .= '</div>' ;
		$ctn .= '</div>' ;
		$ctn .= '<br />' ;
		$ctn .= '<br />' ;
		$ctn .= $this->Tabl1->RenduDispositif() ;
		return $ctn ;
	}
}
// $ctn .= '<a class="btn btn-4  " href="?appelleScript=listeMembres" style = "  
// 		background-image: linear-gradient(to right, #f6d365 0%, red 51%, black 100%);
// 		background-position: right center;
// 		flex: 1 1 auto;
// 		margin: 10px;
// 		padding: 30px;
		
// 		text-transform: uppercase;
// 		transition: 0.5s;
// 		background-size: 200% auto;
// 		color: white;
// 		box-shadow: 0 0 20px #eee;
// 		border-radius: 10px;"><i class="fas fa-2x fa-tasks"><br>Membres</i></a>' ;
// 		$ctn .= '&nbsp;' ;