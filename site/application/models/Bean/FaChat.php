<?php
/**
 * Generic model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Bean_Common
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_FaChat extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $idFa;
	
	/** @var int  */
	protected $idChat;
	
	/**
	  * Retrieve idFa
	  *
	  * @param  idFa
	  * @return la valeur de idFa
	*/
	public function getIdFa() {
	    return (int) $this->idFa;
	}
	
	/**
	  * Set entry idFa
	  *
	  * @param idFa
	*/
	public function setIdFa($idFa) {
	    $this->idFa = (int) $idFa;
	}
	
	/**
	  * Retrieve idChat
	  *
	  * @param  idChat
	  * @return la valeur de idChat
	*/
	public function getIdChat() {
	    return (int) $this->idChat;
	}
	
	/**
	  * Set entry idChat
	  *
	  * @param idChat
	*/
	public function setIdChat($idChat) {
	    $this->idChat = (int) $idChat;
	}
}