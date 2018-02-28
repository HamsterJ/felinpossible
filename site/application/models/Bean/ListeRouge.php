<?php
/**
 * ListeRouge model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_ListeRougeMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_ListeRouge extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	protected $date_demande;
	protected $email;
        protected $commentaire;


	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = (int) $id;
	}

	public function getDate_demande() {
		return $this->date_demande;
	}

	public function setDate_demande($date_demande) {
		$this->date_demande = $date_demande;
	}
        
    	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}    
        
        public function getCommentaire() {
		return $this->commentaire;
	}

	public function setCommentaire($commentaire) {
		$this->commentaire = $commentaire;
	}       
}