<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";

use BackyardAstronomer\Astronomer\ {
	Comment, Event, EventType, Profile
};

/**
 * This is the api for the comment class
 *
 * @author Stephen Pelot <stephenpelot@gmail.com>
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
	$secrets =  new \Secrets("/etc/apache2/capstone-mysql/cohort22/astronomers");
	$pdo = $secrets->getPdoObject();

//	$password = "abc123";
//	$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
//	$token = bin2hex(random_bytes(16));
//
//	$profile = new Profile(generateUuidV4(), $token, "hello", "hello@hello.com", $hash,"http://best-image-ever.com", "world" );
//	$profile->insert($pdo);
//
//	var_dump("profileId", $profile->getProfileId()->toString());
//
//	$eventType = new EventType(generateUuidV4(), "star party");
//	$eventType->insert($pdo);
//
//	$event = new Event(generateUuidV4(), $eventType->getEventTypeId(), $profile->getProfileId(), "Star Party!", "Hello world", new \DateTime());
//
//	var_dump("eventID", $event->getEventId()->toString());
//	$event->insert($pdo);

	//$_SESSION["profile"] = Profile::getProfileByProfileId($pdo,"bc8c85bc-5e0a-4b88-a0e9-2db164bca2ff");

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "Id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentEventId = filter_input(INPUT_GET, "commentEventId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentProfileId = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && empty($commentId) === true) {
		throw(new InvalidArgumentException("comment Id cannot be empty", 405));
	}


	// handle GET request - if id is present, that comment is returned, otherwise all comment are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific comment or all comments and update reply
		if(empty($id) === false) {
			$reply->data = Comment::getCommentByCommentId($pdo, $id);
		} else if(empty($commentEventId) === false) {
			$reply->data = Comment::getCommentByCommentEventId($pdo, $commentEventId)->toArray();
		} else if(empty($commentProfileId) === false) {
			$reply->data = Comment::getCommentByCommentProfileId($pdo, $commentProfileId)->toArray();
		}
	} else if($method === "PUT" || $method === "POST") {

		//enforce that the user has an XSRF token
		verifyXsrf();

		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject

		if(empty($requestObject->commentEventId) === true){
			throw(new \InvalidArgumentException("Comment is not linked to an event", 405));
		}

		//make sure comment content is available (required field)
		if(empty($requestObject->commentContent) === true) {
			throw(new \InvalidArgumentException ("No content for Comment.", 405));
		}

		// make sure comment date is accurate (optional field)
		if(empty($requestObject->commentDate) === true) {
			$requestObject->commentDate = null;
		} else {
			// if the date exists, Angular's milliseconds since the beginning of time MUST be converted
			$commentDate = DateTime::createFromFormat("U.u", $requestObject->commentDate / 1000);
			if($commentDate === false) {
				throw(new RuntimeException("invalid comment date", 400));
			}
			$requestObject->commentDate = $commentDate;
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the comment to update
			$comment = Comment::getCommentByCommentId($pdo, $id);
			if($comment === null) {
				throw(new RuntimeException("Comment does not exist", 404));
			}

			//enforce the user is signed in and only trying to edit their own comment
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $comment->getCommentProfileId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this comment", 403));
			}

			// update all attributes
			$comment->setCommentDate($requestObject->commentDate);
			$comment->setCommentContent($requestObject->commentContent);
			$comment->update($pdo);

			// update reply
			$reply->message = "Comment updated OK";

		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to post comments", 403));
			}

			// create new comment and insert into the database
			$comment = new Comment(generateUuidV4(), $requestObject->commentEventId, $_SESSION["profile"]->getProfileId(), $requestObject->commentContent, $requestObject->commentDate);
			$comment->insert($pdo);

			// update reply
			$reply->message = "Comment created OK";
		}

	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token.
		verifyXsrf();

		// retrieve the Comment to be deleted
		$comment = Comment::getCommentByCommentId($pdo, $id);
		if($comment === null) {
			throw(new RuntimeException("Comment does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own comment
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $comment->getCommentProfileId()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this comment", 403));
		}

		// delete comment
		$comment->delete($pdo);
		// update reply
		$reply->message = "Comment deleted OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}
// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

// encode and return reply to front end caller
header("Content-type: application/json");
echo json_encode($reply);


