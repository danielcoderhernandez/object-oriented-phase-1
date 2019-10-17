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


namespace ThisPc\Desktop\bootcamp\git\object-oriented-phase-1;

require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *
 * @author Daniel Hernandez
 * @version 1.0.0
 **/

class author {
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
	 */
	private $authorUsername;

	/**
	 * accessor method for the author id
	 *
	 * @return int value of authorId
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
	public function setProfileId( $newAuthorId): void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
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
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 */


}
?>
