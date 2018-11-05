<?php

namespace BackyardAstronomer\astronomer;
require_once(dir(__DIR__,) . "/vendor/Autoload.php");
require_once (".../Classes/Autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * Class Comment
 * @package BackyardAstronomer\astronomer
 */
class Comment {
private $commentId;
/**
 * this is the primary key for comment
 * @var Uuid $commentId;
 */

private $commentProfileId;
/**
 * this is the foreign key of the profile that made the comment
 * @var Uuid $commentProfileId;
 */

private $commentEventId;
/**
 * this is the foreign key of the event the comment is posted on
 * @var Uuid $commentEventId
 */

private $commentContent;
/**
 * this is the actual content of the comment
 * @var Uuid $commentContentId
 */
}