<?php
/*
create table author(
authorId binary(16) not null,
authorActivationToken char(32),
authorAvatarUrl varchar(255),
authorEmail varchar(128) not null,
authorHash char(97) not null,
authorUsername varchar(32) not null,
unique(authorEmail),
unique(authorUsername),
primary key(authorId)
);
*/


namespace DanielCoderHernandez\ObjectOriented;

require_once(dirname(__DIR__, 1) . "/Classes/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *
 * @author Daniel Hernandez
 * @version 1.0.0
 **/

class Author {
	use ValidateUuid;
	/**
	 * id for this author; this is the primary key
	 */
	private $authorId;
	/**
	 * token handed out to verify that the author is valid and not malicious.
	 *@var $authorActivationToken
	 **/
	private $authorActivationToken;
	/*
	 * a way for the user to represent themselves
	 * @var string $authorAvatarUrl
	 */
	private $authorAvatarUrl;
	/**
	 * email for this author; this is a unique index
	 * @var string $authorEmail
	 **/
	private $authorEmail;
	/**
	 * hash for author password
	 * @var $authorHash
	 **/
	private $authorHash;
	/*
	 * unique username for this author
	 * @var $authorUsername
	 */
	private $authorUsername;

	/**
	 * constructor for this Author
	 *
	 * @param string|Uuid $newAuthorId id of this author or null if a new author
	 * @param string $newAuthorActivationToken activation token to safe guard against malicious accounts
	 * @param string $newAuthorAvatarUrl string containing avatar
	 * @param string $newAuthorEmail string containing email
	 * @param string $newAuthorHash string containing password hash
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if a data type violates a data hint
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newAuthorId, ?string $newAuthorActivationToken, string $newAuthorAvatarUrl, string $newAuthorEmail, string $newAuthorHash) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatar($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for the author id
	 *
	 * @return int value of author id
	 */
	public function getAuthorId(): Uuid {
	return($this->authorId);
	}

	/**
	* mutator method for author id
 	*
	 * @param Uuid| string $newAuthorId value of new author id
	 * @throws \RangeException if $newAuthorId is not positive
 	 * @throws \TypeError if the author Id is not
	**/
	public function setAuthorId( $newAuthorId): void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the author id
		$this->authorId = $uuid;
	}
	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 */
	public function getAuthorActivationToken() : ?string {
		return ($this->authorActivationToken);
	}
	/**
	 * mutator method for account activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 */
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
	 * accessor method for Avatar
	 *
	 * @return string value of Avatar
	 */
	public function getAuthorAvatarUrl() {
		return $this->authorAvatarUrl;
	}
	/**
	 * *mutator method for Avatar
	 *
	 */

	public function setAuthorAvatar(string $newAuthorAvatar): void {
		// verify the Avatar will fit in the database
		if(strlen($newAuthorAvatar) > 128) {
			throw(new \RangeException("author avatar is too large"));
		}
		// store the Avatar
		$this->authorAvatar = $newAuthorAvatar;
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
			throw(new \InvalidArgumentException("author email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new \RangeException("author email is too large"));
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
		return $this->authorHash;
	}

	/**
	 * mutator method for author hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if author hash is not a string
	 */
		public function setAuthorHash(string $newAuthorHash): void {
		//enforce that the hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("author password hash empty or insecure"));
		}
		/*enforce the hash is really an Argon hash
		$authorHashInfo = password_get_info($newAuthorHash);
		if($authorHashInfo["algoName"] !== "argon2i") {
			throw(new \InvalidArgumentException("author hash is not a valid hash"));

		}*/
		//enforce that the hash is exactly 97 characters.
		if(strlen($newAuthorHash) !== 97) {
			throw(new \RangeException("author hash must be 97 characters"));
		}
		//store the hash
		$this->authorHash = $newAuthorHash;
	}

}

