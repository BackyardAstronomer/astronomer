<?php
namespace BackyardAstronomer\Astronomer;
require_once("Autoload.php");
require_once(dir(__DIR_, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *
 */
class Profile {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * the following establishes the id for this profile; this is the primary
	 * key for the table
	 * @var Uuid $profileId
	 */
	private $profileId;
	/**
	 * the following establishes the string for the user email.
	 * @var string $profileEmail
	 */
	private $profileEmail;
	/**
	 * the following establishes the variable for the null-able text string
	 *bio the user can choose to make.
	 * @var string $profileBio
	 */
	private $profileBio;
	/**
	 * The following establishes the variable for the required name field.
	 * @var string $profileName
	 */
	private $profileName;
	/**
	 * the following establishes the variable for the null-able image field.
	 * @var string $profileImage
	 */
	private $profileImage;
	/**
	 * the following establishes the variable for the required profile activation
	 * token each profile needs to create an account.
	 * @var string $profileActivationToken
	 */
	private $profileActivationToken;
	/**
	 * the following establishes the variable for the required profile hash that
	 * stores each profile's password in an encrypted format
	 * @var string $profileHash
	 */
	private $profileHash;

	/**
	 * @param Uuid $newProfileId for a new user's new profile
	 * @param string $newProfileEmail string containing the user's new email
	 * @param string $newProfileBio string containing a new bio the user can choose to create
	 * @param string $newProfileName string containing a user's new profile name
	 * @param string $newProfileImage string containing a user's profile photo
	 * @param string $newProfileActivationToken string containing the required profile activation token
	 * needed to make account
	 * @param string $newProfileHash string containing the user's encrypted password
	 *
	 * and
	 *
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (strings too long)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

/**
 * The following creates the constructor function for this class
 */

public function __construct($newProfileId, $newProfileEmail, $newProfileBio, $newProfileName, $newProfileImage,
$newProfileActivationToken, $newProfileHash = null) {

	try {
		$this->profileId($newProfileId);
		$this->profileEmail($newProfileEmail);
		$this->profileBio($newProfileBio);
		$this->profileName($newProfileName);
		$this->profileImage($newProfileImage);
		$this->profileActivationToken($newProfileActivationToken);
		$this->profileHash($newProfileHash);
	}
	//the following determines what exception type was thrown
	catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
}

	/**
	 * the following is the accessor method for the profile id
	 *
	 * it will @return Uuid value of tweet id
	 */
	public function getProfileId() : Uuid {
		return($this->profileId);
	}

	/**
	 * mutator method for the profile id
	 *
	 * @param Uuid|string $newProfileId inserts new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not a uuid
	 */

	public function setProfileId( $newProfileId) : void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		//the following converts and stores the new profile id
		$this->profileId = $uuid;
	}

	/**
	 * the following is the accessor method for the profile email
	 *
	 * @return email as string value
	 */

public function getProfileEmail() : string {
	return($this->profileEmail);
}
/**
 * the following is the mutator method for the profile email content
 *
 * @param string $newProfileEmail inserts new profile email value
 * @throws \InvalidArgumentException if email is empty or insecure
 * @throws \RangeException if email is too long
 */

public function setProfileEmail(string $newProfileEmail) : void {

	//the following verifies whether the email content is secure
	$newProfileEmail = trim($newProfileEmail);
	$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newProfileEmail) === true) {
		throw(new \InvalidArgumentException("email input is empty or insecure"))
	}
	//the following verifies the email content will fit in the database
	if(strlen($newProfileEmail) > 50) {
		throw(new \RangeException("this email is too long"));
	}

	//the following stores the new email content
	$this->profileEmail = $newProfileEmail;
}

/**
 *the following is the accessor method for the profile bio
 *
 * @return bio as string value
 */

public function getProfileBio() : string {
	return($this->profileBio);
}
/**
 * The following is the mutator method for the profile bio content
 *
 * @param string $newProfileBio inserts new profile bio information
 * @throws \InvalidArgumentException if content is not string or insecure
 * @throws \RangeException if content is > 240
 * @throws \TypeError if content is not a string
 */

public function setProfileBio(string $newProfileBio) : void {

	//the following verifies the bio content is secure
	$newProfileBio = trim($newProfileBio);
	$newProfileBio = filter_var($newProfileBio, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newProfileBio) === true) {
		throw(new \InvalidArgumentException("Your bio is longer than 240 characters"));
	}

	//the following verifies the content will fit in the database. must be less than 240
	if(strlen($newTweetContent) > 240) {
		throw(new \RangeException("Your bio is too large, must be less than 240 characters"));
	}

//the following stores the tweet content
	$this->profileBio = $newProfileBio;
}



} //class closing bracket