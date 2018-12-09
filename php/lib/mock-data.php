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

$profile = new Profile(generateUuidV4(), null,  null, "amanda@nasa.com", $hash, null, "Amanda");
$profile->insert($pdo);
echo "Amanda's profile :". $profile->getProfileId() . PHP_EOL;

$profile2 = new Profile(generateUuidV4(), null,  null, "john@nasa.com", $hash, null, "John");
$profile2->insert($pdo);
echo "johns's profile : " . $profile2->getProfileId()->toString() . PHP_EOL;

$profile3 = new Profile(generateUuidV4(), null, null, "jacob@nasa.com", $hash, null, "Jacob");
$profile3->insert($pdo);
echo "jacob's profile : " . $profile3->getProfileId()->toString() . PHP_EOL;

$eventTypeId = generateUuidV4();
$eventType = new EventType($eventTypeId, "This Is Event Name");
$eventType->insert($pdo);

//format the sunrise date to use for testing
$startDate = new \DateTime("2019-01-02 11:14:00");

//format the sunset date to use for testing
$endDate = new\DateTime("2019-01-02 12:14:00");

//echo "event Type Id: " . $eventTypeId->getEventTypeId();

$event = new Event(generateUuidV4(), $eventType->getEventTypeId(), $profile->getProfileId(), "Star Party", "This is content", $startDate, $endDate);
$event->insert($pdo);
echo "event 1 Id:" . $event->getEventId() . PHP_EOL;


$event2 = new Event(generateUuidV4(), $eventType->getEventTypeId(), $profile2->getProfileId(), "Lecture", "Gonna talk", $startDate, $endDate);
$event2->insert($pdo);
echo "event 2 Id:" . $event2->getEventId() . PHP_EOL;


$event3 = new Event(generateUuidV4(), $eventType->getEventTypeId(), $profile3->getProfileId(), "Meet up", "Gonna meet", $startDate, $endDate);
$event3->insert($pdo);
echo "event 3 Id:" . $event3->getEventId() . PHP_EOL;


$commentId = generateUuidV4();

$comment = new Comment($commentId, $event2->getEventId(), $profile2->getProfileId(), "I'm gonna be there!", new \DateTime);
$comment->insert($pdo);
echo "comment Id" . $comment->getCommentId();

