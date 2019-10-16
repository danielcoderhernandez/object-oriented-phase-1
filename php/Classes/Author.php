<?php>

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




