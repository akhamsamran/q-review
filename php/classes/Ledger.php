<?php

namespace Edu\Cnm\Faqt;


require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Edu\Cnm\Faqt\JsonObjectStorage;
use Ramsey\Uuid\Uuid;


/**
 * Ledger for Kmaru
 *
 * The ledger will record all interactions with the game, documenting points gained and lost by each player on each question
 * as it relates to the the board.
 *
 * The ledger contains a composite primary key of $ledgerBoardId, $ledgerCardId, and $ledgerProfileId.
 *
 * @author Tristan Bennett <tbennett19@cnm.edu> updated for Kmaru capstone
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 * @package Edu\Cnm\Kmaru
 **/
class Ledger implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for the board on this ledger: this is a foreign key
	 *
	 * @var Uuid $ledgerBoardId
	 **/
	private $ledgerBoardId;

	/**
	 * id for the card on this ledger: this is a foreign key
	 *
	 * @var Uuid $ledgerCardId
	 **/
	private $ledgerCardId;

	/**
	 * id for the Profile on this ledger: this is a foreign key
	 *
	 * @var Uuid $ledgerProfileId
	 **/
	private $ledgerProfileId;

	/**
	 * type of question in ledger: normal (1), wager (2), and final (3)
	 *
	 * @var int $ledgerType
	 **/
	private $ledgerType;

	/**
	 * points awarded or taken by question on this ledger
	 *
	 * @var int $ledgerPoints
	 **/
	private $ledgerPoints;

//******************************************************************************************************************

	/**
	 * constructor for this ledger
	 *
	 * @param Uuid|string $newLedgerBoardId id of this board for this record in ledger
	 * @param Uuid|string $newLedgerCardId id of the card for this record in ledger
	 * @param Uuid|string $newLedgerProfileId id of the profiles in the ledger
	 * @param int $newLedgerPoints signed int value of points for this record in ledger
	 * @param int $newLedgerType type of question for this record in ledger
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if a data type violates a data hint
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newLedgerBoardId, $newLedgerCardId, $newLedgerProfileId, int $newLedgerPoints, int $newLedgerType) {
		try {
			$this->setLedgerBoardId($newLedgerBoardId);
			$this->setLedgerCardId($newLedgerCardId);
			$this->setLedgerProfileId($newLedgerProfileId);
			$this->setLedgerPoints($newLedgerPoints);
			$this->setLedgerType($newLedgerType);

		} catch(\InvalidArgumentException | \RangeException |\TypeError | \Exception $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

//******************************************************************************************************************

	/**
	 * accessor method for $ledgerBoardId
	 *
	 * @return Uuid value for $ledgerBoardId
	 **/
	public function getLedgerBoardId(): Uuid {
		return ($this->ledgerBoardId);
	}

	/**
	 * mutator for ledgerBoardId
	 *
	 * @param Uuid|string $newLedgerBoardId is a new value for ledgerBoardId
	 * @throws \RangeException if $newLedgerBoardId is not positive
	 * @throws \TypeError if $newLedgerBoardId is not a Uuid or string
	 */
	public function setLedgerBoardId($newLedgerBoardId): void {
		try {
			$uuid = self::validateUuid($newLedgerBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store ledgerBoardId
		$this->ledgerBoardId = $uuid;
	}

//*******************************************************************************************************************

	/**
	 * accessor method for $ledgerCardId
	 *
	 * @return Uuid value for $ledgerCardId
	 **/
	public function getLedgerCardId(): Uuid {
		return ($this->ledgerCardId);
	}

	/**
	 * mutator for ledgerCardId
	 *
	 * @param Uuid|string $newLedgerCardId is a new value for ledgerCardId
	 * @throws \RangeException if $newLedgerCardId is not positive
	 * @throws \TypeError if $newLedgerBoardId is not a Uuid or string
	 */
	public function setLedgerCardId($newLedgerCardId): void {
		try {
			$uuid = self::validateUuid($newLedgerCardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store ledgerCardId
		$this->ledgerCardId = $uuid;
	}

//*******************************************************************************************************************

	/**
	 * accessor method for $ledgerProfileId
	 *
	 * @return Uuid value for $ledgerProfileId
	 **/
	public function getLedgerProfileId(): Uuid {
		return ($this->ledgerProfileId);
	}

	/**
	 * mutator for ledgerProfileId
	 *
	 * @param Uuid|string $newLedgerProfileId is a new value for ledger Profile Id
	 * @throws \RangeException if $newLedgerProfileId is not positive
	 * @throws \TypeError if $newLedgerProfileId is not a Uuid or string
	 */
	public function setLedgerProfileId($newLedgerProfileId): void {
		try {
			$uuid = self::validateUuid($newLedgerProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store ledgerProfileId
		$this->ledgerProfileId = $uuid;
	}
//*******************************************************************************************************************

	/**
	 * accessor method for $ledgerPoints
	 *
	 * @return int signed or unsigned value for $ledgerPoints
	 **/
	public function getLedgerPoints(): int {
		return ($this->ledgerPoints);
	}

	/**
	 * mutator method for ledger points $ledgerPoints
	 *
	 * @param int $newLedgerPoints new value of ledger points
	 * @throws \InvalidArgumentException if $newLedgerPoints is not an int or insecure
	 * @throws \RangeException if $newLedgerPoints is > 100000 characters
	 * @throws \TypeError if $newLedgerPoints is not an int
	 **/
	public function setLedgerPoints(int $newLedgerPoints): void {
		// verify the ledger points are integers
		$newLedgerPoints = filter_var($newLedgerPoints, FILTER_VALIDATE_INT);
//		//check to see if points is empty or insecure
//		if(empty($newLedgerPoints) === true) {
//			throw(new \PDOException("ledger points empty or insecure"));
//		}
//		//check to see if ledger points is int
//		if(is_int($newLedgerPoints) !== true) {
//			throw(new \InvalidArgumentException("ledger points is not an integer"));
//		}
		// verify the ledger points will fit in the database
		if($newLedgerPoints > 100000) {
			throw(new \RangeException("ledger points is too large"));
		}
		// store the ledger points
		$this->ledgerPoints = $newLedgerPoints;
	}

//*******************************************************************************************************************

	/**
	 * accessor method for $ledgerType
	 *
	 * @return int unsigned value for $ledgerType
	 **/
	public function getLedgerType(): int {
		return ($this->ledgerType);
	}

	/**
	 * mutator method for ledgerType
	 *
	 * @param int $newLedgerType new value of ledger type
	 * @throws \InvalidArgumentException if $newLedgerType is not an int or insecure
	 * @throws \RangeException if $newLedgerType <= 0 OR > 3
	 * @throws \TypeError if $newLedgerType is not an int
	 **/
	public function setLedgerType(int $newLedgerType): void {
		// verify the ledger type is secure

		$newLedgerType = filter_var($newLedgerType, FILTER_VALIDATE_INT);
		//check to see if ledger type is empty or insecure
		if(empty($newLedgerType) === true) {
			throw(new \PDOException("ledger type is empty or insecure"));
		}
		if(is_int($newLedgerType) !== true) {
			throw(new \InvalidArgumentException("Ledger type is not an integer"));
		}
		// verify the ledger type will fit the game set up
		if($newLedgerType <= 0 || $newLedgerType > 3) {
			throw(new \RangeException("ledger type out of expected range"));
		}
		// store the ledger type
		$this->ledgerType = $newLedgerType;
	}

//*******************************************************************************************************************

	/**
	 * inserts a new ledger into mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// creates the query template. Ready to be formatted and inserted
		$query = "INSERT INTO ledger(ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType) VALUES (:ledgerBoardId, :ledgerCardId, :ledgerProfileId, :ledgerPoints, :ledgerType)";

		// stops direct insert for security reasons. Allows for further formatting.
		$statement = $pdo->prepare($query);

		// bind values of variables to respective placeholders in template
		$parameters = ["ledgerBoardId" => $this->ledgerBoardId->getBytes(), "ledgerCardId" => $this->ledgerCardId->getBytes(), "ledgerProfileId" => $this->ledgerProfileId->getBytes(), "ledgerPoints" => $this->ledgerPoints, "ledgerType" => $this->ledgerType];
		$statement->execute($parameters);
	}

//*******************************************************************************************************************

	/**
	 * Deletes selected ledger from mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM ledger WHERE ledgerBoardId = :ledgerBoardId AND ledgerCardId = :ledgerCardId AND ledgerProfileId = :ledgerProfileId";

		// stops direct deletion
		$statement = $pdo->prepare($query);

		// binds binary value of articleId to placeholder for profileId
		$parameters = ["ledgerBoardId" => $this->ledgerBoardId->getBytes(), "ledgerCardId" => $this->ledgerCardId->getBytes(), "ledgerProfileId" => $this->ledgerProfileId->getBytes()];
		$statement->execute($parameters);
	}


//*******************************************************************************************************************

	/**
	 * gets the Ledgers by ledger board id, ledger card id, and ledger profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerBoardId ledger board id to search by
	 * @param Uuid|string $ledgerCardId ledger card id to search
	 * @param Uuid|string $ledgerProfileId ledger profile id to search
	 * @return Ledger|null Ledger found or null if not
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/

	public static function getLedgerByLedgerBoardIdAndLedgerCardIdAndLedgerProfileId(\PDO $pdo, string $ledgerBoardId, string $ledgerCardId, string $ledgerProfileId): ?Ledger {
		// sanitize the ledgerBoardId before searching
		try {
			$ledgerBoardId = self::validateUuid($ledgerBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// sanitize the ledgerCardId before searching
		try {
			$ledgerCardId = self::validateUuid($ledgerCardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// sanitize the ledgerProfileId before searching
		try {
			$ledgerProfileId = self::validateUuid($ledgerProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType FROM ledger WHERE ledgerBoardId = :ledgerBoardId AND ledgerCardId = :ledgerCardId AND ledgerProfileId = :ledgerProfileId";
		$statement = $pdo->prepare($query);

		//bind the ledger board id and ledger card id and ledger profile id to the place holder in the template
		$parameters = ["ledgerBoardId" => $ledgerBoardId->getBytes(), "ledgerCardId" => $ledgerCardId->getBytes(), "ledgerProfileId" => $ledgerProfileId->getBytes()];
		$statement->execute($parameters);

		// grab the like from mySQL
		try {
			$ledger = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$ledger = new Ledger($row["ledgerBoardId"], $row["ledgerCardId"], $row["ledgerProfileId"], $row["ledgerPoints"], $row["ledgerType"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($ledger);
	}

//*******************************************************************************************************************

	/**
	 * gets the Ledgers by ledger board id and ledger profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerBoardId ledger board id to search by
	 * @param Uuid|string $ledgerProfileId ledger profile id to search
	 * @return \SplFixedArray SplFixedArray of ledgers found
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/


	public static function getLedgersByLedgerBoardIdAndLedgerProfileId(\PDO $pdo, string $ledgerBoardId, string $ledgerProfileId): \SplFixedArray {
		// sanitize the ledgerBoardId before searching
		try {
			$ledgerBoardId = self::validateUuid($ledgerBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// sanitize the ledgerProfileId before searching
		try {
			$ledgerProfileId = self::validateUuid($ledgerProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType FROM ledger WHERE ledgerBoardId = :ledgerBoardId AND ledgerProfileId = :ledgerProfileId";
		$statement = $pdo->prepare($query);

		//bind the ledger board id  and ledger profile id to the place holder in the template
		$parameters = ["ledgerBoardId" => $ledgerBoardId->getBytes(), "ledgerProfileId" => $ledgerProfileId->getBytes()];
		$statement->execute($parameters);

		//build an array of ledgers
		$ledgers = new \SplFixedArray(($statement->rowCount()));
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$ledger = new Ledger($row["ledgerBoardId"], $row["ledgerCardId"], $row["ledgerProfileId"], $row["ledgerPoints"], $row["ledgerType"]);
				$ledgers[$ledgers->key()] = $ledger;
				$ledgers->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($ledgers);
	}

//*******************************************************************************************************************

	/**
	 * gets ledgers by ledger board id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerBoardId ledger board id to search by
	 * @return \SplFixedArray SplFixedArray of ledgers found
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getLedgersByLedgerBoardId(\PDO $pdo, $ledgerBoardId): \SplFixedArray {
		// sanitize the ledgerBoardId before searching
		try {
			$ledgerBoardId = self::validateUuid($ledgerBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType FROM ledger WHERE ledgerBoardId = :ledgerBoardId";

		// stops direct access to database for formatting
		$statement = $pdo->prepare($query);

		// bind the ledger board id to the place holder in the template
		$parameters = ["ledgerBoardId" => $ledgerBoardId->getBytes()];
		$statement->execute($parameters);

		//build an array of ledgers
		$ledgers = new \SplFixedArray(($statement->rowCount()));
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$ledger = new Ledger($row["ledgerBoardId"], $row["ledgerCardId"], $row["ledgerProfileId"], $row["ledgerPoints"], $row["ledgerType"]);
				$ledgers[$ledgers->key()] = $ledger;
				$ledgers->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($ledgers);
	}

//*******************************************************************************************************************

	/**
	 * gets ledgers by ledger card id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerCardId ledger card id to search by
	 * @return \SplFixedArray SplFixedArray of ledgers found
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getLedgersByLedgerCardId(\PDO $pdo, $ledgerCardId): \SplFixedArray {
		// sanitize the ledgerCardId before searching
		try {
			$ledgerCardId = self::validateUuid($ledgerCardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType FROM ledger WHERE ledgerCardId = :ledgerCardId";

		// stops direct access to database for formatting
		$statement = $pdo->prepare($query);

		// bind the ledger card id to the place holder in the template
		$parameters = ["ledgerCardId" => $ledgerCardId->getBytes()];
		$statement->execute($parameters);

		//build an array of ledgers
		$ledgers = new \SplFixedArray(($statement->rowCount()));
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$ledger = new Ledger($row["ledgerBoardId"], $row["ledgerCardId"], $row["ledgerProfileId"], $row["ledgerPoints"], $row["ledgerType"]);
				$ledgers[$ledgers->key()] = $ledger;
				$ledgers->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($ledgers);
	}

//*******************************************************************************************************************

	/**
	 * gets ledgers by ledger profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerProfileId ledger profile id to search by
	 * @return \SplFixedArray SplFixedArray of ledgers found
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getLedgersByLedgerProfileId(\PDO $pdo, $ledgerProfileId): \SplFixedArray {
		// sanitize the ledgerProfileId before searching
		try {
			$ledgerProfileId = self::validateUuid($ledgerProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT ledgerBoardId, ledgerCardId, ledgerProfileId, ledgerPoints, ledgerType FROM ledger WHERE ledgerProfileId = :ledgerProfileId";

		// stops direct access to database for formatting
		$statement = $pdo->prepare($query);

		$parameters = ["ledgerProfileId" => $ledgerProfileId->getBytes()];
		$statement->execute($parameters);

		//build an array of ledgers
		$ledgers = new \SplFixedArray(($statement->rowCount()));
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$ledger = new Ledger($row["ledgerBoardId"], $row["ledgerCardId"], $row["ledgerProfileId"], $row["ledgerPoints"], $row["ledgerType"]);
				$ledgers[$ledgers->key()] = $ledger;
				$ledgers->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($ledgers);
	}



//********************************************************************************************************

	/**
	 * gets points by ledger board id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $ledgerBoardId ledger board id to search by
	 * @return JsonObjectStorage Json object with profile object and points
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getPointsByLedgerBoardId(\PDO $pdo, $ledgerBoardId): JsonObjectStorage {
		// sanitize the ledgerBoardId before searching
		try {
			$ledgerBoardId = self::validateUuid($ledgerBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT
								profileId,
								profileActivationToken,
								profileEmail,
								profileHash,
								profileName,
								profilePrivilege,
								profileSalt,
								profileUsername,
								SUM(ledgerPoints) AS points
					FROM ledger
					INNER JOIN profile ON ledger.ledgerProfileId = profile.profileId
					WHERE ledgerBoardId = :ledgerBoardId
					GROUP BY ledgerProfileId
					ORDER BY points DESC";

		// stops direct access to database
		$statement = $pdo->prepare($query);

		// binds binary value of articleId to placeholder for profileId
		$parameters = ["ledgerBoardId" => $ledgerBoardId->getBytes()];
		$statement->execute($parameters);

		//build an array of players with their respective score
		$leaderBoard = new JsonObjectStorage();
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileName"], $row["profilePrivilege"], $row["profileSalt"], $row["profileUsername"]);
				// attach points to profile object
				$leaderBoard->attach($profile, $row["points"]);
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($leaderBoard);

	}


//*******************************************************************************************************************

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["ledgerBoardId"] = $this->ledgerBoardId->toString();
		$fields["ledgerCardId"] = $this->ledgerCardId->toString();
		$fields["ledgerProfileId"] = $this->ledgerProfileId->toString();
		unset($fields["ledgerPoints"]);
		unset($fields["ledgerType"]);
		return ($fields);
	}
}