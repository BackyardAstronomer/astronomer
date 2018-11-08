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
	 * This is the event type identifier; foreign key;
	 * @var Uuid $eventEventTypeId
	 */
	private $eventEventTypeId;
	/**
 *This is a foreign key from the profile table; shows who is posting event
	 * @var Uuid $eventProfileId
	 */
	private $eventProfileId;
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
	/**
	 * @param Uuid $newEventId for a new event
	 * @param Uuid $newEventEventTypeId for determining the event type
	 * @param Uuid $newEventProfileId the profile that created the event
	 * @param string $newEventTitle title of the event
	 * @param string $newEventContent short description of the event
	 * @param \DateTime $newEventStartDate date of the event
	 * @param \DateTime $newEventEndDate date the event ends
	 *
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if strings are too long
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

	/**
	 * This is the constructor function for the Event class
	 */
	public function __construct($newEventId, $newEventEventTypeId, $newEventProfileId,
$newEventTitle, $newEventContent, $newEventStartDate, $newEventEndDate) {
		try {
			$this -> setEventId($newEventId);
			$this -> setEventEventTypeId($newEventEventTypeId);
			$this -> setEventProfileId($newEventProfileId);
			$this -> setEventTitle($newEventTitle);
			$this -> setEventContent($newEventContent);
			$this -> setEventStartDate($newEventStartDate);
			$this -> setEventEndDate($newEventEndDate);
		}
		//this will determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * This is the accessor method for the event id
	 * it will @return Uuid value of event id
	 */
	public function getEventId(): Uuid {
		return ($this->eventId);
	}

	/**
	 * this is the mutator method for eventId
	 *
	 * @param Uuid $newEventId new eventId value
	 * @throws \RangeException if $newEventId is not positive
	 * @throws \TypeError if $newEventId is not a Uuid
	 *
	 */
	public function setEventId($newEventId) : void {
		try {
			$uuid = self::validateUuid($newEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		//converting and storing eventId
		$this->eventId = $uuid;
	}
}