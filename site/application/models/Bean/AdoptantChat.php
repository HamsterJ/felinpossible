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
class FP_Model_Bean_AdoptantChat extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $idAd;
	
	/** @var int  */
	protected $idChat;
	
	/**
	  * Retrieve idAd
	  *
	  * @param  idAd
	  * @return la valeur de idAd
	*/
	public function getIdAd() {
	    return (int) $this->idAd;
	}
	
	/**
	  * Set entry idAd
	  *
	  * @param idAd
	*/
	public function setIdAd($idAd) {
	    $this->idAd = (int) $idAd;
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