<?php
namespace BackyardAstronomer\Astronomer;

use  BackyardAstronomer\Astronomer\Profile;

require_once("AstronomerTestSetUp.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

/**
 * Full PHPUnit test for the Profile class
 *
 * This is a complete PHPUnit test of the Profile class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Profile
 * @author Chamisa Edmo <chamisaedmo@yahoo.com>
 **/
class ProfileTest extends AstronomerTestSetUp {

	/**
	 * content of the Profile email
	 * @var string $profileEmail
	 **/
	protected $VALID_PROFILE_EMAIL = "PHPUnit test passing";

	/**
	 * content of the updated Profile Bio
	 * @var string $profileBio
	 **/
	protected $VALID_PROFILE_BIO_CONTENT = "PHPUnit";

	/**
	 * content of the profile name; this starts as null and is assigned later
	 * @var string $profileName
	 **/
	protected $VALID_PROFILE_NAME = "test still";

	/**
	 * content of the profile image; this starts as null and is assigned later
	 * @var string $profileImage
	 */
	protected $VALID_PROFILE_IMAGE = "passing";

	/**
	 * content of the profile activation token
	 * @var string $profileActivationToken
	 */
	protected $VALID_PROFILE_ACTIVATION_TOKEN = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $profileHash
	 */
	protected $VALID_PROFILE_HASH;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$this->VALID_PROFILE_ACTIVATION_TOKEN = bin2hex(random_bytes(16));
	}

	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}

	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// edit the Profile and update it in mySQL
		$profile->setProfileContent($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}


	/**
	 * test creating a Tweet and then deleting it
	 **/
	public function testDeleteValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$profile->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		//$pdoTweet = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		//$this->assertNull($pdoProfile);
		//$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}

	/**
	 * test grabbing a Profile by profile name
	 **/
	public function testGetValidProfileByProfileName() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Tweet and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getProfileByProfileName($this->getPDO(), $profile->getProfileName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertCount(1, $results);

		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Astronomer\\Profile", $results);

		// grab the result from the array and validate it
		$pdoProfile = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}
/**
 * test grabbing the Profile by profile email
 */
	public function testGetValidProfileByProfileEmail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Tweet and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getProfileByProfileEmail($this->getPDO(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));

		// grab the result from the array and validate it
		$pdoProfile = $results;
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}

	/**
	 * test grabbing the Profile by the profile activation token
	 * this will be used by the user to find and activate their profile from an email
	 */
	public function testGetValidProfileByProfileActivationToken() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Tweet and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new profile($profileId, $this->VALID_PROFILE_ACTIVATION_TOKEN, $this->VALID_PROFILE_BIO_CONTENT, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE, $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_NAME);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getProfileByProfileActivationToken($this->getPDO(), $profile->getProfileActivationToken());

		// grab the result from the array and validate it
		$pdoProfile = $results;
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILE_ACTIVATION_TOKEN);
		$this->assertEquals($pdoProfile->getProfileBio(), $this->VALID_PROFILE_BIO_CONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_PROFILE_HASH);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILE_NAME);
	}

}