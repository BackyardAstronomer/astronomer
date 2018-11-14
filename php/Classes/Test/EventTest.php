<?php
namespace BackyardAstronomer\Astronomer;
use BackyardAstronomer\astronomer\{Event, Profile, Comment};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");


/**
 *  Full PHPUnit test for Event class
 *
 * This is a complete PHPUnit test for the Event class. It is complete because *all* MySQL/PDO enabled methods are tested for both invalid and valid inputs.
 *
 * @see \BackyardAstronomer\Astronomer\Event
 * @author Jack Jain <jjain2@cnm.edu>
 */

class EventTest extends AstronomerTestSetUp {
	/**
	 * Profile that created the Event; this is for foreign key relations
	 *@var Profile profile
	 */
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $VALID_HASH
	 */
	protected $VALID_PROFILE_HASH;

	/**
	 * content of the event
	 * @var string $VALID_EVENT_CONTENT
	 */
	protected $VALID_EVENT_CONTENT = "PHPUnit test passing";

	/**
	 * content of the updated event
	 * @var string $VALID_EVENT_CONTENT2
	 */
	protected $VALID_EVENT_CONTENT2 = "PHPUnit test still passing";

	/**
	 * Title of the event
	 * @var string $VALID_EVENT_TITLE
	 */
	protected $VALID_EVENT_TITLE = "PHPUnit test passing";

	/**
	 *title of the updated event
	 * @var string $VALID_EVENT_TITLE2
	 */
	protected $VALID_EVENT_TITLE2 = "PHPUnit test still passing";

	/**
	 * timestamp of the event; this starts as null and is assigned later
	 * @var \DateTime $VALID_EVENT_START_DATE
	 */
	protected $VALID_EVENT_START_DATE = null;

	/**
	 * timestamp of the end of the event; this starts as null and is assigned later
	 * @var \DateTime $VALID_EVENT_END_DATE
	 */
	protected $VALID_EVENT_END_DATE = null;

	/**
	 * create dependant objects before running each test
	 */
	public final function setUp() : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);

		//create and insert a profile to own the event
		$this->profile = new Profile(generateUuidV4(), 'thisisanemail@test.com', "this is a bio blah blah blah blah", "Test Astronomer", null, null, $this->VALID_PROFILE_HASH);
		$this->profile->insert($this->getPDO());

		//calculate the start date (date unit test was set up...)
		$this->VALID_EVENT_START_DATE = new \DateTime();
		//calculate the end date
		$this->VALID_EVENT_END_DATE = new \DateTime();
	}

	public function testInsertValidEvent() : void {
		//count the number of rows and save for later
		$numRows = $this->getConnection()->getRowCount("event");

		//create new event and insert into MySQL
		$eventId = generateUuidV4();
		$event = new Event($eventId, $this->profile->getProfileId(), $this->profile->getProfileId(), $this->VALID_EVENT_TITLE, $this->VALID_EVENT_CONTENT, $this->VALID_EVENT_START_DATE, $this->VALID_EVENT_END_DATE);
		$event->insert($this->getPDO());

		//grab the data form MySQL and enforce the fields match our expectations
		$pdoEvent = Event::getEventByEventId($this->getPDO(), $event->getEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("event"));
		$this->assertEquals($pdoEvent->getEventId(), $eventId);
		$this->assertEquals($pdoEvent->getEventEventTypeId(), $this->profile->getProfileId());
		$this->assertEquals($pdoEvent->getEventProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoEvent->getEventTitle(), $this->VALID_EVENT_TITLE);
		$this->assertEquals($pdoEvent->getEventContent(), $this->VALID_EVENT_CONTENT);
		//format the dates 2 seconds since the beginning of time to avoid rounding error
		$this->assertEquals($pdoEvent->getEventStartDate()->getTimestamp(), $this->VALID_EVENT_START_DATE->getTimestamp());
		$this->assertEquals($pdoEvent->getEventEndDate()->getTimestamp(), $this->VALID_EVENT_END_DATE->getTimestamp());
	}

	/**
	 * test inserting Event, editing it, and updating it
	 */

	public function testUpdateValidEvent() : void{
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("event");

		//create a new Event and insert into MySQL
		$eventId = generateUuidV4();
		$event = new Event($eventId, $this->profile->getProfileId(), $this->profile->getProfileId(), $this->VALID_EVENT_TITLE, $this->VALID_EVENT_CONTENT, $this->VALID_EVENT_START_DATE, $this->VALID_EVENT_END_DATE);
		$event->insert($this->getPDO());

		//edit the Event and update in MySQL
		$event->setEventContent($this->VALID_EVENT_CONTENT2);
		$event->update($this->getPDO());

		//grab the data form MySQL and enforce the fields match our expectations
		$pdoEvent = Event::getEventByEventId($this->getPDO(), $event->getEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("event"));
		$this->assertEquals($pdoEvent->getEventId(), $eventId);
		$this->assertEquals($pdoEvent->getEventEventTypeId(), $this->profile->getProfileId());
		$this->assertEquals($pdoEvent->getEventProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoEvent->getEventTitle(), $this->VALID_EVENT_TITLE2);
		$this->assertEquals($pdoEvent->getEventContent(), $this->VALID_EVENT_CONTENT2);
		//format the dates 2 seconds since the beginning of time to avoid rounding error
		$this->assertEquals($pdoEvent->getEventStartDate()->getTimestamp(), $this->VALID_EVENT_START_DATE->getTimestamp());
		$this->assertEquals($pdoEvent->getEventEndDate()->getTimestamp(), $this->VALID_EVENT_END_DATE->getTimestamp());
	}

	/**
	 * test creating an Event and then deleting it
	 */
	public function testDeleteValidEvent() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("event");

		//create a new Event and insert into MySQL
		$eventId = generateUuidV4();
		$event = new Event($eventId, $this->profile->getProfileId(), $this->profile->getProfileId(), $this->VALID_EVENT_TITLE, $this->VALID_EVENT_CONTENT, $this->VALID_EVENT_START_DATE, $this->VALID_EVENT_END_DATE);
		$event->insert($this->getPDO());

		//delete Event from MySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("event"));
		$event->delete($this->getPDO());

		// grab the data from MySQL and enforce the Event does not exist
		$pdoEvent = Event::getEventByEventId($this->getPDO(), $event->getEventId());
		$this->assertNull($pdoEvent);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("event"));
	}
}