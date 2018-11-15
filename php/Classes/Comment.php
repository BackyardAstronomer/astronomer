<?php

namespace BackyardAstronomer\Astronomer;
require_once(dir(__DIR__, 2) . "/vendor/autoload.php");
require_once("autoload.php");
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
	 * @var Uuid $commentId ;
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
	 *
	 * this is the date the comment was posted
	 * @var \DateTime $ $commentDate
	 */

	private $commentDate;


	/**
	 *constructor of this Comment
	 *
	 * @param string|Uuid $newCommentId id of this comment or null if new comment
	 * @param string|Uuid $newCommentProfileId id of the Profile that made this Comment
	 * @param string|Uuid $newCommentContent string containing actual content data
	 * @param string|Uuid $newCommentEventId string id of the event the comment is posted on
	 * @param \DateTime|string|null $newCommentDate date and time Comment was sent or null if set ot current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings are too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct($newCommentId, $newCommentEventId, $newCommentProfileId, string $newCommentContent, $newCommentDate = null) {
		try {
			$this->setCommentId($newCommentId);
			$this->setCommentProfileId($newCommentProfileId);
			$this->setCommentEventId($newCommentEventId);
			$this->setCommentContent($newCommentContent);
			$this->setCommentDate($newCommentDate);

		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exception = get_class($exception);
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
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		}
		$this->commentId = $uuid;
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


	public function setCommentContent(string $newCommentContent): void {
		//verify that the comment content is secure
		$newCommentContent = trim($newCommentContent);
		$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentContent) === true) {
			throw(new \RangeException("Comment content is empty or insecure"));
		}
		if(strlen ($newCommentContent)>255) {
			throw (new \RangeException("Comment is too many characters"));
		}
		//store the comment content
		$this->commentContent = $newCommentContent;
	}

	/**
	 *
	 * accessor method for comment event
	 *
	 * @return Uuid of comment event id
	 */

	public function getCommentEventId(): Uuid {
		return ($this->commentEventId);
	}

	/**
	 * mutator method for comment event id
	 *
	 * @param string | Uuid $newCommentEventId new value of comment event id
	 * @throws \RangeException if $newCommentEventId is not positive
	 * @throws \TypeError if $newCommentEventId is not an integer
	 * @throws \InvalidArgumentException if event is insecure
	 **/

	public function setCommentEventId($newCommentEventId): void {
		try {
			$uuid = self::validateUuid($newCommentEventId);
		} catch(\InvalidArgumentException |\ RangeException |\ Exception |\TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new$exceptionType($exception->getMessage(), 0, $exception));
		}
		//the following stores the new comment event id
		$this->commentEventId = $newCommentEventId;
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
	$this->commentProfileId = $newCommentProfileId;
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
	 * inserts this comment into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO comment(commentId, commentEventId, commentContent, commentDate, commentProfileId) VALUES(:commentId, :commentEventId, :commentContent, :commentDate, :commentProfileId)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["commentId" => $this ->commentId->getBytes(), "commentEventId" => $this->commentEventId, "commentContent" => $this->commentContent, "commentDate" => $this->commentDate, "commentProfileId" => $this->commentProfileId];
		$statement->execute($parameters);}


		/**
		 * updates this comment in mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
		public function update(\PDO $pdo) : void {

			// create query template
			$query = "UPDATE comment SET commentId = :commentId, commentEventId = :commentEventId, commentContent = :commentContent, commentDate = :commentDate, commentProfileId = :commentProfileId WHERE commentId = :commentId";
			$statement = $pdo->prepare($query);

			$parameters = ["commentId" => $this ->commentId->getBytes(), "commentEventId" => $this->commentEventId, "commentContent" => $this->commentContent, "commentDate" => $this->commentDate, "commentProfileId" => $this->commentProfileId];
			$statement->execute($parameters);
		}

	/**
	 * deletes this comment from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM comment WHERE commentId = :commentId, commentEventId = :commentEventId, commentContent = :commentContent, commentDate = :commentDate, commentProfileId = :commentProfileId WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["commentId" => $this ->commentId->getBytes(), "commentEventId" => $this->commentEventId, "commentContent" => $this->commentContent, "commentDate" => $this->commentDate, "commentProfileId" => $this->commentProfileId];
		$statement->execute($parameters);
	}



	/**
	 * gets the comment by comment Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $comment comment id to search for
	 * @return comment\null comment found or null if not found
	 * @throws \PDOException when mySQL related error occur
	 * @throws \TypeError when a variable is not the correct data type
	 *
	 */

	public static function getCommentByCommentId(\PDO $pdo, $comment) : ?Comment {
		//sanitize the commentId before searching

	try{
		$comment = self::validateUuid($comment);
} catch(\InvalidArgumentException |\RangeException |\Exception |\TypeError $exception) {
		throw(new \PDOException($exception->getMessage(), 0, $exception));
}

// create query template
		$query = "SELECT commentId, commentEventId, commentProfileId, commentContent, commentDate from comment WHERE commentId = :commentId";
	$statement = $pdo->prepare($query);

	//grab the comment from mySQL
	try {
		$comment = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$comment = new Comment($row["commentId"], $row["commentEventId"], $row["commentProfileId"], $row["commentContent"], $row["commentDate"]);
		}
	} catch(\Exception $exception) {
		//if the row couldn't be converted, rethrow it
		throw(new \PDOException($exception->getMessage(), 0, $exception));

	}
	return($comment);
	}




	public static function getCommentByCommentProfileId(\PDO $pdo, string  $commentProfileId) : \SPLFixedArray {
		try {
			$commentProfileId = self::validateUuid($commentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT commentId, commentProfileId, commentEventId, commentContent, commentDate FROM comment WHERE commentProfileId = :commentProfileId";
		$statement = $pdo->prepare($query);

		// bind the comment profile id to the place holder in the template
		$parameters = ["commentProfileId" => $commentProfileId->getBytes()];
		$statement->execute($parameters);

		// build an array of comments
		$comment = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new comment($row["commentId"], $row["commentProfileId"], $row["commentEventId"], $row["commentContent"], $row["commentDate"]);
				$comment[$comment->key()] = $comment;
				$comment->next();
			} catch(\Exception $exception) {

				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($comment);
	}



	public static function getCommentByCommentEventId(\PDO $pdo, string  $commentEventId) : \SPLFixedArray {
		try {

			$commentEventId = self::validateUuid($commentEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}


		// create query template
		$query = "SELECT commentId, commentProfileId, commentEventId, commentContent, commentDate FROM comment WHERE 							  commentProfileId = :commentProfileId";
		$statement = $pdo->prepare($query);

		// bind the comment event id to the place holder in the template
		$parameters = ["commentEventId" => $commentEventId->getBytes()];
		$statement->execute($parameters);

		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new comment($row["commentId"], $row["commentProfileId"], $row["commentEventId"], $row["commentContent"], 				$row["commentDate"]);
				$comment[$comment->key()] = $comment;
				$comment->next();
			} catch(\Exception $exception) {

				// if the row couldn't be converted, rethrow it
			  throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($comment);
	}

} //this last one closes the class as a whole