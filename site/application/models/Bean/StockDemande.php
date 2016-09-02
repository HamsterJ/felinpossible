<?php
/**
 * StockDemande model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_StockDemandeMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_StockDemande extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
        /** @var int  */
	protected $idDemandeMateriel;
        /** @var string  */
	protected $login;
        /** @var int  */
	protected $idFA;
	/** @var date  */
	protected $dateDemande;
	/** @var string  */
	protected $token;
	/** @var int  */
	protected $traitee;
	/** @var string  */
	protected $commentaire;
        /** @var string  */
	protected $submit;
        
        
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setIdDemandeMateriel($idDemandeMateriel)
	{
		$this->idDemandeMateriel =  $idDemandeMateriel;
	}

	public function getIdDemandeMateriel() {
		return $this->idDemandeMateriel;
	}
        
	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login = $login;
	}
        
	public function getIdFA() {
		return $this->idFA;
	}       
        
	public function setIdFA($idFA) {
		$this->idFA = (int) $idFA;
	}
	
        public function getDateDemande() {
            return $this->dateDemande;
        }   
        
        public function setDateDemande($dateDemande) {
            $this->dateDemande = $dateDemande;
        }
        
        public function getSubmit() {
            return $this->submit;
        }

        public function setSubmit($submit) {
            $this->submit = $submit;
        }
        
         public function setToken($token) {
            $this->token = $token;
        }
        public function getToken() {
            return $this->token;
        }
        
        public function setTraitee($traitee) {
            $this->traitee = (int) $traitee;
        }
        public function getTraitee() {
            return $this->traitee;
        }
        
        public function setCommentaire($commentaire) {
            $this->commentaire = $commentaire;
        }
        public function getCommentaire() {
            return $this->commentaire;
        }
}