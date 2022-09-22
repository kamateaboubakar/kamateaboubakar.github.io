<?php

namespace Rpa2p\Config ;

class ExecActivites
{
	const MAX_PLANIF = 3 ;
	// 6 correspond a 60mn / 6, donc chaque 10 minutes
	const MINUTAGES = 6 ;
	const MAX_JOBS_SESSION = 4 ;
	const DELAI_ACTIVITE = 900 ; // en seconde
	const TOUJOURS_EXECUTER = true ;
	const CACHE_QUEUES = true ;
	const MAJ_STATS = false ;
	const MAJ_INFOS_PLANIF = true ;
	const REJOUER_NON_DEMARRES = true ;
}
