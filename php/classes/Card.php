<?php

namespace Edu\Cnm\Faqt;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 *created class for card
 *
 * @author Freddy Crawford <fcrawford@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 3.0.0
 **/
class Card implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this card; this is the primary key
	 * @var Uuid $cardId
	 **/
	private $cardId;
	/**
	 *  card category id for this card; this is a foreign key
	 * @var Uuid $cardCategoryId
	 **/
	private $cardCategoryId;
	/**
	 * answer for this card question
	 * @var string $cardAnswer
	 **/
	private $cardAnswer;
	/**
	 * card point value assigned to card
	 * @var int $cardPoints
	 **/
	private $cardPoints;
	/**
	 *card question that is assigned to card
	 * @var string $cardQuestion
	 **/
	private $cardQuestion;
	/**
	 * constructor for this card
	 *
	 * @param string|Uuid $newCardId id of this Card or null if a new card
	 * @param string|Uuid $newCardCategoryId id of the category for this card
	 * @param string $newCardAnswer string containing actual card answer
	 * @param Int $newCardPoints int containing assigned card value
	 * @param string $newCardQuestion string containing question assigned to card
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newCardId, $newCardCategoryId, string $newCardAnswer, int $newCardPoints, string $newCardQuestion) {
		try {
			$this->setCardId($newCardId);
			$this->setCardCategoryId($newCardCategoryId);
			$this->setCardAnswer($newCardAnswer);
			$this->setCardPoints($newCardPoints);
			$this->setCardQuestion($newCardQuestion);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for card id
	 *
	 * @return Uuid value of card id
	 **/
	public function getCardId() : Uuid {
		return($this->cardId);
	}
	/**
	 * mutator method for card id
	 *
	 * @param Uuid|string $newCardId new value of card id
	 * @throws \RangeException if $newCardId is null
	 * @throws \TypeError if $newCardId is not a uuid
	 **/
	public function setCardId( $newCardId) : void {
		try {
			$uuid = self::validateUuid($newCardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the card id
		$this->cardId = $uuid;
	}
	/**
	 * accessor method for card category id
	 *
	 * @return Uuid value of card category id
	 **/
	public function getCardCategoryId() : Uuid{
		return($this->cardCategoryId);
	}
	/**
	 * mutator method for card category id
	 *
	 * @param string|Uuid $newCardCategoryId new value of card category id
	 * @throws \RangeException if $newCardCategory is not positive
	 * @throws \TypeError if $newCardCategoryId is not an uuid
	 **/
	public function setCardCategoryId( $newCardCategoryId) : void {
		try {
			$uuid = self::validateUuid($newCardCategoryId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the card category id
		$this->cardCategoryId = $uuid;
	}
	/**
	 * accessor method for card answer
	 *
	 * @return string value for card answer
	 **/
	public function getCardAnswer() : string {
		return($this->cardAnswer);
	}
	/**
	 * mutator method for card answer
	 *
	 * @param string $newCardAnswer new value of card answer
	 * @throws \InvalidArgumentException if $newCardAnswer is not a string or insecure
	 * @throws \RangeException if $newCardAnswer is > 255 characters
	 * @throws \TypeError if $newCardAnswer is not a string
	 **/
	public function setCardAnswer(string $newCardAnswer) : void {
		// verify the card answer is secure
		$newCardAnswer = trim($newCardAnswer);
		$newCardAnswer = filter_var($newCardAnswer, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCardAnswer) === true) {
			throw(new \InvalidArgumentException("new card answer is empty or insecure"));
		}
		// verify the card answer will fit in the database
		if(strlen($newCardAnswer) > 255) {
			throw(new \RangeException("card answer too large"));
		}
		// store the card answer
		$this->cardAnswer = $newCardAnswer;
	}
	/**
	 *accessor method for card points
	 * @return int for card points
	 **/
	public function getCardPoints() : int {
		return ($this->cardPoints);
	}
	/**
	 * mutator method for card points
	 *
	 * @param int $newCardPoints new value of card points
	 * @throws \InvalidArgumentException if $newCardPoints is not an int or insecure
	 * @throws \RangeException if $newCardPoints is > 255 characters
	 * @throws \TypeError if $newCardPoints is not an int
	 **/
	public function setCardPoints(int $newCardPoints): void {
		// verify the card points are secure
		$newCardPoints = trim($newCardPoints);
		$newCardPoints = filter_var($newCardPoints, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCardPoints) === true) {
			throw(new \InvalidArgumentException("card points are empty or insecure"));
		}
		// verify the card points will fit in the database
		if(strlen($newCardPoints) > 255) {
			throw(new \RangeException("card points is too large"));
		}
		// store the card points
		$this->cardPoints = $newCardPoints;
	}
	/**
	 * accessor method for card question
	 *
	 * @return string value for cardQuestion
	 **/
	public function getCardQuestion() : string {
		return($this->cardQuestion);
	}
	/**
	 * mutator method for card question
	 *
	 * @param string $newCardQuestion new card question
	 * @throws \RangeException if $newCardId is greater than 255
	 * @throws \TypeError if $newCardQuestion is not a string
	 **/
	public function setCardQuestion( $newCardQuestion) : void {
		$newCardQuestion = trim($newCardQuestion);
		$newCardQuestion = filter_var($newCardQuestion, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCardQuestion) === true) {
			throw(new \InvalidArgumentException("card question is empty or insecure"));
		}

		// verify the card question will fit in the database
		if(strlen($newCardQuestion) > 255) {
			throw(new \RangeException("card question is too large"));
		}

		// convert and store the card question
		$this->cardQuestion = $newCardQuestion;
	}
	/**
	 * inserts this Card into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion) VALUES(:cardId, :cardCategoryId, :cardAnswer, :cardPoints, :cardQuestion)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["cardId" => $this->cardId->getBytes(), "cardCategoryId" => $this->cardCategoryId->getBytes(), "cardAnswer" => $this->cardAnswer, "cardPoints" => $this->cardPoints, "cardQuestion" => $this->cardQuestion];
		$statement->execute($parameters);
	}
	/**
	 * deletes this Card from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM card WHERE cardId = :cardId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["cardId" => $this->cardId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * updates this Card in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		// create query template
		$query = "UPDATE card SET cardId = :cardId, cardCategoryId = :cardCategoryId, cardAnswer = :cardAnswer, cardPoints = :cardPoints, cardQuestion = :cardQuestion WHERE cardId = :cardId";
		$statement = $pdo->prepare($query);
		$parameters = ["cardId" => $this->cardId->getBytes(),"cardCategoryId" => $this->cardCategoryId->getBytes(), "cardAnswer" => $this->cardAnswer, "cardPoints" => $this->cardPoints, "cardQuestion" => $this->cardQuestion];
		$statement->execute($parameters);
	}
	/**
	 * gets the Card by cardId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $cardId card id to search for
	 * @return Card|null Card found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getCardByCardId(\PDO $pdo, $cardId) : ?Card {
		// sanitize the cardId before searching
		try {
			$cardId = self::validateUuid($cardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion FROM card WHERE cardId = :cardId";
		$statement = $pdo->prepare($query);
		// bind the card id to the place holder in the template
		$parameters = ["cardId" => $cardId->getBytes()];
		$statement->execute($parameters);
		// grab the card from mySQL
		try {
			$card = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$card = new Card($row["cardId"], $row["cardCategoryId"], $row["cardAnswer"], $row["cardPoints"], $row["cardQuestion"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($card);
	}
	/**
	 * gets the Card by card category id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $cardCategoryId profile id to search by
	 * @return \SplFixedArray SplFixedArray of Cards found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCardByCardCategoryId(\PDO $pdo, string  $cardCategoryId) : \SPLFixedArray {
		try {
			$cardCategoryId = self::validateUuid($cardCategoryId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion FROM card WHERE cardCategoryId = :cardCategoryId";
		$statement = $pdo->prepare($query);
		// bind the card category id to the place holder in the template
		$parameters = ["cardCategoryId" => $cardCategoryId->getBytes()];
		$statement->execute($parameters);
		// build an array of cards
		$cards = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$card = new Card($row["cardId"], $row["cardCategoryId"], $row["cardAnswer"], $row["cardPoints"], $row["cardQuestion"]);
				$cards[$cards->key()] = $card;
				$cards->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($cards);
	}

	/**
	 * gets the card by card points
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $cardPoints card points to search by
	 * @return \SplFixedArray SplFixedArray of cards found
	 * @throws \PDOExceptionwhen mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getCardByCardPoints(\PDO $pdo, $newCardPoints) : \SplFixedArray {
		$newCardPoints = filter_var($newCardPoints, FILTER_VALIDATE_INT);
		//check to see if points is empty or insecure
		if(empty($newCardPoints) === true) {
			throw(new \PDOException("card points empty or insecure"));
		}
		//check to see if points is int
		if(is_int($newCardPoints) !== true) {
			throw(new \InvalidArgumentException("card points is not an integer"));
		}
		// verify the ledger points will fit in the database
		if($newCardPoints > 255) {
			throw(new \RangeException("card points is too large"));
		}
		// create query template
		$query = "SELECT cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion FROM card WHERE cardPoints = :cardPoints";
		$statement = $pdo->prepare($query);
		//bind the categoryProfileId to the place holder in the template
		$parameters = ["cardPoints" => $newCardPoints];
		$statement->execute($parameters);
		//build an array of cards
		$cards = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$card = new Card($row["cardId"], $row["cardCategoryId"], $row["cardAnswer"], $row["cardPoints"], $row["cardQuestion"]);
				$cards[$cards->key()] = $card;
				$cards->next();
			} catch(\Exception $exception) {
				//if the row could not be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($cards);
	}






//	/**
//	 * gets all cards
//	 *
//	 * @param \PDO $pdo PDO connection object
//	 * @return \SplFixedArray SplFixedArray of cards found or null if not found
//	 * @throws \PDOException when mySQL related errors occur
//	 * @throws \TypeError when variables are not the correct data type
//	 **/
//	public static function getAllCards(\PDO $pdo) : \SPLFixedArray {
//		// create query template
//		$query = "SELECT cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion FROM card";
//		$statement = $pdo->prepare($query);
//		$statement->execute();
//		// build an array of cards
//		$cards = new \SplFixedArray($statement->rowCount());
//		$statement->setFetchMode(\PDO::FETCH_ASSOC);
//		while(($row = $statement->fetch()) !== false) {
//			try {
//				$card = new Card($row["cardId"], $row["cardCategoryId"], $row["cardAnswer"], $row["cardPoints"], $row["cardQuestion"]);
//				$cards[$cards->key()] = $card;
//				$cards->next();
//			} catch(\Exception $exception) {
//				// if the row couldn't be converted, rethrow it
//				throw(new \PDOException($exception->getMessage(), 0, $exception));
//			}
//		}
//		return ($cards);
//	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["cardId"] = $this->cardId;
		$fields["cardCategoryId"] = $this->cardCategoryId;
		return ($fields);
	}
}