<?php
namespace BackyardAstronomer\Astronomer;

use BackyardAstronomer\Astronomer\Rsvp;

require_once dirname(__DIR__, 3) . "../vendor/autoload.php";
require_once dirname(__DIR__, 3) . "../php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "../php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "../php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "../php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");




/**
 * Api for the rsvp class
 *
 * @author Dayn Augustosn
 */
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/cohort22/astronomers.ini");

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize the search parameters
	$rsvpEventId = $id = filter_input(INPUT_GET, "rsvpEventId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	$rsvpProfileId = $id = filter_input(INPUT_GET, "resvpPorfileId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific like associated based on its composite key
		if ($rsvpEventId  !== null && $rsvpProfileId !== null) {
			$rsvp = Rsvp::getRsvpByRsvpEventIdRsvpProfileId($pdo, $rsvpEventId, $rsvpProfileId);
			if($rsvp!== null) {
				$reply->data = $rsvp;
			}

