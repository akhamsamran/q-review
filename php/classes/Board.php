<?php
namespace Edu\Cnm\Faqt;



require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use PHPUnit\Exception;
use Ramsey\Uuid\Uuid;

/**
 * board
 *
 * This is what data is stored when a captain/instructor/proctor (the person running the game) creates a board.
 * This sets the "name" of each specific game for the ledger.
 *
 * @author AnnaKhamsamran <akhamsamran1@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 * @package Edu\Cnm\DataDesign
 **/

class Board implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this Board; this is the primary key
	 * @var Uuid $boardId
	 **/
	private $boardId;
	/**
	 * id of the Profile that created this Board; this is a foreign key
	 * @var Uuid $boardProfileId
	 **/
	private $boardProfileId;
	/**
	 * name of the board-this is to distinguish between games within the ledger
	 * @var string $boardName
	 **/
	private $boardName;

	/**
	 * constructor for this Board
	 *
	 * @param Uuid|string $newBoardId id of this Board or null if new Board
	 * @param Uuid|string $newBoardProfileId id of the Profile of the creator of this Board
	 * @param string $newBoardName the Name of this Board
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds(ie strings too long, integers negative)
	 * @throws \TypeError if data types violates type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php (constructors and destructors)
	 **/
	public function __construct($newBoardId, $newBoardProfileId, string $newBoardName) {
		try {
			$this->setBoardId($newBoardId);
			$this->setBoardProfileId($newBoardProfileId);
			$this->setBoardName($newBoardName);
		}
		//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for board id
	 *
	 * @return Uuid value of the board id
	 **/
	public function getBoardId() : Uuid {
		return($this->boardId);
	}
	/**
	 * mutator method for board id
	 *
	 * @param Uuid|string $newBoardId
	 * @throws \RangeException if $newBoardId is not positive
	 * @throws \TypeError if $newBoardId is not a uuid or string
	 **/
	public function setBoardId($newBoardId) : void {
		try {
			$uuid = self::validateUuid($newBoardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the board id
		$this->boardId = $uuid;
	}

	/**
	 * accessor method for board profile id
	 *
	 * @return Uuid value of board profile id
	 **/
	public function getBoardProfileId() : Uuid {
		return($this->boardProfileId);
	}
	/**
	 * mutator method for board profile id
	 *
	 * @param Uuid|string $newBoardProfileId new value of board profile id
	 * @throws \RangeException if $newBoardProfileId is not positive
	 * @throws \TypeError if the $newBoardProfileId is not a uuid or string
	 **/
	public function setBoardProfileId($newBoardProfileId) : void {
		try {
			$uuid = self::validateUuid($newBoardProfileId);
				} catch(\InvalidArgumentException | \RangeException |\Exception | \TypeError $exception) {
					$exceptionType = get_class($exception->getMessage(), 0, $exception);
			}
			//convert and store the board profile id
		$this->boardProfileId = $uuid;
		}
	/**
	 * accessor method for board name
	 * @return string value of board name
	 **/
	public function getBoardName() : string {
		return($this->boardName);
	}
	/**
	 * mutator method for board name
	 *
	 * @param string $newBoardName new value of board name
	 * @throws \InvalidArgumentException if $newBoardName is not a string or insecure
	 * @throws \RangeException if $newBoardName is >64 characters
	 * @throws \TypeError if $newBoardName is not a string
	 **/
	public function setBoardName(string $newBoardName) : void {
		//verify the board name is secure
		$newBoardName = trim($newBoardName);
		$newBoardName = filter_var($newBoardName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newBoardName) === true) {
			throw(new \InvalidArgumentException("board name is empty or insecure"));
		}
		//verify the board name will fit in the database
		if(strlen($newBoardName) > 64) {
			throw(new \RangeException("board name too long"));
		}
		//store the board name
		$this->boardName = $newBoardName;
	}
	/**
	 * inserts this Board into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		//create query template
		$query = "INSERT INTO board(boardId, boardProfileId, boardName) VALUES(:boardId, :boardProfileId, :boardName)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place-holders on the template
		$parameters = ["boardId" => $this->boardId->getBytes(),"boardProfileId" => $this->boardProfileId->getBytes(), "boardName" => $this->boardName];
		$statement->execute($parameters);
	}
	/**
	 * deletes this Board from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pco is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//create query template
		$query = "DELETE FROM board WHERE boardId = :boardId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holder in the template
		$parameters =["boardId" => $this->boardId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * updates this Board in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		//create query template
		$query = "UPDATE board SET boardProfileId = :boardProfileId, boardName = :boardName WHERE boardId = :boardId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place-holder in the template
		$parameters = ["boardId" => $this->boardId->getBytes(), "boardProfileId" => $this->boardProfileId->getBytes(), "boardName" => $this->boardName];
		$statement->execute($parameters);
	}
	/**
	 * gets the Board by boardId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $boardId board id to search for
	 * @return Board|null Board found or null if not found
	 * @throws \PDOException when mySQL related error occurs
	 * @throws \TypeError when a variable is not correct data type
	 **/
	public static function getBoardByBoardId(\PDO $pdo, $boardId) : ?Board {
		//sanitize the string before searching
		try{
			$boardId = self::validateUuid($boardId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//create query template
		$query = "SELECT boardId, boardProfileId, boardName FROM board WHERE boardId = :boardId";
		$statement = $pdo->prepare($query);
		//bind the board id to the place holder in the template
		$parameters = ["boardId" => $boardId->getBytes()];
		$statement->execute($parameters);
		//grab the board from mySQL
		try {
			$board = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$board = new Board($row["boardId"], $row["boardProfileId"], $row["boardName"]);
			}
		} catch(\Exception $exception) {
			//if the row couldn't be converted, then rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($board);
	}
	/**
	 * gets the board by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $boardProfileId board profile id to search by
	 * @return \SplFixedArray SplFixedArray of board found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getBoardByBoardProfileId(\PDO $pdo, $boardProfileId) : \SplFixedArray {
		try {
			$boardProfileId = self::validateUuid($boardProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT boardId, boardProfileId, boardName FROM board WHERE boardProfileId = :boardProfileId";
		$statement = $pdo->prepare($query);
		//bind the boardProfileId to the place holder in the template
		$parameters = ["boardProfileId" => $boardProfileId->getBytes()];
		$statement->execute($parameters);
		//build an array of boards
		$boards = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$board = new Board($row["boardId"], $row["boardProfileId"], $row["boardName"]);
				$boards[$boards->key()] = $board;
				$boards->next();
			} catch(\Exception $exception) {
				//if the row could not be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($boards);
	}
	/**
	 * gets all Boards
	 *
	 * @param \PDO $pdo PDO connection object
 	* @return \SplFixedArray SplFixedArray of Boards found or null if not fund
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
 	**/
	public static function getAllBoards(\PDO $pdo) : \SPLFixedArray {
		//create query template
		$query = "SELECT boardId, boardProfileId, boardName FROM board";
		$statement = $pdo->prepare($query);
		$statement->execute();
		//built and array of boards
		$boards = new \SplFixedArray(($statement->rowCount()));
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$board = new Board($row["boardId"], $row["boardProfileId"], $row["boardName"]);
				$boards[$boards->key()] = $board;
				$boards->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return ($boards);
		}
	}
		/**
		 * formats the state variables for JSON serialization
		 *
		 * @return array result in state variables to serialize
		 **/
		public function jsonSerialize() {
			$fields = get_object_vars($this);

			$fields["boardId"] = $this->boardId->toString();
			$fields["boardProfileId"] = $this->boardProfileId->toString();

			return($fields);
	}






}
?>