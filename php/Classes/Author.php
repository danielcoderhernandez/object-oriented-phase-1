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
 * Cross Section of a Twitter Profile
 *
 * This is a cross section of what is probably stored about a Twitter user. This entity is a top level entity that
 * holds the keys to the other entities in this example (i.e., Favorite and Profile).
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 4.0.0
 **/

class author {
	/**
	 * id for this Employee; this is the primary key
	 */

	private $authorId;
	/**
	 * id for employee who owns this Profile;
	 */
	private $authorActivationToken;
	/*
	 * api that allows authentication of user
	 */
	private $authorAvatarUrl;
	/*
	 * a way for the user to represent themselves
	 */
	private $authorEmail;
	/*
	 * allows the user to communicate with a unique email
	 */
	private $authorHash;
	/*
	 * hash code for efficient lookup and insertion in data collections
	 */
	private $authorUsername;
	/*
	 * a unique way to represent oneself
	 */

	/**
	 * accessor method for the profile id
	 *
	 * @return int value of authorId
	 */
	public function getAuthorId() {
	return($this->authorId);
	}

	/**
* mutator method for author id
 *
	 * @param Uuid | string $newAuthorId value of new author id
	 * @throws \RangeException if $newAuthorId is not positive
 	 * @throws \TypeError if the profile Id is not
	**/
	public function setAuthorId($newAuthorId): void {
		//verify the author id is valid
		$newAuthorId = filter_var($newAuthorId, FILTER_VALIDATE_INT);
		if($newAuthorId === false) {
			throw (new UnexpectedValueException("author id is not a valid integer"));
		}
		//convert and store the author id
		$this->authorId = intval($newAuthorId);
	}


}
?>
