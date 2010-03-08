<?php
/**
 * Couleur model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_SexeMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Couleur extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $libelleCouleur;
	/** @var string  */
	protected $motsClefs;
	
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
	  * Retrieve libelleCouleur.
	  *
	  * @param  libelleCouleur
	  * @return la valeur de libelleCouleur
	*/
	public function getLibelleCouleur() {
	    return $this->libelleCouleur;
	}
	
	/**
	  * Set entry libelleCouleur
	  *
	  * @param libelleCouleur
	*/
	public function setLibelleCouleur($libelleCouleur) {
	    $this->libelleCouleur = $libelleCouleur;
	}
	
	/**
	  * Retrieve motsClefs
	  *
	  * @param  motsClefs
	  * @return la valeur de motsClefs
	*/
	public function getMotsClefs() {
	    return $this->motsClefs;
	}
	
	/**
	  * Set entry motsClefs
	  *
	  * @param motsClefs
	*/
	public function setMotsClefs($motsClefs) {
	    $this->motsClefs = $motsClefs;
	}
}