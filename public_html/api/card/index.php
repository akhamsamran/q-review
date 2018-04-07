<?php
require_once dirname(__DIR__,3) . "/vendor/autoload.php";
require_once dirname(__DIR__,3) . "/php/classes/autoload.php";
require_once dirname(__DIR__,3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\Faqt\{
	Card,
	// use the Category class for testing only (card is dependent on category)
	Category,
	// use the Profile class for testing only (category is dependent on profile)
	Profile
};

/**
 * api for the Card class
 *
 * @author Anna Khamsamran <akhamsamran1@cnm.edu>
 * @author Tristan Bennett <tbennett19@cnm.edu>
 * @author Dylan McDonald
 * @author George Kephart
 */
//verify the session status. start session if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySql("/etc/apache2/capstone-mysql/faqtAnnakhamsamran.ini");

	// determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$cardCategoryId = filter_input(INPUT_GET, "cardCategoryId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$cardAnswer = filter_input(INPUT_GET, "cardAnswer", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$cardPoints = filter_input(INPUT_GET, "cardPoints", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$cardQuestion = filter_input(INPUT_GET, "cardQuestion", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	// handle GET request - if id is present, that card is returned, otherwise all cards are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific card or all cards and update reply
		if(empty($id) === false) {
			$card = Card::getCardByCardId($pdo, $id);
			if($card !== null) {
				$reply->data = $card;
			}
		} else if(empty($cardCategoryId) === false) {

			$cards = Card::getCardByCardCategoryId($pdo, $cardCategoryId)->toArray();
			if($cards !== null) {

				$cardsInTable = [];
				for($i=0; $i < count($cards); $i++) {
					$cardsInTable[] = (object)["cardId" => $cards[$i]->getCardId(), "cardAnswer" => $cards[$i]->getCardAnswer(), "cardPoints" => $cards[$i]->getCardPoints(), "cardQuestion" => $cards[$i]->getCardQuestion()];
				}

				// sorts $cards by point value from least to greatest
				usort($cardsInTable, function($leftCard, $rightCard) {
					// spaceship operator checks less than, equal to, and greater than returning -1,0,and 1 respectively
					return($leftCard->cardPoints <=> $rightCard->cardPoints);
				});

				for($i = 0; $i < count($cardsInTable) - 1; $i++) {
					if($cardsInTable[$i]->cardPoints === $cardsInTable[$i + 1]->cardPoints) {
						$deleteIndex = random_int(0, 1) + $i;
						unset($cardsInTable[$deleteIndex]);
						$cardsInTable = array_combine(range(0, count($cardsInTable) - 1), $cardsInTable);
						$i--;
					}
				}

				$reply->data = $cardsInTable;
			}
		} else if(empty($cardPoints) === false) {
		$cards = Card::getCardByCardPoints($pdo, $cardPoints)->toArray();
			if($cards !== null) {
				$reply->data = $cards;
			}
		}

		//put or post
	} else if($method === "PUT" || $method === "POST") {

		//enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to write a card", 401));
		}

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		// retrieves the JSON package that the front end sent and stores it in $requestContentHere we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// this like above decodes the JSON package and stores the result in $requestObject

		//make sure card answer is available (required field)
		if(empty($requestObject->cardAnswer) === true) {
			throw(new \InvalidArgumentException ("No card answer.", 405));
		}

		//make sure card points is available (required field)
		if(empty($requestObject->cardPoints) === true) {
			throw(new \InvalidArgumentException ("No card points.", 405));

		}		//make sure card question is available (required field)
		if(empty($requestObject->cardQuestion) === true) {
			throw(new \InvalidArgumentException ("No card question.", 405));
		}

		//perform the actual put or post
		if($method == "PUT") {

			//retrieve the card to update
			$card = Card::getCardByCardId($pdo, $id);
			if($card === null) {
				throw(new RuntimeException("Card does not exist", 404));
			}
			//enforce the user is signed in and only trying to edit their own category
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $category->getCategoryProfileId()->toString()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this category", 403));
			}
			//enforce the category id matches the cardCategoryId to ensure that the user is trying to edit their own card
			if(empty($_SESSION["category"] === true || $_SESSION["category"]->getCategoryId()->toString() !== $card->getCardCategoryId()->toString())) {
				throw(new \InvalidArgumentException("You are not allowed to edit this card", 403));
			}


			validateJwtHeader();

			//update all attributes
			$card->setCardAnswer($requestObject->cardAnswer);
			$card->setCardAnswer($requestObject->cardPoints);
			$card->setCardAnswer($requestObject->cardQuestion);
			$card->update($pdo);

			// reply
			$reply->message = "Card updated OK";



		} else if($method === "POST") {

			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to write cards", 403));
			}

			//enforce the end user has a JWT token
			validateJwtHeader();

			// create a new card and insert it into the database
			$card = new Card(generateUuidV4(), $_SESSION["category"]->getCategoryId(), $requestObject->cardAnswer, null, $requestObject->cardQuestion);
			$card->insert($pdo);

			// update reply
			$reply->message = "Card created OK";
		}

		// delete method
	} else if($method === "DELETE") {

		//enforce that the end user has a XSRF token
		verifyXsrf();

		// retrieve the Card to be deleted
		$card = Card::getCardByCardId($pdo, $id);
		if($card === null) {
			throw(new RuntimeException("Card does not exist", 404));
		}

		//enforce the user is signed in and only trying to edit their own category
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $category->getCategoryProfileId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this category", 403));
		}
		//enforce the category id matches the cardCategoryId to ensure that the user is trying to edit their own card
		if(empty($_SESSION["category"] === true || $_SESSION["category"]->getCategoryId()->toString() !== $card->getCardCategoryId()->toString())) {
			throw(new \InvalidArgumentException("You are not allowed to delete this card", 403));
		}
		//enforce the end user has a JWT token
		validateJwtHeader();

		// delete card
		$card->delete($pdo);
		// update reply
		$reply->message = "Card deleted OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request", 418));
	}
// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);



