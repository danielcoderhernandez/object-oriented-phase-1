<?php

namespace DanielCoderHernandez\ObjectOriented;

require_once("autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 *Cross Section of Author
 *
 * @author Daniel Hernandez
 * @version 1.0.2
 **/
class Author implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this author; this is the primary key
	 * @var Uuid $authorId ;
	 **/

	private $authorId;

	/**
	 * token handed out to verify that the author is valid and not malicious.
	 * @var string $authorActivationToken
	 **/

	private $authorActivationToken;

	/**
	 * a way for the user to represent themselves
	 * @var string $authorAvatarUrl
	 **/

	private $authorAvatarUrl;

	/**
	 * email for this author; this is a unique index
	 * @var string $authorEmail
	 **/

	private $authorEmail;

	/**
	 * hash for author password
	 * @var string $authorHash
	 **/

	private $authorHash;

	/**
	 * unique username for author
	 * @var string $authorUsername
	 **/

	private $authorUsername;


	/**
	 * constructor for this Author
	 * @param string|Uuid $newAuthorId id of this author or null if a new author id
	 * @param string $newAuthorActivationToken activation token to safe guard against malicious accounts
	 * @param string $newAuthorAvatarUrl string containing avatar url
	 * @param string $newAuthorEmail string containing email
	 * @param string $newAuthorHash string containing new hash
	 * @param string $newAuthorUsername username of author
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if a data type violates a data hint
	 * @throws \Exception if some other exception occurs
	 **/

	public function __construct(string $newAuthorId, ?string $newAuthorActivationToken, string $newAuthorAvatarUrl, string $newAuthorEmail, string $newAuthorHash,
										 string $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUsername($newAuthorUsername);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for the author id
	 *
	 * @return Uuid value of author id (or null if new author)
	 **/

	public function getAuthorId(): Uuid {
		return ($this->authorId);
	}

	/**
	 * mutator method for author id
	 *
	 * @param Uuid| string $newAuthorId value of new author id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if the author Id is not
	 **/

	public function setAuthorId($newAuthorId): void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the author id
		$this->authorId = $uuid;
	}

	/*
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 **/

	public function getAuthorActivationToken(): ?string {
		return ($this->authorActivationToken);
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/

	public function setAuthorActivationToken(?string $newAuthorActivationToken): void {
		if($newAuthorActivationToken === null) {
			$this->authorActivationToken = null;
			return;
		}

		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false) {
			throw(new\RangeException("user activation is not valid"));
		}

		//make sure user activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 32) {
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this->authorActivationToken = $newAuthorActivationToken;
	}

	/**
	 * accessor method for Avatar url
	 *
	 * @return string value of Avatar url or null
	 **/

	public function getAuthorAvatarUrl(): ?string {
		return ($this->authorAvatarUrl);
	}

	/**
	 * mutator method for Avatar
	 *
	 * @param string $newAuthorAvatarUrl new value of avatar
	 * @throws \InvalidArgumentException if $newAvatar is not a string
	 * @throws \RangeException if $newAuthorAvatarUrl is > 255 characters
	 * @throws \TypeError if $newAuthorAvatarUrl is not a string
	 *
	 **/

	public function setAuthorAvatarUrl(?string $newAuthorAvatarUrl): void {
		//if $authorAvatarUrl is null return it right away
		if($newAuthorAvatarUrl === null) {
			$this->authorAvatarUrl = null;
			return;
		}

		//verify the avatar is secure
		$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
		$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorAvatarUrl) === true) {
			throw(new \InvalidArgumentException("Avatar URL is empty or insecure"));
		}

		//verify the avatar will fit in the database
		if(strlen($newAuthorAvatarUrl) > 255) {
			throw(new \RangeException("Avatar is too large"));
		}

		//store the avatar
		$this->authorAvatarUrl = $newAuthorAvatarUrl;
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/

	public function getAuthorEmail(): string {
		return $this->authorEmail;
	}

	/**
	 * mutator method for email
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/

	public function setAuthorEmail(string $newAuthorEmail): void {

		// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("Author email is empty or insecure"));
		}

		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new \RangeException("Author email is too large"));
		}

		// store the email

		$this->authorEmail = $newAuthorEmail;
	}

	/**
	 * accessor method for authorHash
	 *
	 * @return string value of hash
	 */
	public function getAuthorHash(): string {
		return ($this->authorHash);
	}

	/**
	 * mutator method for author hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 97 characters
	 * @throws \TypeError if author hash is not a string
	 **/

	public function setAuthorHash(string $newAuthorHash): void {

		//enforce that the hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		$newAuthorHash = strtolower($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("Author password hash empty or insecure"));
		}

		//enforce the hash is properly formatted as hexadecimal
		if(!ctype_xdigit($newAuthorHash)) {
			throw(new \InvalidArgumentException("Author hash is empty or insecure"));
		}


		//enforce that the hash is exactly 97 characters.
		if(strlen($newAuthorHash) !== 97) {
			throw(new \RangeException("Author hash must be 97 characters"));
		}

		//store the hash
		$this->authorHash = $newAuthorHash;
	}

	/**
	 * accessor method for username
	 *
	 * @return string value of username
	 **/

	public function getAuthorUsername(): string {
		return ($this->authorUsername);
	}

	/**
	 * mutator method for username
	 * @param string $newAuthorUsername new value of username
	 * @throws \InvalidArgumentException if $newUsername is not a valid Username
	 * @throws \RangeException if $newUsername is > 32 characters
	 **/

	public function setAuthorUsername(string $newAuthorUsername): void {

		// verify the username is secure
		$newAuthorUsername = trim($newAuthorUsername);
		$newAuthorUsername = filter_var($newAuthorUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorUsername) === true) {
			throw(new \InvalidArgumentException("Username is empty or invalid"));
		}

		// verify the username will fit in the database
		if(strlen($newAuthorUsername) > 32) {
			throw(new \RangeException("Username is too large"));
		}

		// store the Username
		$this->authorUsername = $newAuthorUsername;
	}

	/**
	 * inserts author into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/

	public function insert(\PDO $pdo): void {


		// create query template
		$query = "INSERT INTO author(authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername) 
							VALUES(:authorId, :authorActivationToken, :authorAvatarUrl, :authorEmail, :authorHash, :authorUsername)";
		$statement = $pdo->prepare($query);

		//bind member variables to the place holder's in this template
		$parameters = ["authorId" => $this->authorId->getBytes(), "authorAvatarUrl" => $this->authorAvatarUrl, "authorActivationToken" => $this->authorActivationToken,
			"authorEmail" => $this->authorEmail, "authorHash" => $this->authorHash, "authorUserName" => $this->authorUsername];
		$statement->execute($parameters);
	}

	/**
	 * deletes this author from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/

	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["authorId" => $this->authorId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this author in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 *
	 **/

	public function update(\PDO $pdo): void {

		// create query template
		$query = "UPDATE author SET authorActivationToken = :authorActivationToken, authorAvatarUrl = :authorAvatarUrl,  
    						authorEmail = :authorEmail, authorHash = :authorHash, authorUsername = :authorUsername 
						WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template

		$parameters = ["authorId" => $this->authorId->getBytes(), "authorActivationToken" => $this->authorActivationToken,
			"authorAvatarUrl" => $this->authorAvatarUrl, "authorEmail" => $this->authorEmail,
			"authorHash" => $this->authorHash, "authorUserName" => $this->authorUsername];
		$statement = $pdo->execute($parameters);
	}

	/**
	 * gets the author by authorId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $authorId author id to search for
	 * @return author|null author found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/

	public static function getAuthorByAuthorId(\PDO $pdo, $authorId): Author {
		// sanitize the authorId before searching

		try {
			$authorId = self::validateUuid($authorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template

		$query = "SELECT authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, 
       					authorUsername FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);

		// bind the author id to the place holder in the template
		$parameters = ["authorId" => $authorId->getBytes()];
		$statement->execute($parameters);

		// grab the author from mySQL
		try {
			$author = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$author = new author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"],
					$row["authorEmail"], $row["authorHash"], $row["authorUsername"]);
			}
		} catch(\Exception $exception) {

			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($author);
	}

	/**
	 * gets the author by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $authorEmail author email to search by
	 * @return \SplFixedArray SplFixedArray of authors found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/

	public static function getAuthorByAuthorEmail(\PDO $pdo, string $authorEmail): \SplFixedArray {
		$authorEmail = trim($authorEmail);
		$authorEmail = filter_var($authorEmail, filter_validate_Email);
		if(empty($authorEmail) === true) {
			throw (new \PDOException("email not valid"));
		}
		$authorEmail = str_replace("_", "\\_", str_replace("%", "\\%", $authorEmail));

		$query = "SELECT authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername FROM author 
						WHERE authorEmail = :authorEmail";
		$statement = $pdo->prepare($query);

		$authorEmail = "%authorEmail%";
		$parameters = ["authorEmail" => $authorEmail];
		$statement = execute($parameters);

		//build an array of authors//
		$authors = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$author = new Author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"], $row["authorEmail"],
					$row["authorHash"], $row["authorUsername"]);
				$authors[$authors->key()] = $author;
				$authors->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($authors);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/

	public function jsonSerialize(): array {
		$fields = get_object_vars($this);
		$fields["authorId"] = $this->authorId->toString();
		unset($fields["authorHash"]);
		return ($fields);
	}
}

