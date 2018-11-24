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

			//if none of the search parameters are met throw an exception
		} else if(empty($rsvpEventId) === false) {
			$reply->data = Rsvp::getRsvpByRsvpEventId($pdo, $rsvpEventId)->toArray();
			//get all the rsvp associated with the rsvpProfileId
		} else if(empty($rsvpProfileId) === false) {
			$reply->data = Rsvp::getRsvpByRsvpProfileId($pdo, $rsvpProfileId)->toArray();
		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
		}
	} else if($method === "POST" || $method === "PUT") {
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->rsvpEventId) === true) {
			throw (new \InvalidArgumentException("No Event linked to the Rsvp", 405));
		}
		if(empty($requestObject->rsvpProfileId) === true) {
			throw (new \InvalidArgumentException("No Provile linked to the rsvp", 405));
		}
		if($method === "POST") {
			//enforce that the end user has a XSRF token.
			verifyXsrf();
			//enforce the end user has a JWT token
			//validateJwtHeader();
			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in too rsvp to this event", 403));
			}
