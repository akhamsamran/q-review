<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Faqt\{
	Profile, Board
};
//use PubNub\PNConfiguration;
//use PubNub\PubNub;

///**
// * Accessing pubnub for the Board
// **/
//$config = readConfig("/etc/apache2/capstone-mysql/faqtAnnakhamsamran.ini");
//$pubNub = json_decode($config["pubnub"]);
//
//$pubNubConf = new PNConfiguration();
//
//$pubNubConf->setSubscribeKey($pubNub->subscribeKey);
//$pubNubConf->setPublishKey($pubNub->publishKey);
//$pubNubConf->setSecretKey($pubNub->secretKey);
//$pubNubConf->setSecure(true);
//
//$pubNubBoard = new PubNub($pubNubConf);



/**
 * API for the Board
 *
 * @author Tristan Bennett tbennett19@cnm.edu
 */
//verify the session, if it is not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/faqtAnnakhamsamran.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$boardProfileId = filter_input(INPUT_GET, "boardProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$boardName = filter_input(INPUT_GET, "boardName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("id cannot be empty or invalid", 405));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets a post by content
		if(empty($id) === false) {
			$board = Board::getBoardByBoardId($pdo, $id);
			if($board !== null) {
				$reply->data = $board;
			}
		} else {
			$boardProfileId = Board::getBoardByBoardProfileId($pdo, $boardProfileId);
			if($boardProfileId !== null) {
				$reply->data = $boardProfileId;
			}
		}
	} elseif($method === "POST") {
		//enforce that the XSRF token is present in the header
		verifyXsrf();
		//enforce the end user has a JWT token
		validateJwtHeader();

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//enforce that an authorized user is trying to create a board
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("You are not allowed to create a board", 403));
		}

		$board = new Board(generateUuidV4(), $_SESSION["profile"]->getProfileId(), $requestObject->boardName);
		$board->insert($pdo);
		// update reply
		$reply->message = $board->getBoardId();
	}


	header("Content-type: application/json");
	if($reply->data === null) {
		unset($reply->data);
	}
	// catch any exceptions that were thrown and update the status and message state variable fields
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTrace();
}
// encode and return reply to front end caller
echo json_encode($reply);

