<?php
use Edu\Cnm\CrowdVibe\{Profile, Event, EventAttendance, Rating};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("uuid.php");

// grab the uuid generator
require_once( "uuid.php");
$pdo =  new \Secrets("/etc/apache2/capstone-mysql/.ini");

$profileActivationToken =
	$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);

$profile = new Profile(generateUuidV4(), bin2hex(random_bytes(16)), null, "amanda@nasa.com", $hash, null, "Amanda");