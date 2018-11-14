<?php
namespace BackyardAstronomer\Astronomer;

require_once("autoload.php");
require_once(dirname(__DIR__,2) . "/vendor/autoload.php");


use Ramsey\Uuid\Uuid;

class Rsvp implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;


	/**
	 * id for this rsvp Profile Id; this is a foreign key
	 * @var Uuid $rsvpProfileId
	 **/
	private $rsvpProfileId;
	/**
	 * id of this rsvp Event ID ; this is a foreign key
	 * @var Uuid $rsvpEventID
	 **/
	private $rsvpEventId;
	/**
	 * This integer that counts the number of people that RSVP to an event
	 * @var int $rsvpEventCounter
	 **/
	private $rsvpEventCounter;

	/**
	 * constructor EventTypes
	 *
	 * @param string|Uuid $newRsvpProfileId id of rsvp to profile Id
	 * @param string|Uuid $newRsvpEventId id of rsvp to event Id
	 * @param integer $newRsvpEventCounter this counts the number of people RSVP to an event
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newRsvpProfileId, $newRsvpEventId, int $newRsvpEventCounter) {
		try {
			$this->setRsvpProfileId($newRsvpProfileId);
			$this->setRsvpEventId($newRsvpEventId);
			$this->setRsvpEventCounter($newRsvpEventCounter);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
}


	/**
	 * accessor method for rsvp profile Id
	 *
	 * @return Uuid value of rsvpProfileId
	 **/
	public function getRsvpProfileId() : Uuid {
		return($this->rsvpProfileId);
	}

	/**
	 * mutator method for rsvp profile Id
	 *
	 * @param Uuid|string $newRsvpProfileId new value of rsvp Profile Id
	 * @throws \RangeException if $newRsvpProfileId is not positive
	 * @throws \TypeError if $newRsvpProfileId is not a uuid or string
	 **/
	public function setRsvpProfileId($newRsvpProfileId) : void {
		try {
			$uuid = self::validateUuid($newRsvpProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the rsvp Profile Id
		$this->rsvpProfileId = $uuid;
	}

	/**
	 * accessor method for rsvp Event Id
	 *
	 * @return Uuid value of rsvpEventId
	 **/
	public function getRsvpEventId() : Uuid {
		return($this->rsvpEventId);
	}

	/**
	 * mutator method for rsvp Event ID
	 *
	 * @param Uuid|string $newRsvpEventID new value of rsvp Event ID
	 * @throws \RangeException if $newRsvpEventID is not positive
	 * @throws \TypeError if $newRsvpEventID is not a uuid or string
	 **/
	public function setRsvpEventId( $newRsvpEventId) : void {
		try {
			$uuid = self::validateUuid($newRsvpEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the rsvp Event Id
		$this->rsvpEventId = $uuid;
	}

	/**
	 * accessor method for rsvp Event Counter
	 *
	 * @return integer value of rsvp Event Counter content
	 **/
	public function getRsvpEventCounter() : int {
		return ($this->rsvpEventCounter);
	}

	/**
	 * mutator method for rsvp Event Counter
	 *
	 * @param integer $newRsvpEventCounter new value of rsvp Event Counter Name
	 * @throws \InvalidArgumentException if $newRsvpEventCounter is not a tinyint or insecure
	 * @throws \RangeException if $newRsvpEventCounter is > 225 characters
	 * @throws \TypeError if $newRsvpEventCounter is not a tinyint
	 **/
	public function setRsvpEventCounter(int $newRsvpEventCounter) : void {
		//check to make sure not getting a negative number
		if($newRsvpEventCounter <=0){
			throw(new \RangeException("rsvp event counter is not positive"));
		}

		// store the rsvpEventCounter content
		$this->rsvpEventCounter = $newRsvpEventCounter;
	}


	/**
	 * inserts Rsvp into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO rsvp (rsvpProfileId, rsvpEventId, rsvpEventCounter  ) VALUES(:rsvpProfileId, :rsvpEventId, :rsvpEventCounter )";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["rsvpProfileId" => $this->rsvpProfileId->getBytes(), "rsvpProfileId" => $this->rsvpProfileId->getBytes(), "rsvpEventCounter" => $this->rsvpEventCounter];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Rsvp from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM rsvp WHERE rsvpEventId = :rsvpEventId  and rsvpProfileId = :rsvpProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["rsvpEventId" => $this->rsvpEventId->getBytes(), "rsvpProfileId" => $this->rsvpProfileId->getBytes()];
		$statement->execute($parameters);
	}


	/**
	 * gets the Rsvp by resvpEventId and RsvpProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $rsvpId tweet id to search for
	 * @return Rsvp|null Rsvp found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getRsvpByRsvpProfileIdRsvpEventId(\PDO $pdo,$rsvpProfileId, $rsvpEventId ) : ?Rsvp {
		// sanitize the rsvpId before searching
		try {
			$rsvpProfileId = self::validateUuid($rsvpProfileId);
			$rsvpEventId = self::validateUuid($rsvpEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT rsvpProfileId, rsvpEventId, rsvpEventCounter FROM rsvp WHERE rsvpProfileId = :rsvpProfileId and WHERE rsvpEventId = :rsvpEventId  ";
		$statement = $pdo->prepare($query);

		// bind the rsvpEventId and rsvpProfileId to the place holder in the template
		$parameters = ["rsvpEventId" => $rsvpEventId->getBytes()];
		$parameters = ["rsvpProfileId" => $rsvpProfileId->getBytes()];
		$statement->execute($parameters);

		// grab the rsvp from mySQL
		try {
			$rsvp = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$rsvp = new rsvp($row["rsvpProfileId"], $row["rsvpEventId"], $row["rsvpEventCounter"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($rsvp);
	}


	/**
	 * gets the Rsvp by  RsvpProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $rsvpProfileId profile id to search by
	 * @return \SplFixedArray SplFixedArray of rsvps  found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getRsvpByRsvpProfileId(\PDO $pdo, $rsvpProfileId) : \SplFixedArray {

		try {
			$rsvpProfileId = self::validateUuid($rsvpProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT rsvpProfileId, rsvpEventId, rsvpEventCounter FROM rsvp WHERE rsvpProfileId = :rsvpProfileId";
		$statement = $pdo->prepare($query);
		// bind the rsvp profile id to the place holder in the template
		$parameters = ["rsvpProfileId" => $rsvpProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of rsvps
		$rsvps = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$rsvp = new Rsvp($row["rsvpProfileId"], $row["rsvpEventId"], $row["rsvpEventCounter"]);
				$rsvps[$rsvps->key()] = $rsvp;
				$rsvps->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($rsvps);
	}

	/**
	 * gets the Rsvp by rsvpEventId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $rsvpEventId event id to search by
	 * @return \SplFixedArray SplFixedArray of rsvps found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getRsvpByRsvpEventId(\PDO $pdo, $rsvpEventId) : \SplFixedArray {

		try {
			$rsvpEventId = self::validateUuid($rsvpEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT rsvpProfileId, rsvpEventId, rsvpEventCounter FROM rsvp WHERE rsvpEventId = :rsvpEventId";
		$statement = $pdo->prepare($query);
		// bind the rsvp event id to the place holder in the template
		$parameters = ["rsvpEventId" => $rsvpEventId->getBytes()];
		$statement->execute($parameters);
		// build an array of rsvp
		$rsvps = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$rsvp = new Rsvp($row["rsvpProfileId"], $row["rsvpEventId"], $row["rsvpEventCounter"]);
				$rsvps[$rsvps->key()] = $rsvp;
				$rsvps->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($rsvps);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		return($fields);
	}


}

