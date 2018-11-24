<?php
namespace BackyardAstronomer\Astronomer;

use BackyardAstronomer\Astronomer\Rsvp;

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use BackyardAstronomer\Astronomer\EventType;



/**
* API for eventType
*
* @author Dayn Augustson
* @version 1.0
*/
//verify the session, if it is not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
//grab the mySQL connection
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

//determine which HTTP method was used
$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

// sanitize input