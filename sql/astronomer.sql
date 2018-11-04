ALTER DATABASE - CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS rsvp
DROP TABLE IF EXISTS eventType
DROP TABLE IF EXISTS comment
DROP TABLE IF EXISTS event
DROP TABLE IF EXISTS profile

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileEmail VARCHAR(32) NOT NULL,
	profileBio VARCHAR(255),
	profileName VARCHAR(32) NOT NULL,
	profileImage VARBINARY(64),
	profileActivationToken VARCHAR(128) NOT NULL,
	profileHash BINARY(64) NOT NULL,
	UNIQUE (profileId),
	UNIQUE (profileEmail),
	UNIQUE (profileActivationToken),
	PRIMARY KEY(profileId)
);

CREATE TABLE event (
eventId BINARY(16) NOT NULL, --creates an unique 32 digit id for each event that is created, cannot be blank
eventProfileId BINARY(16) NOT NULL, --
eventEventTypeId BINARY(16) NOT NULL,
eventContent VARCHAR(255) NOT NULL,
eventTitle VARCHAR(32) NOT NULL,
eventCounter
eventStartDate DATETIME(6) NOT NULL,
eventEndDate DATETIME(6) NOT NULL
);
/**
This will be the third table. Chamisa, you'll want to insert the "event" table above this one
 */
CREATE TABLE comment (
commentId BINARY(16) NOT NULL,
commentEventId BINARY(16) NOT NULL,
commentProfileId BINARY(16) NOT NULL,
commentDate DATETIME(6) NOT NULL,
commentContent VARCHAR (255) NOT NULL,
INDEX (commentEventId),
INDEX (commentProfileId),
FOREIGN KEY (commentEventId) REFERENCES event(eventId),
FOREIGN KEY (commentProfileId) REFERENCES profile(profileId),
PRIMARY KEY (commentId)
);