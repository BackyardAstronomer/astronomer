<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
//below needs to be changed to a file that we actually have, but I don't know what to change it to.
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use BackyardAstronomer\Astronomer{
	Profile;
};

/*
 * API for the astronomer
 *
 * @author Chamisa Edmo
 * @version 1.0
 */
