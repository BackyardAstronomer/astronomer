<?php
namespace BackyardAstronomer\Astronomer;

require_once("Autoload.php");
require_once(dirname(__DIR__,2) . "/classes/Autoload.php");


use Ramsey\Uuid\Uuid;

class EventType implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * *this ID for the event type:
	 * this is a primary key identifies unique profiles
	 * @var Uuid $eventTypeId
	 */
	private $eventTypeId;
	/**
	 * *this is the event type by name:
	 * @var string
	 */
	private $eventTypeName;

	/**
	 * constructor EventTypes
	 *
	 * @param string|Uuid $eventTypeId id of this event
	 * @param string $eventTypeName string name of event type
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/











}


?>

