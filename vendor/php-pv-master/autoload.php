<?php

include_once dirname(__FILE__)."/vendor/SafeHTML.php" ;
include_once dirname(__FILE__)."/vendor/Services_JSON.php" ;
spl_autoload_register(function ($class_name) {
	if(strpos($class_name, "Pv\\") === false) {
		return ;
	}
	$class_name = str_replace("Pv\\", "src".DIRECTORY_SEPARATOR, $class_name) ;
    include_once dirname(__FILE__). DIRECTORY_SEPARATOR .$class_name .'.php';
}) ;

