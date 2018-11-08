<?php
namespace BackyardAstronomer\Astronomer;

require_once("autoload.php");
require_once(dirname(__DIR__,2) . "/classes/autoload.php");


use Ramsey\Uuid\Uuid;

class EventType implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * this ID for the event type:
	 * this is a primary key identifies unique profiles
	 * @var Uuid $eventTypeId
	 **/
	private $eventTypeId;
	/**
	 * *this is the event type by name:
	 * @var string
	 **/
	private $eventTypeName;

	/**
	 * constructor EventTypes
	 *
	 * @param string|Uuid $newEventTypeId id this is a primary key identifies unique profiles
	 * @param string $newEventTypeName string this is the event type by name:
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newEventTypeId, string $newEventTypeName) {
		try {
			$this->setEventTypeId($newEventTypeId);
			$this->setEventTypeName($newEventTypeName);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for event Type id
	 *
	 * @return Uuid value of event Type id
	 **/
	public function getEventTypeId() : Uuid {
		return ($this->eventTypeId);
	}

	/**
	 * mutator method for event type id
	 *
	 * @param Uuid|string $newEventTypeId new value of event type id
	 * @throws \RangeException if $newEventTypeId is not positive
	 * @throws \TypeError if $newEventTypeId is not a uuid or string
	 **/
	public function  setEventTypeId($newEventTypeId) : void {
		try {
			$uuid = self::validateUuid($newEventTypeId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the Event Type Id
		$this->eventTypeId = $uuid;
	}

	/**
	 * accessor method for Event Type Name
	 *
	 * @return string value of Event Type Name content
	 **/
	public function getEventTypeName() : string {
		return ($this->eventTypeName);
	}

	/**
	 * mutator method for Event Type Name
	 *
	 * @param string $newEventTypeName new value of Event Type Name
	 * @throws \InvalidArgumentException if $newEventTypeName is not a string or insecure
	 * @throws \RangeException if $newEventTypeName is > 32 characters
	 * @throws \TypeError if $newEventTypeName is not a string
	 **/
public function setEventTypeName(string $newEventTypeName) : void {
		// verify the Event Type Name content is secure
		$newEventTypeName = trim($newEventTypeName);
		$newEventTypeName = filter_var($newEventTypeName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventTypeName) === true) {
			throw(new \InvalidArgumentException("Event type name content is empty or insecure"));
		}

	// verify the Event Type Name content will fit in the database
	if(strlen($newEventTypeName) > 32) {
		throw(new \RangeException("Event name content too large"));
	}

	// store the Event Type Name content
	$this->eventTypeName = $newEventTypeName;
}
************************************************************************************************************
	/**
	 * inserts EventType into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO EventType(eventTypeId, eventTypeName) VALUES(:eventTypeId, :eventTypeName)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["eventTypeId" => $this->eventTypeId->getBytes(), "eventTypeName" => $this->eventTypeName,];
		$statement->execute($parameters);
	}

	/**
	 * deletes this EventType from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM EventType WHERE eventTypeId = :eventTypeId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["eventTypeId" => $this->eventTypeId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates EventType in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE EventType SET eventTypeId = :eventTypeId, eventTypeName = :eventTypeName";
		$statement = $pdo->prepare($query);


		$parameters = ["eventTypeId" => $this->eventTypeId->getBytes(), "eventTypeName" => $this->eventTypeName,];
		$statement->execute($parameters);
	}








}