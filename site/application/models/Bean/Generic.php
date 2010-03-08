<?php
/**
 * Generic model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_GenericMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Generic extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $libelle;
	
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
	  * Retrieve libelle
	  *
	  * @param  libelle
	  * @return la valeur de libelle
	*/
	public function getLibelle() {
	    return $this->libelle;
	}
	
	/**
	  * Set entry libelle
	  *
	  * @param libelle
	*/
	public function setLibelle($libelle) {
	    $this->libelle = $libelle;
	}
}