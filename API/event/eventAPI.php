<?php

require_once dirname(__DIR__, 3) . "../vendor/autoload.php";
require_once dirname(__DIR__, 3) . "../php/classes/autoload.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "../php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "../php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "../php/lib/jwt.php";

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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/cohort22/astronomers.ini");

	// which http method used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$eventEventTypeId = filter_input(INPUT_GET, "eventEventTypeId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$eventProfileId = filter_input(INPUT_GET, "eventProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//ensure id is valid for methods that need it
	if(($method === "DELETE" || $method = "PUT") && (empty($id) === true)){
		throw(new InvalidArgumentException("in cannot be empty or negative", 402));
	}

	// handle GET request - if id is present, that event is returned, otherwise all events are returned
	if($method === "GET"){
		//set xsrf token
		setXsrfCookie();
		//get a specific event or all events and update reply
		if(empty($id) === false) {
			$reply->data = Event::getEventByEventId($pdo, $id);
		} else if(empty($eventEventTypeId) === false) {
			$reply->data = Event::getEventByEventEventTypeId($pdo, $_SESSION["eventType"]->getEventTypeId())->toArray();
		} else if(empty($eventProfileId) === false) {
			//if user is logged in grab all events by that user based on log in
			$reply->data = Event::getEventByEventProfileId($pdo, $_SESSION["profile"]->getProfileId())->toArray();
		}else{
			$reply->data = Event::getAllEvents($pdo)->toArray();
		}

	}
}