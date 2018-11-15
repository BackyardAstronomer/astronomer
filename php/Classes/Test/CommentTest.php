<?php
namespace BackyardAstronomer\Astronomer;
use BackyardAstronomer\astronomer\ {Event, Profile, Comment};
require_once (dirname(__DIR__) . "/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");

use phpDocumentor\Reflection\Types\Void_;
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

protected $VALID_ACTIVATION;




public final function setUp(): void {
//run the default setUp() method first
	parent::setUp();

	//create a salt and hash for the mocked profile
	$password = "abc123";
	$this->VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
	$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

	//create and insert the mocked profile
	$this->profile = new Profile(generateUuidV4(), generateUuidV4(), "This is a sample bio", sampleemail@test . com, null, null, "Jane Smith");
	$this->profile->insert($this->getPDO());

	//create and insert mocked comment
	$this->comment - new Comment(generateUuidV4(), generateUuidV4(), generateUuidV4(), "Sample Comment Content", new \DateTime());


	//calculate the date(just use the time the unit test was set up)
	$this->VALID_COMMENTDATE = new \DateTime();

}
	/**
	 *
	 * test inserting a valid comment and verify the actual mySQL data matches
	 */

	public function testInsertValidComment() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new comment and insert into MySQL
		$comment = new Comment($this->profile->getProfileId(), $this->comment->getCommentId(), $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Like::getCommentByCommentEventIdAndCommentProfileId($this->getPDO(), $this->profile->getProfileId(), $this->comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getCommentEventId(), $this->comment->getCommentId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getCommentDate()->getTimeStamp(), $this->VALID_COMMENTDATE->getTimestamp());
	}


	/**
	 * test creating a comment and then deleting it
	 **/
	public function testDeleteValidComment() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$comment = new Like($this->profile->getProfileId(), $this->comment->getCommentId(), $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());
		// delete the Comment from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$comment->delete($this->getPDO());
		// grab the data from mySQL and enforce the Comment does not exist
		$pdoComment = Comment::getCommentByEventIdAndCommentProfileId($this->getPDO(), $this->profile->getProfileId(), $this->event->getEventId());
		$this->assertNull($pdoComment);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));
	}


	/**
	 * test inserting a Comment and regrabbing it from mySQL
	 **/
	public function testGetValidCommentByEventIdAndProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$comment = new Comment($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoLike = Comment::getCommentByCommentEventIdAndCommentProfileId($this->getPDO(), $this->profile->getProfileId(), $this->event->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENTDATE->getTimestamp());
	}

	/**
	 * test grabbing a Comment that does not exist
	 **/
	public function testGetInvalidCommentByEventIdAndProfileId() {
		// grab a event id and profile id that exceeds the maximum allowable event id and profile id
		$comment = Comment::getCommentByCommentEventIdAndCommentProfileId($this->getPDO(), generateUuidV4(), generateUuidV4());
		$this->assertNull($comment);
	}



	/**
	 * test grabbing a Comment by event id
	 **/
	public function testGetValidCommentByEventId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$like = new Like($this->profile->getProfileId(), $this->tweet->getTweetId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentEventId($this->getPDO(), $this->event->getEventId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\BackyardAstronomers\\Comment", $results);
		// grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENTDATE->getTimestamp());
	}


	/**
	 * test grabbing a Comment by a event id that does not exist
	 **/
	public function testGetInvalidCommentByEventId() : void {
		// grab a event id that exceeds the maximum allowable event id
		$comment = Comment::getCommentByCommentEventId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}
	/**
	 * test grabbing a Comment by profile id
	 **/
	public function testGetValidCommentByProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$comment = new Comment($this->profile->getProfileId(), $this->event->getEventId(), $this->VALID_COMMENTDATE);
		$comment->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\BackyardAstronomers\\Comment", $results);
		// grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentEventId(), $this->event->getEventId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDate()->getTimeStamp(), $this->VALID_COMMENTDATE->getTimestamp());
	}

	/**
	 * test grabbing a Comment by a profile id that does not exist
	 **/
	public function testGetInvalidCommentByProfileId() : void {
		// grab a comment id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}





}//this one closes the test