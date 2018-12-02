<?php
namespace BackyardAstronomer\Astronomer;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");


use phpDocumentor\Reflection\Types\Array_;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

//use Ramsey\Uuid\UuidInterface;

/**
 *The following establishes the Profile class for the astronomer sql tables.
 */
class Profile implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * the following establishes the id for this profile; this is the primary
	 * key for the table
	 * @var Uuid $profileId
	 */
	private $profileId;
	/**
	 * the following establishes the variable for the required profile activation
	 * token each profile needs to create an account.
	 * @var string $profileActivationToken
	 */
	private $profileActivationToken;
	/**
	 * the following establishes the variable for the null-able text string
	 *bio the user can choose to make.
	 * @var string $profileBio
	 */
	private $profileBio;
	/**
	 * the following establishes the string for the user email.
	 * @var string $profileEmail
	 */
	private $profileEmail;
	/**
	 * the following establishes the variable for the required profile hash that
	 * stores each profile's password in an encrypted format
	 * @var string $profileHash
	 */
	private $profileHash;
	/**
	 * the following establishes the variable for the null-able image field.
	 * @var string $profileImage
	 */
	private $profileImage;
	/**
	 * The following establishes the variable for the required name field.
	 * @var string $profileName
	 */
	private $profileName;

	/**
	 * @param Uuid $newProfileId for a new user's new profile
	 * @param string $newProfileActivationToken string containing the required profile activation token
	 * needed to make account
	 * @param string $newProfileBio string containing a new bio the user can choose to create
	 * @param string $newProfileEmail string containing the user's new email
	 * @param string $newProfileHash string containing the user's encrypted password
	 * @param string $newProfileImage string containing a user's profile photo
	 * @param string $newProfileName string containing a user's new profile name
	 *
	 * and
	 *
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (strings too long)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

	public function __construct($newProfileId, string $newProfileActivationToken, string $newProfileBio, string $newProfileEmail,  string $newProfileHash,  string $newProfileImage, string $newProfileName) {

		try {

			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileBio($newProfileBio);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileHash($newProfileHash);
			$this->setProfileImage($newProfileImage);
			$this->setProfileName($newProfileName);

		} //the following determines what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * the following is the accessor method for the profile id
	 *
	 * it will @return Uuid value of profile id
	 */

	public function getProfileId(): Uuid {
		return ($this->profileId);
	}

	/**
	 * the following is the mutator method for the profile id
	 *
	 * @param Uuid $newProfileId inserts new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not a uuid
	 */

	public function setProfileId($newProfileId): void {
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
	 * @return string $profileEmail as string value
	 */

	public function getProfileEmail(): string {
		return ($this->profileEmail);
	}

	/**
	 * the following is the mutator method for the profile email content
	 *
	 * @param string $newProfileEmail inserts new profile email value
	 * @throws \InvalidArgumentException if email is empty or insecure
	 * @throws \RangeException if email is too long
	 */

	public function setProfileEmail(string $newProfileEmail): void {
		//the following verifies whether the email content is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("email input is empty or insecure"));
		}
		//the following verifies the email content will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("this email is too long"));
		}

		//the following stores the new email content
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 *the following is the accessor method for the $profileBio
	 *
	 * @return string $profileBio as string value
	 */

	public function getProfileBio(): string {
		return ($this->profileBio);
	}

	/**
	 * The following is the mutator method for the profile bio content
	 *
	 * @param string $newProfileBio inserts new profile bio information
	 * @throws \InvalidArgumentException if content is not string or insecure
	 * @throws \RangeException if content is > 240
	 * @throws \TypeError if content is not a string
	 */

	public function setProfileBio(string $newProfileBio): void {

		//the following verifies the bio content is secure
		$newProfileBio = trim($newProfileBio);
		$newProfileBio = filter_var($newProfileBio, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileBio) === true) {
			throw(new \InvalidArgumentException("Your bio is longer than 240 characters"));
		}

		//the following verifies the content will fit in the database. must be less than 240
		if(strlen($newProfileBio) > 240) {
			throw(new \RangeException("Your bio is too large, must be less than 240 characters"));
		}

//the following stores the tweet content
		$this->profileBio = $newProfileBio;
	}

	/**
	 * the following is the accessor method for the profile name field
	 *
	 * @return profile name as a string
	 */

	public function getProfileName(): string {
		return($this->profileName);
	}
/**
 * the following is the mutator method for the profile name field
 *
 * @param string $newProfileName inserts a new name for the profile
 * @throws \InvalidArgumentException if not string or insecure
 * @throws \RangeException if the name is too long for our database
 * @throws \TypeError if the name is not actually a text name.. *insert dolphin noise*.
 */

public function setProfileName(string $newProfileName) : void {
	//the following verifies the content is secure
	$newProfileName = trim($newProfileName);
	$newProfileName = filter_var($newProfileName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newProfileName) === true) {
		throw(new \InvalidArgumentException("This content is empty or insecure"));
	}

	//the following verifies the tweet content will fit in the database
	if(strlen($newProfileName) > 50) {
		throw(new \RangeException("This name is too long for our database."));
	}

	//the following stores the name content
	$this->profileName = $newProfileName;
}

/**
 * the following is the accessor method for the profile image variable
 *
 * @returns string value for profile image
 */

public function getProfileImage() : string {
	return($this->profileImage);
}

/**
 * the following is the mutator method for the profile image variable
 *
 * @param string $newProfileImage saves a new image for the profile
 * @throws \InvalidArgumentException if not string or insecure
 * @throws \RangeException if the name is too long for our database
 * @throws \TypeError if the name is not actually a string
 */

public function setProfileImage($newProfileImage) : void {
	//the following verifies the description content is secure
	$newProfileImage = trim($newProfileImage);
	$newProfileImage = filter_var($newProfileImage, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if (empty($newProfileImage) === true) {
		throw(new \InvalidArgumentException("profile image is not valid or insecure"));
	}
		//verify the image content will fit in the database

		if(strlen($newProfileImage) > 255) { //review this
		throw(new \RangeException("this image is too big"));
		}
		//stores the new photo content
		$this->profileImage = $newProfileImage;
	}

/**
 * the following is the accessor method for the profile activation token
 *
 * @returns profile activation token as a string
 */

public function getProfileActivationToken() : string {
	return ($this->profileActivationToken);
}

/**
 * the following is the mutator method for the profile activation token
 *
 * @param string $newProfileActivationToken
 * @throws \InvalidArgumentException if not string or insecure
 * @throws \RangeException if the name is too long for our databases
 * @throws \TypeError if the name is not a string
 */

public function setProfileActivationToken($newProfileActivationToken) : void {
	//the following verifies the description is secure

	$newProfileActivationToken = trim($newProfileActivationToken);
	$newProfileActivationToken = filter_var($newProfileActivationToken, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if (empty($newProfileActivationToken) === true) {
		throw(new \InvalidArgumentException("This content is insecure or empty."));
	}
	if(ctype_xdigit ($newProfileActivationToken) !== true){
		throw(new \InvalidArgumentException("This content is not hexadecimal."));
	}
	//stores the new activation token... review this
	$this->profileActivationToken = $newProfileActivationToken;
}

/**
 * The following is the accessor method for the profile hash
 *
 * @return string value of hash
 */

public function getProfileHash() : string {
	return ($this->profileHash);
}

/**
 * the following is the mutator method for the profile hash
 *
 * @param string $newProfileHash
 * @throws \InvalidArgumentException if not string or insecure
 * @throws \RangeException if the name is too long for our databases
 * @throws \TypeError if the input is not a string
 */

public function setProfileHash($newProfileHash) : void {
	//the following enforces that the hash is properly formatted

	$newProfileHash = trim($newProfileHash);
	if(empty($newProfileHash) === true) {
		throw(new \InvalidArgumentException("profile password hash empty or insecure"));
	}

	//the following enforces that the hash is really an Argon hash
	$profileHashInfo = password_get_info($newProfileHash);
	if($profileHashInfo["algoName"] !== "argon2i") {
		throw(new \InvalidArgumentException("profile hash is not a valid hash"));
	}

	//enforce tht the hash is exactly 97 characters.
	if(strlen($newProfileHash) !== 97) {
		throw(new \RangeException("profile hash must be 97 characters"));
	}

	//the following stores the hash
	$this->profileHash = $newProfileHash;
}





//
//PDO STARTS HERE
//


/**
 * inserts this Profile into mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/
public function insert(\PDO $pdo) : void {

	// create query template
	$query = "INSERT INTO profile(profileActivationToken, profileBio, profileEmail, profileHash, profileId,  profileImage, profileName) VALUES(:profileActivationToken, :profileBio, :profileEmail, :profileHash, :profileId, :profileImage, :profileName)";
	$statement = $pdo->prepare($query);

	// bind the member variables to the place holders in the template
	$parameters = ["profileId" => $this ->profileId->getBytes(), "profileEmail" => $this->profileEmail, "profileBio" => $this->profileBio, "profileName" => $this->profileName, "profileImage" => $this->profileImage, "profileActivationToken" => $this->profileActivationToken, "profileHash" => $this->profileHash];
	$statement->execute($parameters);
}

	/**
	 * updates this Profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE profile SET profileActivationToken = :profileActivationToken, profileBio = :profileBio, profileEmail = :profileEmail, profileHash = :profileHash, profileId = :profileId, profileImage = :profileImage, profileName = :profileName  WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

	$parameters = ["profileId" => $this ->profileId->getBytes(), "profileEmail" => $this->profileEmail, "profileBio" => $this->profileBio, "profileName" => $this->profileName, "profileImage" => $this->profileImage, "profileActivationToken" => $this->profileActivationToken, "profileHash" => $this->profileHash];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["profileId" => $this ->profileId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Profile by profile name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileName profile name to search for
	 * @return \SplFixedArray Profile found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getProfileByProfileName(\PDO $pdo, $profileName) : \SplFixedArray {
		//sanitize and get rid of wild cards

		// create query template
		$query = "SELECT profileId, profileActivationToken, profileBio, profileEmail, profileHash, profileImage, profileName FROM profile WHERE profileName = :profileName";
		$statement = $pdo->prepare($query);

		// bind the profile name to the place holder in the template
		$parameters = ["profileName" => $profileName];
		$statement->execute($parameters);

		//bind the array of profiles
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode( \PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profileName = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileBio"], $row["profileEmail"], $row["profileHash"], $row["profileImage"], $row["profileName"]);
				$profiles[$profiles->key()] = $profileName;
				$profiles->next();
			} catch (\Exception $exception) {
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return($profiles);
	}

/**
 * gets the profile by profileId
 *
 * @param \PDO $pdo PDO connection object
 * @param \Ramsey\Uuid\ $profileId
 * @return profile found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 */
public static function getProfileByProfileId(\PDO $pdo, $profileId) : ?Profile {
	//sanitizing
	try {
		$profileId = self::validateUuid($profileId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}

	//create query template
	$query = "SELECT profileId, profileActivationToken, profileBio, profileEmail, profileHash,  profileImage, profileName FROM profile WHERE profileId = :profileId";
	$statement = $pdo->prepare($query);

	//bind the profile id to the place holder in the template
	$parameters = ["profileId" => $profileId->getBytes()];
	$statement->execute($parameters);

	//grab the profile from mySQL
	try {
		$profile = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileBio"], $row["profileEmail"], $row["profileHash"], $row["profileImage"], $row["profileName"]);
		}
	} catch(\Exception $exception) {

		//if the row couldn't be converted, rethrow it
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return($profile);
}

/**
 * gets the profile by profile email
 *
 * @param \PDO $pdo connection object
 * @param  $profileEmail
 * @return profile found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not correct data type
	 */

public static function getProfileByProfileEmail(\PDO $pdo, $profileEmail) : Profile {

//creates query template
	$query = "SELECT profileId, profileActivationToken, profileBio, profileEmail, profileHash, profileImage, profileName FROM profile WHERE profileEmail = :profileEmail";
$statement = $pdo->prepare($query);

//bind the profile email to the place holder in the template
	$parameters = ["profileEmail" => $profileEmail];
	$statement->execute($parameters);

	//grab the profile from mySQL
	try {
		$profileEmail = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$profileEmail = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileBio"], $row["profileEmail"], $row["profileHash"], $row["profileImage"], $row["profileName"]);
		}
	} catch(\Exception $exception) {
		//if the row couldn't be converted, rethrow it
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return($profileEmail);
}


/**
 * gets the profile by profile activation token
 *
 * @param \PDO $pdo connection object
 * @param string $profileActivationToken to search for
 * @return profile found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables aren't the correct data type
 */
public static function getProfileByProfileActivationToken(\PDO $pdo, string $profileActivationToken) : Profile {

	//creates query template
	$query = "SELECT profileId, profileActivationToken, profileBio, profileEmail, profileHash, profileImage, profileName FROM profile WHERE profileActivationToken = :profileActivationToken";
	$statement = $pdo->prepare($query);

	//bind the profile activation token to the place holder in the template
	$parameters = ["profileActivationToken" => $profileActivationToken];
	$statement->execute($parameters);

	//grab the profile from mySQL
	try {
		$profileActivationToken = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$profileActivationToken = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileBio"], $row["profileEmail"], $row["profileHash"], $row["profileImage"], $row["profileName"]);
		}
	} catch(\Exception $exception) {
		//if the row could not be converted, rethrow that
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return($profileActivationToken);
}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		return($fields);
	}
} //class closing bracket