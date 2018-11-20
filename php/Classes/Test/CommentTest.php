<?php
namespace BackyardAstronomer\Astronomer;
use BackyardAstronomer\Astronomer\Comment;
require_once ("AstronomerTestSetUp.php");
require_once (dirname(__DIR__) . "/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");
use Ramsey\Uuid\Uuid;
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
	protected $event = null;

	/**
	 * valid hash to create
	 * @var  $VALID_HASH
	 *
	 */
	protected $VALID_HASH;


	/**
	 * valid activation token
	 * @var string $profileActivationToken
	 */

	protected $VALID_ACTIVATION_TOKEN = null;

	/**
	 * content of the comment
	 * @var string $VALID_COMMENT_CONTENT
	 */

	protected $VALID_COMMENT_CONTENT = "nice";

	/**
	 * content of the comment
	 * @var string $VALID_COMMENT_CONTENT2
	 */

	protected $VALID_COMMENT_CONTENT2 = "nice again";


	/**
	 *timestamp of the comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_START_DATE
	 */
	protected $VALID_START_DATE;

	/**
	 *timestamp of the comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_END_DATE
	 */
	protected $VALID_END_DATE;


	/**
	 * valid comment date
	 * @var $VALID_COMMENT_DATE
	 */

	protected $VALID_COMMENT_DATE;


	/**
	 *create dependant objects before running each test
	 */
public final function setUp(): void {
//run the default setUp() method first
	parent::setUp();

	//create a salt and hash for the mocked profile
	$password = "abc123";
	$this->VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost"=>384]);
	$this->VALID_ACTIVATION_TOKEN = bin2hex(random_bytes(16));

	//create and insert the mocked profile
	$profileId = generateUuidV4();
	$this->profile = new Profile($profileId, $this->VALID_ACTIVATION_TOKEN, "bio here", "test@email.com", $this->VALID_HASH, "image", "Jalk J");
	$this->profile->insert($this->getPDO());


	//create and insert event type
	$eventTypeId = generateUuidV4();
	$this->eventType = new EventType($eventTypeId, "Star Party");
	$this->eventType->insert($this->getPDO());

	//create and insert event
	$eventId = generateUuidV4();
	$this->event = new Event($eventId, $this->eventType->getEventTypeId(), $this->profile->getProfileId(), "Star Party", "Sample Content", new \DateTime(), new \DateTime() );
	$this->event->insert($this->getPDO());




	//calculate the date(just use the time the unit test was set up)
	$this->VALID_START_DATE = new \DateTime();

}
	/**
	 *
	 * test inserting a valid comment and verify the actual mySQL data matches
	 */

	public function testInsertValidComment() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new comment and insert into MySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->event->getEventId(), $this->profile->getProfileId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_DATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
	$pdoComment = Comment::getCommentByCommentId($this->getPDO(),$comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
		//format the date two seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENT_DATE->getTimestamp());

	}


	/**
	 *test inserting, editing, then updating a comment
	 */


	public function testUpdateValidComment() :void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new comment and insert into MySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->event->getEventId(), $this->profile->getProfileId(), $this->VALID_COMMENT_CONTENT, $this->$this->VALID_COMMENT_DATE);
		$comment->insert($this->getPDO());

		//edit the comment, and then update it
		$comment->setCommentContent($this->VALID_COMMENT_CONTENT2);
		$comment->update($this->getPDO());

		//grab the data from MySQL and force the data to match the expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentContent());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT2);

		//format the dates 2 seconds since the beginning to avoid rounding error
		$this->assertEquals($pdoComment->getCommentDate()->getTimestamp(), $this->VALID_COMMENT_DATE->getTimestamp());
	}
	/**
	 * test creating a comment and then deleting it
	 **/
	public function testDeleteValidComment() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->event->getEventId(),$this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_DATE);
		$comment->insert($this->getPDO());

		// delete the Comment from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$comment->delete($this->getPDO());

		// grab the data from mySQL and enforce the Comment does not exist
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment);
		$this->assertNull($pdoComment);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));
	}


	/**
	 * test grabbing a Comment by event id
	 **/
	public function testGetValidCommentByEventId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_DATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentEventId($this->getPDO(), $this->event->getEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("BackyardAstronomer\\Astronomer\\Comment", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		//format the date two seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENT_DATE->getTimestamp());
	}


	/**
	 * test grabbing a Comment by profile id
	 **/
	public function testGetValidCommentByProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$comment = new Comment($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_COMMENT_DATE, $this->VALID_COMMENT_CONTENT);
		$comment->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("BackyardAstronomers\\Astronomer\\Comment", $results);
		// grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENT_DATE->getTimestamp());
	}






}//this one closes the test