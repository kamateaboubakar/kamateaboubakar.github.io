<?php

include dirname(__FILE__)."/../vendor/autoload.php" ;

if(! isset($_SERVER["argv"]) || ! isset($_SERVER["argv"][1])) {
	echo "Usage : php ".$_SERVER["PHP_SELF"]." <id_activite>" ;
	return ;
}

$app = new \Rpa2p\Application() ;
$id = $_SERVER["argv"][1] ;
$repActivit = \Rpa2p\Config\Chemin::REP_ACTIVITES."/".$id ;
$chemFich = $repActivit."/main.robot" ;
if(file_exists($chemFich))
{
	$ctnCrypt = file_get_contents($chemFich) ;
	$ctnDecode = $app->DecrypteFich($ctnCrypt, \Rpa2p\Config\Cryptage::CLE_FICH_ROBOT) ;
	file_put_contents($repActivit."/main-decrypted.robot", $ctnDecode) ;
	echo "Decryptage OK" ;
}
else
{
	echo "Fichier robot inexistant pour cette activité" ;
}