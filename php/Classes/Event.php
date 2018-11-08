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

	/**
	 * this is the accessor method for the eventEventTypeId
	 *
	 * @return Uuid value of eventEventTypeId
	 */

	public function getEventEventTypeId() : Uuid {
		return ($this->eventEventTypeId);
	}
	/**
	 * this is the mutator method for eventEventTypeId
	 *
	 * @param Uuid $newEventEventTypeId new value for eventEventTypeId
	 * @throws \RangeException if $newEventEventTypeId is not positive
	 * @throws \ TypeError if $newEventEventTypeId is not a Uuid
	 */

	public function setEventEventTypeId($newEventEventTypeId) : void {
		try {
			$uuid = self::validateUuid($newEventEventTypeId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// converting and storing eventEventTypeId
		$this->eventEventTypeId= $uuid;
	}

	/**
	 * this is the accessor method for the eventProfileId
	 *
	 * @return Uuid value of eventProfileId
	 */

	public function getEventProfileId(): Uuid {
		return $this->eventProfileId;
	}

	/**
	 * this is the mutator method for the eventProfileId
	 *
	 * @param Uuid $newEventProfileId new value for the event profile id
	 * @throws \RangeException if $newEventProfileId is not positive
	 * @throws \TypeError if $newEventProfileId is not a Uuid
	 */

	public function setEventProfileId($newEventProfileId) : void {
		try{
			$uuid = self::validateUuid($newEventProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//conversion and storage of event Profile Id
		$this->eventProfileId = $uuid;
	}

	/**
	 * this is the accessor method for eventTitle
	 *
	 * @return string value of eventTitle
	 */

	public function getEventTitle(): string {
		return $this->eventTitle;
	}

	/**
	 * this is the mutator method for event title
	 *
	 * @param string $newEventTitle new name of event
	 * @throws \InvalidArgumentException if $newEventTitle is not a string or insecure
	 * @throws \RangeException if $newEventTitle is greater than 32 characters
	 * @throws \TypeError if $newEventTitle is not a string
	 */

	public function setEventTitle(string $newEventTitle) : void {
		//verify event title is secure
		$newEventTitle = trim($newEventTitle);
		$newEventTitle = filter_var($newEventTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventTitle)=== true) {
			throw (new \InvalidArgumentException("Event title content is empty or insecure"));
		}
		//verify event title will fit in the database
		if(strlen($newEventTitle) > 32) {
			throw(new \RangeException("Event title is too long"));
		}
		//verify event title is a string
		/**
		 * @documentation http://php.net/manual/en/function.is-string.php
		 */
		if(is_string($newEventTitle)=== false) {
			throw(new \TypeError("Event title is not a string"));
		}
		//convert and store Event Title
		$this->eventTitle = $newEventTitle;
	}

	/**
	 * this is the accessor method for the Event Content
	 * @return string value of event content
	 */

	public function getEventContent(): string {
		return $this->eventContent;
	}
	/**
	 * this is the mutator method for eventContent
	 *
	 * @param string $newEventContent
	 * @throws \InvalidArgumentException if $newEventContent is not a string or insecure
	 * @throws \RangeException if $newEventContent is > 255 characters
	 * @throws \TypeError if $newEventContent is not a string
	 */

	public function setEventContent (string $newEventContent) : void {
		//verify event content is secure
		$newEventContent = trim($newEventContent);
		$newEventContent = filter_var($newEventContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventContent)=== true) {
			throw(new \InvalidArgumentException("Event content is empty or insecure"));
		}
		//verify event content fits into the database
		if(strlen($newEventContent) > 255) {
			throw(new \RangeException("Event content is too long"));
		}
		//convert and store Event Content
		$this->eventContent = $newEventContent;
	}
}