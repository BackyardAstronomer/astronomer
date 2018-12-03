<?php
require_once dirname(__DIR__,3)."php/classes/autoload.php";
require_once dirname(__DIR__,3)."php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use BackyardAstronomer\Astronomer;
/**
 * API to check profile activation status
 * @author Gkephart
 */
// Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare and empty reply