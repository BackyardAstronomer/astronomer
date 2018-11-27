<?php

require_once dirname(__DIR__, 3) . "../vendor/autoload.php";
require_once dirname(__DIR__, 3) . "../php/classes/autoload.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "../php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "../php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "../php/lib/jwt.php";

use BackyardAstronomer\Astronomer\ {
	Comment
};

/**
 * This is the api for the comment class
 *
 * @author Stephen Pelot <stephenpelot@gmail.com>
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
	$commentId = filter_input(INPUT_GET, "commentId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentEventId = filter_input(INPUT_GET, "commentEventId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentProfileId = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentContent = filter_input(INPUT_GET, "commentContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentDate = filter_input(INPUT_GET, "commentDate", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//ensure id is valid for methods that need it
	if(($method === "DELETE" || $method = "PUT") && (empty($commentId) === true)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 401));}

		// handle GET request - if id is present, that comment is returned, otherwise all comments are returned
		if($method === "GET") {
			//set xsrf token
			setXsrfCookie();
			//get a specific comment or all comment and update reply
			if(empty($Id) === false) {
				$reply->data = Comment::getCommentByCommentProfileId($pdo, $commentId);
			} else if(empty($eventCommentTypeId) === false) {
				$reply->data = Comment::getCommentByCommentProfileId($pdo, $_SESSION["commentType"]->getEventId())->toArray();
			} else if(empty($commentProfileId) === false) {
				//if user is logged in grab all comments by that user based on log in
				$reply->data = Comment::get($pdo, $_SESSION["profile"]->getProfileId())->toArray();
			} else {
				$reply->data = Comment::getAllComments($pdo)->toArray();
			}
		} else if($method === "PUT" || $method === "POST") {
			//enforce user has xsrf token
			verifyXsrf();
			// enforce user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be signed in to create comments", 401));
			}
			$requestContent = file_get_contents("php://input");
			// retrieves JSON package that the front end sent, and stores it in $requestContent
			$requestObject = json_decode($requestContent);
			// this line decodes json package and stores result in $requestObject
			// ensure event content is available (required)
			if(empty($requestObject->commentContent) === true) {
				throw(new \InvalidArgumentException("No content for Comment", 405));
			}

			// ensure date is not null
			if(empty($requestObject->eventStartDate) === true) {
				throw(new \InvalidArgumentException("comments must have dateTime", "https://http.cat/[406].jpeg"));
			}
		}
};