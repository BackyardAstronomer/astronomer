<?php
use BackyardAstronomer\Astronomer\{Profile, Event, EventType, Comment, Rsvp};

// grab the class under scrutiny
require_once(dirname(__DIR__, 1) . "/Classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once("uuid.php");

// grab the uuid generator
require_once( "uuid.php");
$pdo =  new \Secrets("/etc/apache2/capstone-mysql/cohort22/astronomers");
$pdo = $pdo->getPdoObject();

	$hash = password_hash("abc123", PASSWORD_ARGON2I, ["time_cost" => 384]);

//$profile = new Profile(generateUuidV4(), null,  null, "amanda@nasa.com", $hash, null, "Amanda");
//$profile->insert($pdo);
//echo "Amanda's profile : $profile->getProfileId()";

$profile2 = new Profile(generateUuidV4(), null,  null, "john@nasa.com", $hash, null, "John");
$profile2->insert($pdo);
echo "johns's profile : " . $profile2->getProfileId();

$profile3 = new Profile(generateUuidV4(), null, null, "jacob@nasa.com", $hash, null, "Jacob");
$profile3->insert($pdo);
echo "jacob's profile : " . $profile3->getProfileId();

$event = new Event(id)