<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once ("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";

use BackyardAstronomer\Astronomer\{
	Event,
	Profile,
	EventType
};

/**
 * this is the api for the event class
 *
 * @author Jack Jain <jjain1998@gmail.com>
 */

//verifying the session, or start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare and empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//get mysql connection
	$secrets =  new \Secrets("/etc/apache2/capstone-mysql/cohort22/astronomers");
	$pdo = $secrets->getPdoObject();

	// which http method used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$eventId = filter_input(INPUT_GET, "eventId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$eventEventTypeId = filter_input(INPUT_GET, "eventEventTypeId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$eventProfileId = filter_input(INPUT_GET, "eventProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//ensure id is valid for methods that need it
	if(($method === "DELETE" || $method = "PUT") && (empty($eventId) === true)){
		throw(new InvalidArgumentException("id cannot be empty or negative", 401));
	}

	// handle GET request - if id is present, that event is returned, otherwise all events are returned
	if($method === "GET"){
		//set xsrf token
		setXsrfCookie();
		//get a specific event or all events and update reply
		if(empty($eventId) === false) {
			$reply->data = Event::getEventByEventId($pdo, $eventId);
		} else if(empty($eventEventTypeId) === false) {
			$reply->data = Event::getEventByEventEventTypeId($pdo, $_SESSION["eventType"]->getEventTypeId())->toArray();
		} else if(empty($eventProfileId) === false) {
			//if user is logged in grab all events by that user based on log in
			$reply->data = Event::getEventByEventProfileId($pdo, $_SESSION["profile"]->getProfileId())->toArray();
		}else{
			$reply->data = Event::getAllEvents($pdo)->toArray();
		}
	} else if($method ==="PUT" || $method === "POST") {
		//enforce user has xsrf token
		verifyXsrf();
		// enforce user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be signed in to create events", 401));
		}
		$requestContent = file_get_contents("php://input");
		// retrieves JSON package that the front end sent, and stores it in $requestContent
		$requestObject = json_decode($requestContent);
		// this line decodes json package and stores result in $requestObject
		// ensure event content is available (required)
		if(empty($requestObject->eventContent) === true) {
			throw(new \InvalidArgumentException("No content for Event.", 405));
		}

		// ensure date is not null
		if(empty($requestObject->eventStartDate) === true) {
			throw(new \InvalidArgumentException("events must have start dateTime", "https://http.cat/[406].jpeg"));
		} else{
			$secondsStart = $requestObject->eventStartDateTime;
			$formattedStartDate = date("Y-m-d H:i:s" $secondsStart / 1000);
		}
		if(empty($requestObject->eventEndDate) === true) {
			throw(new \InvalidArgumentException("events must have end dateTime", "https://http.cat/[406].jpeg"));
		} else{

		}



		if($method === "PUT") {
			// retrieve the event to update
			$event = Event::getEventByEventId($pdo, $eventId);
			if($event === null) {
				throw (new \RuntimeException("event does not exist", "https://http.cat/[404].jpg"));
			}
			//enforce user has a JWT token

			//enforce user is signed in and only trying to edit their own event
			if(empty($_SESSION["profile"])=== true || $_SESSION["profile"]->getProfileId()->toString() !== $event->getEventProfileId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this event", 'https://http.cat/[403]'));
			}

			//enforce the end user has a JWT token
			validateJwtHeader();

			//update all attributes
			//$event->setEventDate($requestObject->eventDate);
			$event->setEventContent($requestContent->eventContent);
			$event->update($pdo);

			//update reply
			$reply->message = "Event updated OK";
		}else if($method === "POST"){

			//enforce user is signed in
			if(empty($_SESSION["profile"]) === true){
				throw(new \InvalidArgumentException("you must be logged in to post events", 403));
			}

			//enforce end user has JWT token
			validateJwtHeader();

			//create new event and insert into database
			$event = new Event(generateUuidV4(), $_SESSION["eventType"]->getEventTypeId(), $_SESSION["profile"]->getProfileId(), $requestObject->eventTitle, $requestContent->eventContent, null, null);
			$event->insert($pdo);

			//update reply
			$reply->message = "Event created OK";
		}

	}else if($method === "DELETE") {
		// enforce user has xsrf token
		verifyXsrf();

		// retrieve the Event to be deleted
		$event = Event::getEventByEventId($pdo, $id);
		if($event === null){
			throw (new RuntimeException("Event does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own event
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $event->getEventProfileId()->toString()){
			throw(new \InvalidArgumentException("You are not allowed to delete this event", 403));
		}

		//enforce JWT token
		validateJwtHeader();

		//delete event
		$event->delete($pdo);
		//update reply
		$reply->message = "Event deleted OK";
	} else {
		throw(new \InvalidArgumentException("Invalid HTTP method request", 418));
	}
	//update the $reply->status $reply->message
} catch(\TypeError | \Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//encode and return to front end caller
header("Content-type: application/json");
echo json_encode($reply);