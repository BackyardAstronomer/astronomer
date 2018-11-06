<?php
namespace BackyardAstronomer\Astronomer;
require_once("Autoload.php");
require_once(dir(__DIR__,2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * Created by PhpStorm.
 * User: jjain
 * Date: 11/6/2018
 * Time: 3:34 PM
 */
class Event {
	use ValidateUuid;
	use ValidateDate;

	/**
 *ID of event; this is a primary key
	 *@var Uuid $eventId
	 */
	private $eventId;
	/**
 *This is a foreign key from the profile table; shows who is posting event
	 * @var Uuid $eventProfileId
	 */
	private $eventProfileId;
	/**
 * This is the event type identifier;
	 * @var Uuid $eventEventTypeId
	 */
	private $eventEventTypeId;
/**
 * This is the title of the posted event;
 * @var string $eventTitle
 */
	private $eventTitle;
	/**
	 * this will be a short description of the event being hosted
	 * @var string $eventContent
	 */
	private $eventContent;
	/**
	 *this is the starting date/time of the event
	 * @var \DateTime $eventStartDate
	 */
	private $eventStartDate;
	/**
	 * this is the end date/time of the event
	 * @var \DateTime $eventEndDate
	 */
	private $eventEndDate;

}