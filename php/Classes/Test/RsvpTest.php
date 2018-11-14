<?php
namespace BackyardAstronomer\Astronomer;

//todo need to look over the path below
use BackyardAstronomer\Astronomer\php\classes\rsvp;

require_once("autoload.php");
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
class TsvpTest extends TestCase {
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
	 * Event that created the Rsvp; this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $event = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);


		// create and insert a Profile to own the test Rsvp
		$this->profile = new Profile(generateUuidV4(), "test@phpunit.de", "I am Groot", "Dilbert", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif",Null,  $this->VALID_PROFILE_HASH);
		$this->profile->insert($this->getPDO());

		// create and insert a Event to test Rsvp
		$this->event = new Event(generateUuidV4(), generateUuidV4(), generateUuidV4(), "blind star watch party","may the braille be with you","05/04/77", "05/25/77");





	}




}