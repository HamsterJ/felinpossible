<?php
/**
 * Post model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_GenericMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_Post extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $content;
	/** @var int  */
	protected $date;
	
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
	  * Retrieve content
	  *
	  * @param  content
	  * @return la valeur de content
	*/
	public function getContent() {
	    return $this->content;
	}
	
	/**
	  * Set entry content
	  *
	  * @param content
	*/
	public function setContent($content) {
	    $this->content = $content;
	}
	
	/**
	  * Retrieve date
	  *
	  * @param  date
	  * @return la valeur de date
	*/
	public function getDate() {
	    return (int) $this->date;
	}
	
	/**
	  * Set entry date
	  *
	  * @param date
	*/
	public function setDate($date) {
	    $this->date = (int) $date;
	}
}