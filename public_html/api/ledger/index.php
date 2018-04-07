<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Faqt\{Ledger, Profile, Board, Card};


/**
 *
 *
 *
 **/
//verify the session, start if not active
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
	if ($method === "GET") {
//		if(empty($_SESSION["profile"]) === true) {
//			throw(new \InvalidArgumentException("invalid profile", 401));
//		}
//		validateJwtHeader();

		$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($id) === false) {
			$ledgerLeaderBoard = Ledger::getPointsByLedgerBoardId($pdo, $id);
			if($ledgerLeaderBoard !== null) {
				$reply->data = $ledgerLeaderBoard;
			}
		}
		//

   }else if($method === "POST") {

		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("invalid profile", 401));
		}

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		// retrieves the JSON package that the front end sent and stores it in $requestContentHere we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);

		//
		if(empty($requestObject->ledgerBoardId) === true) {
			throw(new \InvalidArgumentException ("No Board Id found", 405));
		}
		//
		if(empty($requestObject->ledgerCardId) === true) {
			throw(new \InvalidArgumentException ("No Card Id found", 405));
		}
		//
		if(empty($requestObject->ledgerProfileId) === true) {
			throw(new \InvalidArgumentException ("No Profile Id Found", 405));
		}
		//
		if(empty($requestObject->ledgerPoints) === true) {
			throw(new \InvalidArgumentException ("No Points value was found", 405));
		}
		//
		if(empty($requestObject->ledgerType) === true) {
			throw(new \InvalidArgumentException ("No Leger Type was found", 405));
		}

		validateJwtHeader();

		//
		$ledger = new Ledger($requestObject->ledgerBoardId, $requestObject->ledgerCardId, $requestObject->ledgerProfileId, $requestObject->ledgerPoints, $requestObject->ledgerType);
		//
		$ledger->insert($pdo);

		$reply->message = "Ledger recorded OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request", 418));
	}
} catch(\Exception | \TypeError $exception) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
header("Content-type: application/json");
echo json_encode($reply);
