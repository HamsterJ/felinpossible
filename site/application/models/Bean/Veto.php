<?php
/**
 * Veto model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_VetoMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Veto extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	protected $raison;
	protected $adresse;
	protected $codePostal;
	protected $ville;
	protected $telephoneFixe;
	protected $telephonePortable;
	protected $email;

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
	 * Retrieve raison
	 *
	 * @param  raison
	 * @return la valeur de raison
	 */
	public function getRaison() {
		return $this->raison;
	}

	/**
	 * Set entry raison
	 *
	 * @param raison
	 */
	public function setRaison($raison) {
		$this->raison = $raison;
	}

	/**
	 * Retrieve adresse
	 *
	 * @param  adresse
	 * @return la valeur de adresse
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Set entry adresse
	 *
	 * @param adresse
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
	}

	/**
	 * Retrieve codePostal
	 *
	 * @param  codePostal
	 * @return la valeur de codePostal
	 */
	public function getCodePostal() {
		return $this->codePostal;
	}

	/**
	 * Set entry codePostal
	 *
	 * @param codePostal
	 */
	public function setCodePostal($codePostal) {
		$this->codePostal = $codePostal;
	}

	/**
	 * Retrieve ville
	 *
	 * @param  ville
	 * @return la valeur de ville
	 */
	public function getVille() {
		return $this->ville;
	}

	/**
	 * Set entry ville
	 *
	 * @param ville
	 */
	public function setVille($ville) {
		$this->ville = $ville;
	}

	/**
	 * Retrieve telephoneFixe
	 *
	 * @param  telephoneFixe
	 * @return la valeur de telephoneFixe
	 */
	public function getTelephoneFixe() {
		return $this->telephoneFixe;
	}

	/**
	 * Set entry telephoneFixe
	 *
	 * @param telephoneFixe
	 */
	public function setTelephoneFixe($telephoneFixe) {
		$this->telephoneFixe = $telephoneFixe;
	}

	/**
	 * Retrieve telephonePortable
	 *
	 * @param  telephonePortable
	 * @return la valeur de telephonePortable
	 */
	public function getTelephonePortable() {
		return $this->telephonePortable;
	}

	/**
	 * Set entry telephonePortable
	 *
	 * @param telephonePortable
	 */
	public function setTelephonePortable($telephonePortable) {
		$this->telephonePortable = $telephonePortable;
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

}