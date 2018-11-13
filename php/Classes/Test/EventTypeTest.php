<?php
namespace BackyardAstronomer\Astronomer;
//todo need to look over the path below
use BackyardAstronomer\Astronomer\{EventType};

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

	//grab the data from mySQl and enforce the fields match out expectations
	$pdoEventType = EventType::getEventTypeByEventTypeId($this->getPDO(), $eventType->getEventTypeId());
	$this->assertEquals($pdoEventType->getEventTypeId(), $eventTypeId);
	$this->assertEquals($numRows + 1,$this->getConnection()->getRowCount("eventType"));
	$this->assertEquals($pdoEventType->getEventTypeId());
	$this->assertEquals($pdoEventType->getEventTypeName(),$this->VALID_EVENTTYPENAME2);

}

/**
 * test creating a eventType and then Deleting it
 */
public function testDeleteValidEventType() : void {
	//count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("eventType");

	//create a new EventType and insert into mySql
	$eventTypeId = f567fe08-d90f-4c41-ad4f-52483f89aae0();
	$eventType = new EventType($eventTypeId, $this->VALID_EVENTTYPENAME);
	$eventType->insert($this->getPDO());

	//delete the EventType from mySQL
	 $this->assertEquals($numRows + 1,$this->getConnection()->getRowCount("eventType"));
	 $eventType->delete($this->getPDO());

	 //grab the date from mySQL and make sure the EventType has be deleted
	$pdoEventType = EventType::getEventTypeByEventTypeId($this->getPDO(), $eventType->getEventTypeId());
	$this->assertNull($pdoEventType);
	$this->assertEquals($numRows, $this->getConnection()->getRowCount("eventType"));
}



}

