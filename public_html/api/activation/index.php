<?php
require_once dirname(__DIR__,3)."/php/Classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use BackyardAstronomer\Astronomer\Profile;
/**
 * API to check profile activation status
 * @author Gkephart
 */
// Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try{
	//grab MySQL connection object
	$secrets =  new \Secrets("/etc/apache2/capstone-mysql/cohort22/astronomers");
	$pdo = $secrets->getPdoObject();

	//check HTTP method being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input (because end users are always malicious)
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);

	//make sure the activation token is the correct size
	if(strlen($activation) !== 32){
		throw(new InvalidArgumentException("activation has an incorrect length", 405));
	}

	//verify activation token is a string value in hexadecimal
	if(ctype_xdigit($activation) === false) {
		throw(new \InvalidArgumentException("activation is empty or has invalid content", 405));
	}
	//handle the GET HTTP request
	if($method === "GET"){

		//set XSRF cookie
		setXsrfCookie();

		//find profile associated with the activation token
		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);

		//verify the profile is not null
		if($profile !== null){

			//make sure activation token matches
			if($activation === $profile->getProfileActivationToken()) {

				//set activation to null
				$profile->setProfileActivationToken(null);

				//update profile in the database
				$profile->update($pdo);

				//set the reply for the end user
				$reply->data = "Thank you for activating your account, you will be auto-redirected to your profile shortly.";
			}
		}else {
			//throw an exceprion if the activation token does not exist
			throw(new RuntimeException("Profile with this activation code does not exist", 404));
		}
	}else {
		//throw an exception if HTTP request is not a GET
		throw(new InvalidArgumentException("Invalid HTTP method request", 403));
	}
} catch(Exception $exception){
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}catch(TypeError $typeError){
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

//prepare and send the reply
header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}
echo json_encode($reply);