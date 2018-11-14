<?php
namespace BackyardAstronomer\Astronomer;
use BackyardAstronomer\astronomer\ {Event, Profile, Comment};
require_once (dirname(__DIR__) . "/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");
/**
 * Full PHPUnit test for Comment Class
 *
 * This is a complete PHPUnit test for the Comment class. It is complete because *all* MySQL/PDO enabled methods are tested for both valid and invalid inputs.
 *
 * @see BackyardAstronomer\Astronomer\Comment
 * @author Stephen Pelot <spelot@cnm,.edu>
 */

class CommentTest extends AstronomerTestSetUp {
	/**
	 * Profile that created the Comment; this is for foreign key relations
	 * @var Profile $profile
	 */
	protected $profile = null;
	/**
	 * event that the comment is on for foreign key relations
	 * @var Event $event
	 *
	 */
	protected $event;


	/**
	 * valid  hash to create
	 * @var  $VALID_HASH
	 *
	 */
	protected $VALID_HASH;

	/**
	 *timestamp of the comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_COMMENTDATE
	 */
	protected $VALID_COMMENTDATE;

/**
 * valid activationToken to create the comment object to own the test
 * @var string $VALID_ACTIVATION
 */

protected $VALID_ACTIVATION




/**
 * create dependant objects before running each test
 */

	public final function setUp(): void {
		//run the default setUp() first
		parent::setUp();
		$password = "abc123";
		$this->VALID_HASH = password_hash($password, PASSWORD_ARGON21, ["time_cost" => 384]);

		//create and insert profile to own the comment
		$this->profile = new Profile(generateUuidV4(),
			'sampleemail@test.com', "sample bio fluff", "Test Astronomer", null, $this->VALID_PROFILE_HASH);
			$this->profile->insert($this->getPDO());

		//calculate the start date (date unit test was set up)
	$this->VALID_COMMENT = new \DateTime();
}


}//this one closes the test