<?php

namespace BackyardAstronomer\astronomer;
require_once(dir(__DIR__,) . "/vendor/Autoload.php");
require_once (".../Classes/Autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * Class Comment
 * @package BackyardAstronomer\astronomer
 */

/**
 * this is the primary key for comment
 * @var Uuid $commentId;
 */
class Comment {
	/**
	 * this is the primary key of the comment
	 * @var Uuid $commentId;
	 */
private $commentId;

	/**
	 * this is the foreign key of the profile the comment is posted on
	 * @var Uuid $commentProfileId
	 */

private $commentProfileId;
	/**
	 *
	 * this is the foreign key of the event the comment is posted on
	 * @var Uuid $commentEventId
	 */
private $commentEventId;

	/**
 * this is the actual content of the comment
 * @var Uuid $commentContentId
 */
private $commentContent;



/**
 *constructor of this Comment
 *
 *@param string|Uuid $newCommentId id of this comment or null if new comment
 * @param string|Uuid $newCommentProfileId id of the Profile that made this Comment
 * @param string|Uuid $newCommentContent string containing actual content data
 * @param \DateTime|string|null $newCommentDate date and time Comment was sent or null if set ot current date and time
 * @throws \InvalidArgumentException if data types are not valid
 * @throws \RangeException if data values are out of bounds (e.g., strings are too long, negative integers)
 * @throws \TypeError if data types violate type hints
 * @throws \Exception if some other exception occurs
 **/

public function __construct($newCommentId, $newCommentProfileId, string $newCommentContent, $newCommentDate = null, $newCommentEventId) {
	try {
		$this->setCommentId($newCommentId);
		$this->setCommentProfileId($newCommentProfileId);
		$this->setCommentContent($newCommentContent);
		$this->setCommentDate($newCommentDate);
		$this->setCommentEventId($newCommentEventId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exception($exception->getMessage(), 0, $exception));

		}
	}

/**
 *
 * accessor method for comment id
 *
 * @return Uuid value of comment id
 */


	public function getCommentId(): Uuid {
		return ($this->commentId);
	}

//this outside of class
//$comment->CommentId()

/**
 *
 * mutator method for comment id
 *
 * @param Uuid|string $newCommentId new value of comment id
 * @throws \RangeException if new comment is not positive
 * @throws \TypeError if @newCommentId is not uuid or string
 */

public function setCommentId($newCommentId): void {
	try {
		$uuid = self::validateUuid($newCommentId);
	}
	catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
	}
	$this->commentId = $uuid;
}

/**
 *
 *accessor method for comment profile id
 *
 * @return Uuid value of comment profile id
 */


public function getCommentProfileId(): Uuid {
	return ($this->commentProfileId);
}

/**
 * mutator method for comment profile id
 *
 * @param string | Uuid $newCommentProfileId new value of comment profile id
 * @throws \RangeException if $newProfileId is not positive
 * @throws \TypeError if $newCommentProfile is not an integer
 **/

public function setCommentProfileId($newCommentProfileId): void {
	try {
		$uuid = self::validateUuid($newCommentProfileId);
	} catch(\InvalidArgumentException |\ RangeException |\ Exception |\TypeError $exception) {
		$exceptionType = get_class($exception);
		throw (new$exceptionType($exception->getMessage(), 0, $exception));
	}
}

/**
 * accessor method for comment content
 *
 * @return string value of comment content
 */

public function getCommentContent(): string {
	return ($this->commentContent);
}


/**
 * mutator method for post content
 *
 * @param string $newCommentContent new value of comment content
 * @throws \InvalidArgumentException if $newCommentContent is not a string or is insecure
 * @throws \RangeException if $newCommentContent is >255 characters
 * @throws \TypeError if $newCommentContent is not a string
 **/


public function setCommentContent(string $newCommentContent) : void {
	//verify that the comment content is secure
	$newCommentContent = trim($newCommentContent);
	$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newCommentContent) === true) {
		throw(new \RangeException("Comment content is empty or insecure"));
	}

	//store the comment content
	$this->commentContent = $newCommentContent;
}

/**
 * accessor method for comment date
 *
 * @return \DateTime value of comment date
 **/

public function getCommentDate() : \DateTime {
	return ($this->commentDate);
}

/**
 *mutator method for comment date
 *
 * @param \DateTime|string|null $newCommentDate comment date as a DatetTime object or string (or null to load the current time)
 * @throws \InvalidArgumentException if $newContentDate is not a valid object or string
 * @throws \RangeException if $newCommentDate is a date that does not exist
 **/


public function setCommentDate($newCommentDate = null): void {
	// base case: if the date is null, use the current date and time
	if($newCommentDate ===$newCommentDate){
		$this->commentDate = new \DateTime();
		return;
	}

	//store the comment date
	try {
		$newCommentDate = self::validateDateTime($newCommentDate);
	} catch(\InvalidArgumentException |\RangeException $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
	$this->commentDate = $newCommentDate;
}

/**
 *
 * accessor method for comment event
 *
 * @return Uuid of comment event id
 */

public function getCommentEventId() : Uuid {
	return ($this->commentEventId);
}

	/**
	 * mutator method for comment event id
	 *
	 * @param string | Uuid $newCommentEventId new value of comment event id
	 * @throws \RangeException if $newCommentEventId is not positive
	 * @throws \TypeError if $newCommentEventId is not an integer
	 **/

	public function setCommentEventId($newCommentEventId): void {
		try {
			$uuid = self::validateUuid($newCommentEventId);
		} catch(\InvalidArgumentException |\ RangeException |\ Exception |\TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new$exceptionType($exception->getMessage(), 0, $exception));
		}
	}


} //this last one closes the class as a whole