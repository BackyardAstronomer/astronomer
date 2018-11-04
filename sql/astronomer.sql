-- The statement below sets the collation of the database to utf8
ALTER DATABASE - CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--dropping tables to run again with fresh inputs, eliminates run errors
DROP TABLE IF EXISTS rsvp
DROP TABLE IF EXISTS eventType
DROP TABLE IF EXISTS comment
DROP TABLE IF EXISTS event
DROP TABLE IF EXISTS profile

--
CREATE TABLE profile (
	profileId BINARY(16) NOT NULL, --
	profileEmail VARCHAR(32) NOT NULL, --
	profileBio VARCHAR(255), --
	profileName VARCHAR(32) NOT NULL, --
	profileImage VARBINARY(64), --
	profileActivationToken VARCHAR(128) NOT NULL, --
	profileHash BINARY(64) NOT NULL, --
--
	UNIQUE (profileId), --
	UNIQUE (profileEmail), --
	UNIQUE (profileActivationToken), --
--
	PRIMARY KEY(profileId) --
);

--This table establishes the variables for events and event creation.
CREATE TABLE event (
eventId BINARY(16) NOT NULL, --creates an unique 32 digit id for each event that is created, cannot be blank
eventProfileId BINARY(16) NOT NULL, --creates a unique 32 digit id that associates profile to event, cannot be blank
eventEventTypeId BINARY(16) NOT NULL, --creates a unique 32 digit id that associates the event to an event type, not blank
eventContent VARCHAR(255) NOT NULL, --allows user to write a 255 character content blob detailing event, cannot be blank
eventTitle VARCHAR(32) NOT NULL, --allows user to write short 32 character title for their event, cannot be blank
eventCounter BINARY(16), --not positive if this is the correct var type for a counter..
eventStartDate DATETIME(6) NOT NULL, --mm/dd/yy format, cannot be blank
eventEndDate DATETIME(6) NOT NULL, --mm/dd/yy, cannot be blank
--the following makes sure duplicate data cannot exist.
UNIQUE(eventId),
UNIQUE(eventProfileId),
UNIQUE(eventEventTypeId),
--the following establishes an index before making a foreign key.
INDEX(eventProfileId),
INDEX(eventEventTypeId),
--the following creates the foreign key relationship
FOREIGN KEY(eventProfileId) REFERENCES profile(profileId),
FOREIGN KEY(eventEventTypeId) REFERENCES eventType(eventTypeId),
--the following establishes the primary key for this table
PRIMARY KEY(eventId)
);

--
CREATE TABLE comment (
commentId BINARY(16) NOT NULL, --
commentEventId BINARY(16) NOT NULL, --
commentProfileId BINARY(16) NOT NULL, --
commentDate DATETIME(6) NOT NULL, --
commentContent VARCHAR (255) NOT NULL, --
INDEX (commentEventId), --
INDEX (commentProfileId), --
--
FOREIGN KEY (commentEventId) REFERENCES event(eventId), --
FOREIGN KEY (commentProfileId) REFERENCES profile(profileId), --
--
PRIMARY KEY (commentId) --
);

--The following creates the Event Type table


--The following creates the RSVP table