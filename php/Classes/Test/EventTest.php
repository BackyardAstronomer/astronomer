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
	 * @var string
	 */
	protected $VALID_EVENT_CONTENT = "PHPUnit test passing";

	/**
	 * content of the updated event
	 * @var string $VALID_EVENT_CONTENT2
	 */
	protected $VALID_EVENT_CONTENT2 = "PHPUnit test still passing";

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


}