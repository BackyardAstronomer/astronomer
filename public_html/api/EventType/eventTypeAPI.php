<?php

require_once dirname(__DIR__, 3) . "../vendor/autoload.php";
require_once dirname(__DIR__, 3) . "../php/Classes/autoload.php";
require_once dirname(__DIR__, 3) . "../php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "../php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "../php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use BackyardAstronomer\Astronomer\ {
	EventType
};


/**
* api for eventType
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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/cohort22/astronomers.ini");

//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$eventTypeName = filter_input(INPUT_GET, "eventTypeName ", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($evenTypeName) === true)) {
		throw(new InvalidArgumentException("EventTypeID cannot be empty or negative", 405));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific tweet based on arguments provided or all the tweets and update reply
		if(empty($id) === false) {
			$reply->data = EventType::getEventTypeByEventTypeId($pdo, $id);
		}
		else {
			$reply->data = EventType::getAllEventTypes($pdo)->toArray();
		}

}
// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);

// finally - JSON encodes the $reply object and sends it back to the front end.

