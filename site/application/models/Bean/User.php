<?php
/**
 * User model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_GenericMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_User extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $login;
	/** @var string  */
	protected $hashPassword;
	/** @var string  */
	protected $nom;
	/** @var string  */
	protected $prenom;
	/** @var string  */
	protected $email;
	/** @var string */
	protected $groupName;

	/**
	 * Retrieve id
	 *
	 * @param  id
	 * @return la valeur de id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set entry id
	 *
	 * @param id
	 */
	public function setId($id) {
		$this->id = (int) $id;
	}

	/**
	 * Retrieve login
	 *
	 * @param  login
	 * @return la valeur de login
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * Set entry login
	 *
	 * @param login
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * Retrieve hashPassword
	 *
	 * @param  hashPassword
	 * @return la valeur de hashPassword
	 */
	public function getHashPassword() {
		return $this->hashPassword;
	}

	/**
	 * Set entry hashPassword
	 *
	 * @param hashPassword
	 */
	public function setHashPassword($hashPassword) {
		$this->hashPassword = $hashPassword;
	}

	/**
	 * Retrieve email
	 *
	 * @param  email
	 * @return la valeur de email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set entry email
	 *
	 * @param email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Retrieve groupName
	 *
	 * @param  groupName
	 * @return la valeur de groupName
	 */
	public function getGroupName() {
		return $this->groupName;
	}

	/**
	 * Set entry groupName
	 *
	 * @param groupName
	 */
	public function setGroupName($groupName) {
		$this->groupName = $groupName;
	}
}