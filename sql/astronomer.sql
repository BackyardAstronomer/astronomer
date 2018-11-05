-- The statement below sets the collation of the database to utf8
ALTER DATABASE - CHARACTER SET utf8 COLLATE utf8_unicode_ci;

--dropping tables to run again with fresh inputs, eliminates run errors
DROP TABLE IF EXISTS rsvp
DROP TABLE IF EXISTS eventType
DROP TABLE IF EXISTS comment
DROP TABLE IF EXISTS event
DROP TABLE IF EXISTS profile

-- This table creates the user profiles and allows initial interaction with the site.
CREATE TABLE profile (
	profileId BINARY(16) NOT NULL, -- holds the uuid stored as binary
	profileEmail VARCHAR(32) NOT NULL, -- holds the users email
	profileBio VARCHAR(255), -- short paragraph describing the user
	profileName VARCHAR(32) NOT NULL, -- users display name within the site
	profileImage VARBINARY(64), -- allows for the use of a small image on the users profile
	profileActivationToken VARCHAR(128) NOT NULL, -- used to authenticate the user's email
	profileHash BINARY(64) NOT NULL, -- hash of the users password AE6 encryption
-- The UNIQUE statements ensure user creation is not overlapped
	UNIQUE (profileId), -- every user need a unique primary key
	UNIQUE (profileEmail), -- the same email cannot be used twice
	UNIQUE (profileActivationToken), -- activation tokens must be used only once
--This is the declaration of a primary key
	PRIMARY KEY(profileId) -- identifies unique profiles
);

--This table establishes the variables for events and event creation.
CREATE TABLE event (
eventId BINARY(16) NOT NULL, --creates an unique 32 digit id for each event that is created, cannot be blank
eventProfileId BINARY(16) NOT NULL, --creates a unique 32 digit id that associates profile to event, cannot be blank
eventEventTypeId BINARY(16) NOT NULL, --creates a unique 32 digit id that associates the event to an event type, not blank
eventContent VARCHAR(255) NOT NULL, --allows user to write a 255 character content blob detailing event, cannot be blank
eventTitle VARCHAR(32) NOT NULL, --allows user to write short 32 character title for their event, cannot be blank
eventCounter INT(6), --not positive if this is the correct var type for a counter..
eventStartDate DATETIME(6) NOT NULL, --mm/dd/yy format, cannot be blank
eventEndDate DATETIME(6) NOT NULL, --mm/dd/yy, cannot be blank
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
commentId BINARY(16) NOT NULL, --this will create a 32 digit id that associates comment, cannot be left blank
commentEventId BINARY(16) NOT NULL, --this links the comment with the event it is attached to, cannot be left blank
commentProfileId BINARY(16) NOT NULL, --this links the comment with the profile that created it, cannot be left blank
commentDate DATETIME(6) NOT NULL, --this marks when the comment was created, cannot be left blank
commentContent VARCHAR (255) NOT NULL, --this is the actual comment, cannot be left blank
INDEX (commentEventId), --this makes it easier to retrieve the comment
INDEX (commentProfileId), --this makes it easier to retrieve the comment
--
FOREIGN KEY (commentEventId) REFERENCES event(eventId), --this is the foreign key of the event the comment is attached to
FOREIGN KEY (commentProfileId) REFERENCES profile(profileId), --this is the foreign key of the profile that created the comment
--
PRIMARY KEY (commentId) --this is the primary key of our comment
);

--The following creates the Event Type table
CREATE TABLE eventType (
eventTypeId BINARY(16) NOT NULL,--creates an unique 32 digit id that associates event with type, cannot be blank
eventTypeName VARCHAR (32) NOT NULL,--allows user to write short 32 character title for their event, cannot be blank

primary key (eventTypeId)
);

--The following creates the RSVP table

CREATE TABLE rsvp (
rsvpProfileId BINARY(16) NOT NULL,--creates a unique 32 digit id that associates rsvp to profileID
rsvpEventID BINARY(16) NOT NULL,--creates a unique 32 digit id that associates rsvp to eventID
rsvpId BINARY(16) NOT NULL,--This is a composite of rsvpProfileId and rsvpEventId
rsvpEventCounter INT(16)--This integer that counts the number of people that RSVP

FOREIGN KEY (rsvpProfileId) REFERENCES profile(profileId),
FOREIGN KEY (rsvpEventId) REFERENCES event(eventId),
--COMPOSITE KEY (rsvpId) COMPOSITE (rsvpProfileId, rsvpEventId))

);
creates a unique 32 digit id that associates profile to event

