<?php
namespace BackyardAstronomer\Astronomer;

use BackyardAstronomer\Astronomer{eventType};

require_once("autoload.php");
require_once(dirname(__DIR__,3) . "/vendor/autoload.php");

/**
 * Full PHPUnit test for the eventType class
 *
 * This is a complete PHPUnit test of the eventType class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see eventType
 * @author Dayn Augustson <daugustson@cnm.edu>
 **/
class EventTypeTest extends TestCase {

	/**
	 * content of the EventType
	 * @var string $VALID_EVENTTYPENAME
	 **/
	protected $VALID_EVENTTYPENAME = "PHPUnit test passing";

	/**
	 * content of the updated EventType
	 * @var string $VALID_EVENTTYPENAME2
	 **/
	protected $VALID_EVENTTYPENAME2 = "PHPUnit test still passing";

}

/**
 * test inserting a valid EventType and verify that it actual mySql data matches
 **/
public function testInsertValidEventType() : void {
	//count the number of rows and save them for later
	$numRows = $this->getConnection()->getRowCount("eventType");

	//create a new EventType and insert into mySql
	$eventTypeId = f567fe08-d90f-4c41-ad4f-52483f89aae0();
	$eventType = new EventType($eventTypeId, $this->VALID_EVENTTYPENAME);
	$eventType->insert($this->getPDO());

}