<?php

namespace DanielCoderHernandez\ObjectOriented;

require_once ("autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
	/**
 	*Cross Section of Author
 	*
 	* @author Daniel Hernandez
 	* @version 1.0.1
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
	 	* @var $authorActivationToken
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
	 	* @var $authorHash
	 	**/
		private $authorHash;
		/**
	 	* unique username for author
	 	* @var string $authorUsername
	 	**/
		private $authorUsername;


		/**
	 	* constructor for this Author
	 	* @param string $newAuthorId id of this author or null if a new author
	 	* @param string $newAuthorActivationToken activation token to safe guard against malicious accounts
	 	* @param string $newAuthorAvatarUrl string containing avatar
	 	* @param string $newAuthorEmail string containing email
	 	* @param string $newAuthorHash string containing password hash
	 	* @param string $newAuthorUsername username of author
	 	* @throws \UnexpectedValueException if any of the parameters are invalid
	 	**/

		public function __construct(string $newAuthorId, ?string $newAuthorActivationToken, string $newAuthorAvatarUrl, string $newAuthorEmail, string $newAuthorHash, ?string $newAuthorUsername) {
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
						throw(new $exceptionType($exception->getMessage(), 97, $exception));
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

		/**
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
	 	* accessor method for Avatar
	 	*
	 	* @return string value of Avatar or null
	 	**/

		public function getAuthorAvatarUrl(): ?string {
				return ($this->authorAvatarUrl);
		}

	/**
	 * mutator method for Avatar
	 *
	 * @param string $newAuthorAvatarUrl new value of avatar
	 * @throws \InvalidArgumentException if $newAvatar is not a string or insecure
	 * @throws \RangeException if $newAuthorAvatarUrl is > 32 characters
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
	 	* @throws \RangeException if the hash is not 128 characters
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
					throw(new \InvalidArgumentException("Author hash must be "));
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

		public function getAuthorUsername(): ?string {
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
		public function insert(\PDO $pdo) : void {

			/**
			// create query template
			$query = "INSERT INTO author(authorId,authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername) 
							VALUES(:authorId, :authorActivationToken, :authorAvatarUrl, :authorEmail, :authorHash, :authorUsername)";
			$statement = $pdo->prepare($query);

			// bind the member variables to the place holders in the template
			$formattedDate = $this->authorDate->format("Y-m-d H:i:s.u");
			$parameters = ["authorId" => $this->authorId->getBytes(), "authorActivationTokenId" => $this->authorActivationToken->getBytes(),
				"authorAvatarUrl" => $this->authorAvatarUrl->getBytes(),"authorEmail" => $this->authorEmail->getBytes(),
				"authorHash" => $this->authorHash->getBytes(),"authorUsername" => $this->authorUsername->getBytes(),"authorDate" => $formattedDate];
			$statement->execute($parameters);
		}
			 **/


		/**
		 * deletes this author from mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
		public function delete(\PDO $pdo) : void {

			// create query template
			$query = "DELETE FROM author WHERE authorId = :authorId";
			$statement = $pdo->prepare($query);

			// bind the member variables to the place holder in the template
			$parameters = ["authorId" => $this->authorId->getBytes()];
			$statement->execute($parameters);
		}

		/**
		 * updates this Tweet in mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError if $pdo is not a PDO connection object
		 **/
		public function update(\PDO $pdo) : void {

			// create query template
			$query = "UPDATE author SET authorId = :authorId, authorActivationToken = :authorActivationToken, tweetDate = :tweetDate WHERE tweetId = :tweetId";
			$statement = $pdo->prepare($query);


			$formattedDate = $this->tweetDate->format("Y-m-d H:i:s.u");
			$parameters = ["tweetId" => $this->tweetId->getBytes(),"tweetProfileId" => $this->tweetProfileId->getBytes(), "tweetContent" => $this->tweetContent, "tweetDate" => $formattedDate];
			$statement->execute($parameters);
		}

		/**
		 * gets the Tweet by tweetId
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param Uuid|string $tweetId tweet id to search for
		 * @return Tweet|null Tweet found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when a variable are not the correct data type
		 **/
		public static function getTweetByTweetId(\PDO $pdo, $tweetId) : ?Tweet {
			// sanitize the tweetId before searching
			try {
				$tweetId = self::validateUuid($tweetId);
			} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}

			// create query template
			$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetId = :tweetId";
			$statement = $pdo->prepare($query);

			// bind the tweet id to the place holder in the template
			$parameters = ["tweetId" => $tweetId->getBytes()];
			$statement->execute($parameters);

			// grab the tweet from mySQL
			try {
				$tweet = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
				}
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
			return($tweet);
		}

		/**
		 * gets the Tweet by profile id
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param Uuid|string $tweetProfileId profile id to search by
		 * @return \SplFixedArray SplFixedArray of Tweets found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getTweetByTweetProfileId(\PDO $pdo, $tweetProfileId) : \SplFixedArray {

			try {
				$tweetProfileId = self::validateUuid($tweetProfileId);
			} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}

			// create query template
			$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetProfileId = :tweetProfileId";
			$statement = $pdo->prepare($query);
			// bind the tweet profile id to the place holder in the template
			$parameters = ["tweetProfileId" => $tweetProfileId->getBytes()];
			$statement->execute($parameters);
			// build an array of tweets
			$tweets = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
					$tweets[$tweets->key()] = $tweet;
					$tweets->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return($tweets);
		}

		/**
		 * gets the Tweet by content
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $tweetContent tweet content to search for
		 * @return \SplFixedArray SplFixedArray of Tweets found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getTweetByTweetContent(\PDO $pdo, string $tweetContent) : \SplFixedArray {
			// sanitize the description before searching
			$tweetContent = trim($tweetContent);
			$tweetContent = filter_var($tweetContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($tweetContent) === true) {
				throw(new \PDOException("tweet content is invalid"));
			}

			// escape any mySQL wild cards
			$tweetContent = str_replace("_", "\\_", str_replace("%", "\\%", $tweetContent));

			// create query template
			$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet WHERE tweetContent LIKE :tweetContent";
			$statement = $pdo->prepare($query);

			// bind the tweet content to the place holder in the template
			$tweetContent = "%$tweetContent%";
			$parameters = ["tweetContent" => $tweetContent];
			$statement->execute($parameters);

			// build an array of tweets
			$tweets = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
					$tweets[$tweets->key()] = $tweet;
					$tweets->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return($tweets);
		}

		/**
		 * gets all Tweets
		 *
		 * @param \PDO $pdo PDO connection object
		 * @return \SplFixedArray SplFixedArray of Tweets found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
		public static function getAllTweets(\PDO $pdo) : \SPLFixedArray {
			// create query template
			$query = "SELECT tweetId, tweetProfileId, tweetContent, tweetDate FROM tweet";
			$statement = $pdo->prepare($query);
			$statement->execute();

			// build an array of tweets
			$tweets = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$tweet = new Tweet($row["tweetId"], $row["tweetProfileId"], $row["tweetContent"], $row["tweetDate"]);
					$tweets[$tweets->key()] = $tweet;
					$tweets->next();
				} catch(\Exception $exception) {
					// if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($tweets);
		}

		public function jsonSerialize() {
			$fields = get_object_vars($this);
			$fields["authorId"] = $this->authorId->toString();
			unset($fields["authorHash"]);
			return ($fields);
		}
}

