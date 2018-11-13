<?php
namespace BackyardAstronomer\Astronomer;
require_once("Autoload.php");
require_once(dirname(__DIR__,2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * Created by PhpStorm.
 * User: jjain
 * Date: 11/6/2018
 * Time: 3:34 PM
 * @author Jack Jain <jjain1998@gmail.com>
 * @version 1.0.0
 **/
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
	public function __construct($newEventId, $newEventEventTypeId, $newEventProfileId,
string $newEventTitle, string $newEventContent, $newEventStartDate = null, $newEventEndDate = null) {
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
	/**
	 * this is the accessor method for eventStartDate
	 *
	 * @return \DateTime value of start date
	 **/

	public function getEventStartDate(): \DateTime {
		return ($this->eventStartDate);
	}

	/**
	 * this is the mutator method for event start date
	 * @param \DateTime $newEventStartDate tweet date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newEventStartDate is not a valid object or string
	 * @throws \RangeException if $newEventStartDate is a date that does not exist
	 * @throws \TypeError if $newEventStartDate is not a date
	 * @throws \Exception for MySQL related errors
	 **/
	public function setEventStartDate($newEventStartDate = null) : void {
		// if date is null, use current date and time
		if($newEventStartDate === null) {
			$this->eventStartDate = new \DateTime();
			return;
		}
		//store the start date using ValidateDate trait
		try {
			$newEventStartDate = self::validateDateTime($newEventStartDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->eventStartDate = $newEventStartDate;
	}
	/**
	 * this is the accessor method for eventStartDate
	 *
	 * @return \DateTime value of start date
	 **/

	public function getEventEndDate(): \DateTime {
		return ($this->eventEndDate);
	}

	/**
	 * this is the mutator method for event start date
	 * @param \DateTime $newEventEndDate must be a date
	 * @throws \InvalidArgumentException if $newEventEndDate is not a valid object or string
	 * @throws \RangeException if $newEventEndDate is a date that does not exist
	 * @throws \TypeError if $newEventEndDate is not a date
	 * @throws \Exception for MySQL related errors
	 **/
	public function setEventEndDate($newEventEndDate = null) : void {
		// if date is null, use current date and time
		if($newEventEndDate === null) {
			$this->eventEndDate = new \DateTime();
			return;
		}
		//store the start date using ValidateDate trait
		try {
			$newEventEndDate = self::validateDateTime($newEventEndDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->eventEndDate = $newEventEndDate;
	}

	/**
	 *PDO Statements
	 */

	/**
	 *inserts this Event into the database
	 *
	 *@param \PDO $pdo PDO connection object
	 *@throws \PDOException when MySQL related errors occur
	 *@throws \TypeError if $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) : void {

		//query template
		$query = "INSERT INTO event(eventId, eventEventTypeId, eventProfileId, eventTitle, eventContent, eventStartDate, eventEndDate) VALUES (:eventId, :eventEventTypeId, :eventProfileId, :eventTitle, :eventContent, :eventStartDate, :eventEndDate)";
		$statement = $pdo->prepare($query);
		$formattedStartDate = $this->eventStartDate->format("Y-m-d H:i:s.u");
		$formattedEndDate = $this->eventEndDate->format("Y-m-d H:i:s.u");
		$parameters = ["eventId" => $this ->eventId->getBytes(), "eventEventTypeId" => $this->eventEventTypeId->getBytes(), "eventProfileId" => $this->eventProfileId->getBytes(), "eventTitle" => $this->eventTitle, "eventContent" => $this->eventContent,"eventStartDate" => $formattedStartDate, "eventEndDate" => $formattedEndDate];
		$statement->execute($parameters);
	}

	/**
	 *updates event in the database
	 *
	 *@param \PDO $pdo PDO connection object
	 *@throws \PDOException when MySQL related errors occur
	 *@throws \TypeError if $pdo is not a PDO statement
	 */
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE event SET eventId = :eventId, eventEventTypeId = :eventEventTypeId, eventProfileId = :eventProfileId, eventTitle = :eventTitle, eventContent = :eventContent, eventStartDate = :eventStartDate, eventEndDate = :eventEndDate WHERE eventId = :eventId";
		$statement = $pdo-> prepare($query);
		$formattedStartDate = $this->eventStartDate->format("Y-m-d H:i:s.u");
		$formattedEndDate = $this->eventEndDate->format("Y-m-d H:i:s.u");

		$parameters = ["eventId" => $this -> eventId->getBytes(), "eventEventTypeId" => $this->eventEventTypeId->getBytes(), "eventProfileId" => $this->eventProfileId->getBytes(), "eventTitle" => $this->eventTitle, "eventContent" => $this->eventContent, "eventStartDate" => $formattedStartDate, "eventEndDate" => $formattedEndDate];
		$statement->execute($parameters);
	}
	/**
	 * this will delete the event from MySQL
	 *
	 *@param \PDO $pdo PDO connection object
	 *@throws \PDOException when MySQL related errors occur
	 *@throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo) : void {

		// creating query template
		$query = "DELETE FROM event WHERE eventId = :eventId";
		$statement = $pdo->prepare($query);

		//binding member variables
		$parameters = ["eventId" => $this->eventId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Event by eventId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $eventId event id to search for
	 * @return Event|null Event found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/

	public static function getEventByEventId(\PDO $pdo, $eventId) : ?Event {
		//sanitize eventId before searching
		try{
			$eventId = self::validateUuid($eventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//creating query template
		$query = "SELECT eventId, eventEventTypeId, eventProfileId, eventTitle, eventContent, eventStartDate, eventEndDate FROM event WHERE eventId = :eventId";
		$statement = $pdo->prepare($query);
		// bind the event id to the placeholders in the template
		$parameters = ["eventId" => $eventId->getBytes()];
		$statement->execute($parameters);

		//grab event from database
		try{
			$event = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row === true) {
				$event = new Event($row["eventId"], $row["eventEventTypeId"], $row["eventProfileId"], $row["eventTitle"], $row["eventContent"], $row["eventStartDate"], $row["eventEndDate"]);
			}
		} catch(\Exception $exception){
			//if the row could not be converted rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($event);
	}

	/**
	 * gets Event by eventEventTypeId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $eventEventTypeId event type to search for
	 * @return \SPLFixedArray SplFixedArray of events found
	 * @throws \PDOException when MySql related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 */

	public static function getEventByEventEventTypeId(\PDO $pdo, string $eventEventTypeId) : \SplFixedArray {

		try{
			$eventEventTypeId = self::validateUuid($eventEventTypeId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//query template
		$query = "SELECT eventId, eventEventTypeId, eventProfileId, eventTitle, eventContent, eventStartDate, eventEndDate FROM event WHERE eventEventTypeId = :eventEventTypeId";
		$statement = $pdo->prepare($query);

		//bind event event typeId to placeholder in template
		$parameters =["eventEventTypeId" => $eventEventTypeId->getBytes()];
		$statement->execute($parameters);
		//bind array of events
		$events = new \SPLFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try{
				$event = new Event($row["eventId"], $row["eventEventTypeId"], $row["eventProfileId"], $row["eventTitle"], $row["eventContent"], $row["eventStartDate"], $row["eventEndDate"]);
				$events[$events->key()] = $event;
				$events->next();
			}catch(\Exception $exception) {
				//if the row cannot be converted, rethrow
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($events);
	}
}