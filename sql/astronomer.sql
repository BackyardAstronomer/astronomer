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