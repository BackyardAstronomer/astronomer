<?php
namespace BackyardAstronomer\Astronomer;

require_once("Autoload.php");
require_once(dirname(__DIR__,2) . "/classes/Autoload.php");


use Ramsey\Uuid\Uuid;

class Rsvp implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * id of this rsvp Id ; this is a Primary key also a composite of rsvpProfileId and rsvpEventID
	 * @var Uuid $rsvpId
	 **/
	private $rsvpId;
	/**
	 * id for this rsvp Profile Id; this is a foreign key
	 * @var Uuid $rsvpProfileId
	 **/
	private $rsvpProfileId;
	/**
	 * id of this rsvp Event ID ; this is a foreign key
	 * @var Uuid $rsvpEventID
	 **/
	private $rsvpEventID;

	/**
	 * This integer that counts the number of people that RSVP to an event
	 * @var TINYINT $rsvpEventCounter
	 **/
	private $rsvpEventCounter;










}


?>