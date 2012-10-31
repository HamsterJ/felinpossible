<?php
/**
 * FicheSoins model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_ChatMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_FicheSoins extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $nom;
	/** @var string  */
	protected $qualite;
	/** @var string  */
	protected $adresse;
	/** @var string  */
	protected $codePostal;
	/** @var string  */
	protected $ville;
	/** @var string  */
	protected $telephoneFixe;
	/** @var string  */
	protected $telephonePortable;
	/** @var string  */
	protected $nomChat;
	/** @var string  */
	protected $couleur;
	/** @var string  */
	protected $identification;
	/** @var string  */
	protected $dateNaissance;
	/** @var boolean  */
	protected $dateNaissanceApprox;
	/** @var string  */
	protected $sexe;
	/** @var string  */
	protected $idVeto;
	
	/**
	 * Set entry id
	 *
	 * @param  int $id
	 * @return FP_Model_News
	 */
	public function setId($id)
	{
		$this->id = (int) $id;
		return $this;
	}

	/**
	 * Retrieve entry id
	 *
	 * @return null|int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Retrieve nom
	 *
	 * @param  string nom
	 * @return la valeur de nom
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set entry nom
	 *
	 * @param nom
	 */
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	/**
	 * Gets the qualite field.
	 * @return the field value.
	 */
	public function getQualite() {
	  return $this->qualite;
	}
	
	/**
	 * Sets the qualite field.
	 */
	public function setQualite($qualite) {
	  $this->qualite = $qualite;
	}
	
	/**
	 * Gets the adresse field.
	 * @return the field value.
	 */
	public function getAdresse() {
	  return $this->adresse;
	}
	
	/**
	 * Sets the adresse field.
	 */
	public function setAdresse($adresse) {
	  $this->adresse = $adresse;
	}
	
	/**
	 * Gets the codePostal field.
	 * @return the field value.
	 */
	public function getCodePostal() {
	  return $this->codePostal;
	}
	
	/**
	 * Sets the codePostal field.
	 */
	public function setCodePostal($codePostal) {
	  $this->codePostal = $codePostal;
	}
	
	/**
	 * Gets the ville field.
	 * @return the field value.
	 */
	public function getVille() {
	  return $this->ville;
	}
	
	/**
	 * Sets the ville field.
	 */
	public function setVille($ville) {
	  $this->ville = $ville;
	}
	
	/**
	 * Gets the telephoneFixe field.
	 * @return the field value.
	 */
	public function getTelephoneFixe() {
	  return $this->telephoneFixe;
	}
	
	/**
	 * Sets the telephoneFixe field.
	 */
	public function setTelephoneFixe($telephoneFixe) {
	  $this->telephoneFixe = $telephoneFixe;
	}
	
	/**
	 * Gets the telephonePortable field.
	 * @return the field value.
	 */
	public function getTelephonePortable() {
	  return $this->telephonePortable;
	}
	
	/**
	 * Sets the telephonePortable field.
	 */
	public function setTelephonePortable($telephonePortable) {
	  $this->telephonePortable = $telephonePortable;
	}
	
	/**
	 * Gets the nomChat field.
	 * @return the field value.
	 */
	public function getNomChat() {
	  return $this->nomChat;
	}
	
	/**
	 * Sets the nomChat field.
	 */
	public function setNomChat($nomChat) {
	  $this->nomChat = $nomChat;
	}
	
	/**
	 * Gets the couleur field.
	 * @return the field value.
	 */
	public function getCouleur() {
	  return $this->couleur;
	}
	
	/**
	 * Sets the couleur field.
	 */
	public function setCouleur($couleur) {
	  $this->couleur = $couleur;
	}
	
	/**
	 * Gets the identification field.
	 * @return the field value.
	 */
	public function getIdentification() {
	  return $this->identification;
	}
	
	/**
	 * Sets the identification field.
	 */
	public function setIdentification($identification) {
	  $this->identification = $identification;
	}
	
	/**
	 * Retrieve dateNaissance
	 *
	 * @param  dateNaissance
	 * @return la valeur de dateNaissance
	 */
	public function getDateNaissance() {
		return $this->dateNaissance;
	}

	/**
	 * Set entry dateNaissance
	 *
	 * @param dateNaissance
	 */
	public function setDateNaissance($dateNaissance) {
		$this->dateNaissance = $dateNaissance;
	}

	/**
	 * Gets the sexe field.
	 * @return the field value.
	 */
	public function getSexe() {
	  return $this->sexe;
	}
	
	/**
	 * Sets the sexe field.
	 */
	public function setSexe($sexe) {
	  $this->sexe = $sexe;
	}

	/**
	 * Gets the idVeto field.
	 * @return the field value.
	 */
	public function getIdVeto() {
	  return $this->idVeto;
	}
	
	/**
	 * Sets the idVeto field.
	 */
	public function setIdVeto($idVeto) {
	  $this->idVeto = $idVeto;
	}
	
	/**
	 * Gets the dateNaissanceApprox field.
	 * @return the field value.
	 */
	public function getDateNaissanceApprox() {
	  return (boolean) $this->dateNaissanceApprox;
	}
	
	/**
	 * Sets the dateNaissanceApprox field.
	 */
	public function setDateNaissanceApprox($dateNaissanceApprox) {
	  $this->dateNaissanceApprox = (boolean) $dateNaissanceApprox;
	}
	
	
}