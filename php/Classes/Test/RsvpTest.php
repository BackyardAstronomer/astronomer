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
	 *  eventType that created the Rsvp
	 * @var $VALID_HASH
	 */
	protected $eventType = null;

	/**
	 * Event that created the Rsvp; this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $event = null;

	/**
	 * event count for RSVP
	 * @var Profile profile
	 **/
	protected $VALID_RSVPEVENTCOUNTER = "1";

	/**
	 * event count for RSVP2
	 * @var Profile profile
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


		// create and insert a Profile to the test Rsvp
		$this->profile = new Profile(generateUuidV4(), Null, "I am Groot", "test@phpunit.de",$this->VALID_PROFILE_HASH ,"https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "Dilbert");
		$this->profile->insert($this->getPDO());

		// create and insert EventType to test Rsvp
		$this->eventType = new EventType(generateUuidV4(), "blind star watch");
		$this->eventType->insert($this->getPdo);

		// create and insert a Event to test Rsvp
		$this->event = new Event(generateUuidV4(), $this->eventType->getEventTypeId(), $this->profileType->getProfileTypeId(), "blind star watch party","May the braille be with you","\DateTime()", "\DateTime()");

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
		$this->asserEquals($numRows = 1, $this->getConnectoion()->getRowCount("rsvp"));
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
		$this->asserEquals($numRows = 1, $this->getConnectoion()->getRowCount("rsvp"));
		$rsvp->delete($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoRsvp = Rsvp::getRsvpByRsvpProfileIdRsvpEventId($this->getPDO(), $rsvp->getRsvpProfileId(), $rsvp->getRsvpEventId());
		$this->assertNull($pdoRsvp);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("tweet"));
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
		$results = Rsvp::getRsvpByRsvpProfileId($this->PDO(), $rsvp->getRsvpProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\Rsvp", $results);

		//grab the result from the array and validate it
		$pdoRsvp = $results[0];
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
		$results = Rsvp::getRsvpByRsvpEventeId($this->PDO(), $rsvp->getRsvpEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\Rsvp", $results);

		//grab the result from the array and validate it
		$pdoRsvp = $results[0];
	}



}