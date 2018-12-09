<?php
namespace BackyardAstronomer\Astronomer\Profile;

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("uuid.php");

// grab the uuid generator
require_once( "uuid.php");
$pdo =  new \Secrets("/etc/apache2/capstone-mysql/.ini");

	$hash = password_hash("abc123", PASSWORD_ARGON2I, ["time_cost" => 384]);

$profile = new Profile(generateUuidV4(), null,  null, "amanda@nasa.com", $hash, null, "Amanda");
echo "Amanda's profile : $profile->getProfileId()";

$profile2 = new Profile(generateUuidV4(), null,  null, "amanda@nasa.com", $hash, null, "John");

$profile = new Profile(generateUuidV4(), null, null, "amanda@nasa.com", $hash, null, "Jacob");
