<?php
require_once dirname(__DIR__,3 ) .  "/php/lib/xsrf.php";
require_once dirname(__DIR__,3 ) .  "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
use Edu\Cnm\Faqt\{Ledger};
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//verify the HTTP method being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// if the HTTP method is head check/start the PHP session and set the XSRF token
	if($method === "POST") {
		if(session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
		verifyXsrf();

		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/faqtAnnakhamsamran.ini");


		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		if (empty($_SESSION["profile"]) === true) {
			throw new InvalidArgumentException("you must be logged in", 400);
		}
		if (empty($requestObject->ledgerBoardId) === true) {
			throw new InvalidArgumentException("no board id", 400);
		}
		$ledger = new Ledger($requestObject->ledgerBoardId,"fa41de8f-f69b-47cd-8b71-6fff8a3a1185", $_SESSION["profile"]->getProfileId(),0, "1");

		$ledger->insert($pdo);

		$reply->message = "tea ready";
	} else {
		throw (new \InvalidArgumentException("Attempting to brew coffee with a teapot", 418));
	}
} catch(\Exception  | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
// encode and return reply to front end caller
echo json_encode($reply);