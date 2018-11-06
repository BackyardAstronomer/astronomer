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





} //class closing bracket