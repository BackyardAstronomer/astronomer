<?php
namespace BackyardAstronomer\Astronomer;

namespace BackyardAstronomer\Astronomer;
require_once("AstronomerTestSetUp.php");

use BackyardAstronomer\Astronomer\Rsvp;

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

require_once(dirname(__DIR__)."/autoload.php");
require_once(dirname(__DIR__,3) . "/vendor/autoload.php");

/**
 * Full PHPUnit test for the Rsvp class
 *
 * This is a complete PHPUnit test of the Rsvp class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Rsvp
 * @author Dayn Augustson <daugustson@cnm.edu>
 **/
class TsvpTest extends AstronomerTestSetUp{
	/**
	 * Profile that created the Rsvp; this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $VALID_HASH
	 */
	protected $VALID_PROFILE_HASH;

	/**
	 * valid activation token
	 * @var string $profileActivationToken
	 */
	protected $VALID_ACTIVATION_TOKEN = null;

	/**
	 *  eventType that created the Rsvp
	 * @var string $eventType
	 */
	protected $eventType = null;

	/**
	 * Event that created the Rsvp; this is for foreign key relations
	 * @var string $event
	 **/
	protected $event = null;

	/**
	 * event count for RSVP
	 * @var int $VALID_RSVPEVENTCOUNTER
	 **/
	protected $VALID_RSVPEVENTCOUNTER = "1";

	/**
	 * event count for RSVP2
	 *
	 * @var int $VALID_RSVPEVENTCOUNTER2
	 **/
	protected $VALID_RSVPEVENTCOUNTER2 = "2";

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$this->VALID_ACTIVATION_TOKEN = bin2hex(random_bytes(16));


		// create and insert a Profile to the test Rsvp
		$profileId = generateUuidV4();
		$this->profile = new Profile($profileId, $this->VALID_ACTIVATION_TOKEN, "I am Groot", "test@phpunit.de",$this->VALID_PROFILE_HASH ,"https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "Dilbert");
		$this->profile->insert($this->getPDO());

		// create and insert EventType to test Rsvp
		$eventTypeId = generateUuidV4();
		$this->eventType = new EventType($eventTypeId, "blind star watch");
		$this->eventType->insert($this->getPdo());

		// create and insert a Event to test Rsvp
		$eventId = generateUuidV4();
		$this->event = new Event($eventId, $eventTypeId, $profileId, "blind star watch party","May the braille be with you","\DateTime()", "\DateTime()");
		$this->event->insert($this->getPdo());

	}

	/**
	 * test inserting a valid Rsvp and verify that the actual mySQL data matches
	 **/
	public function testInsertValidRsvp() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert into mySQL
		$rsvp = new Rsvp($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_RSVPEVENTCOUNTER);
		$rsvp->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoRsvp = Rsvp::getRsvpByRsvpProfileIdRsvpEventId($this->getPDO(), $rsvp->getRsvpProfileId(), $rsvp->getRsvpEventId());
		$this->asserEquals($numRows + 1, $this->getConnectoion()->getRowCount("rsvp"));
		$this->assertEquals($pdoRsvp->getRsvpProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoRsvp->getRsvpEventId(), $this->event->getEventId());
		$this->assertEquals($pdoRsvp->getRsvpEventCounter(), $this->VALID_RSVPEVENTCOUNTER);
	}

	/**
	 * test creating a Rsvp and then deleting it
	 **/
	public function testDeleteValidRsvp() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert into mySQL
		$rsvp = new Rsvp($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_RSVPEVENTCOUNTER);
		$rsvp->insert($this->getPDO());

		//delete the Rsvp from MySQL
		$this->asserEquals($numRows + 1, $this->getConnectoion()->getRowCount("rsvp"));
		$rsvp->delete($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoRsvp = Rsvp::getRsvpByRsvpProfileIdRsvpEventId($this->getPDO(), $rsvp->getRsvpProfileId(), $rsvp->getRsvpEventId());
		$this->assertNull($pdoRsvp);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("rsvp"));
	}

/**
* gets the Rsvp by RsvpProfileId
**/
	public function testGetValidRsvpByRsvpProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert into mySQL
		$rsvp = new Rsvp($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_RSVPEVENTCOUNTER);
		$rsvp->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Rsvp::getRsvpByRsvpProfileId($this->getPDO(), $rsvp->getRsvpProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\Rsvp", $results);

		//grab the result from the array and validate it
		$pdoRsvp = $results[0];
		$this->assertEquals($pdoRsvp->getRsvpProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoRsvp->getRsvpEventId(), $this->event->getEventId());
		$this->assertEquals($pdoRsvp->getRsvpEventCounter(), $this->VALID_RSVPEVENTCOUNTER);

	}

	/**
	 * gets the Rsvp by RsvpEventId
	 **/
	public function testGetValidRsvpByRsvpEventId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert into mySQL
		$rsvp = new Rsvp($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_RSVPEVENTCOUNTER);
		$rsvp->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Rsvp::getRsvpByRsvpEventeId($this->getPDO(), $rsvp->getRsvpEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\Rsvp", $results);

		//grab the result from the array and validate it
		$pdoRsvp = $results[0];
		$this->assertEquals($pdoRsvp->getRsvpProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoRsvp->getRsvpEventId(), $this->event->getEventId());
		$this->assertEquals($pdoRsvp->getRsvpEventCounter(), $this->VALID_RSVPEVENTCOUNTER);

	}
/**
//test grab all Rsvp
	public function testGetAllValidRsvp() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("eventType");

		//create a new EventType and insert into mySql
		$rsvp = new Rsvp($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_RSVPEVENTCOUNTER);
		$rsvp->insert($this->getPDO());

		//grab the data from mySQL and make sure the field match
		// getting data from mySQL and enforce the fields match our expectations
		$results = eventType::getAllRsvp($this->getPDO());
		$this->assertEquals($numRows + 1,$this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		//enfoce no other objects bled into test
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\EventType", $results);

		//grab results for the array and validate
		$pdoRsvp = $results[0];
		$this->assertEquals($pdoRsvp->(), $);
		$this->assertEquals($pdoRsvp->getRsvpProfiledId(), $this->Profile->getProfileId);
		$this->assertEquals($pdoRsvp->getRsvpEventId(), $this->event->getEventId);
		$this->assertEquals($pdoRsvp->getRevpEventCounter(), $this->VALID_RSVPEVENTCOUNTER);

	}
**/
}