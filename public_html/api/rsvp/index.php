<?php


require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use BackyardAstronomer\Astronomer\ {
	Rsvp, Profile, Event
};
/**
 * Api for the rsvp class
 *
 * @author Dayn Augustson
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
	//grab the mySQL connection
	$secrets = new \Secrets("/etc/apache2/capstone-mysql/cohort22/astronomers");
	$pdo = $secrets->getPdoObject();

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize the search parameters
	$rsvpEventId = $id = filter_input(INPUT_GET, "rsvpEventId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	$rsvpProfileId = $id = filter_input(INPUT_GET, "rsvpProfileId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets  a specific like associated based on its composite key
		if ($rsvpEventId  !== null && $rsvpProfileId !== null) {
			$rsvp = Rsvp::getRsvpByRsvpEventIdRsvpProfileId($pdo, $rsvpEventId, $rsvpProfileId);
			if($rsvp !== null) {
				$reply->data = $rsvp;
			}

			//if none of the search parameters are met throw an exception
		} else if(empty($rsvpEventId) === false) {
			$reply->data = Rsvp::getRsvpByRsvpEventId($pdo, $rsvpEventId)->toArray();
			//get all the rsvp associated with the rsvpProfileId
		} else if(empty($rsvpProfileId) === false) {
			$rsvps = Rsvp::getRsvpByRsvpProfileId($pdo, $rsvpProfileId)->toArray();
			$events = [];

			foreach($rsvps as $rsvp){
				$events[] = Event::getEventByEventId($pdo, $rsvp->getRsvpEventId());
			}
			$reply->data = $events;
		} else {
			throw new \InvalidArgumentException("incorrect search parameters", 404);
		}
	} else if($method === "POST" || $method === "PUT") {

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->rsvpEventId) === true) {
			throw (new \InvalidArgumentException("No Event linked to the RSVP", 405));
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

			validateJwtHeader();

			$rsvp = new Rsvp($_SESSION["profile"]->profile->getProfileId(), $requestObject->rsvpEventId, 1);
			$rsvp->insert($pdo);
			$reply->message = "rsvp event successful";
		} else if($method === "PUT") {
			//enforce the end user has a XSRF token.
			verifyXsrf();

			//grab the rsvp by its composite key
			$rsvp = Rsvp::getRsvpByRsvpEventIdRsvpProfileId($pdo, $requestObject->rsvpEventId, $requestObject->rsvpProfileId);
			if($rsvp === null) {
				throw (new \RuntimeException("rsvp does not exist"));
			}
			//enforce the user is signed in and only trying to edit their own rsvp
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->profile->getProfileId() !== $rsvp->getrsvpProfileId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this rsvp", 403));
			}
			validateJwtHeader();
			//preform the actual delete
			$rsvp->delete($pdo);
			//update the message
			$reply->message = "rsvp successfully deleted";
		}
		// if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	}
	//catch any exceptions that is thrown and update the reply status and message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);

