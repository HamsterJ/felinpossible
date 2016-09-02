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
class FP_Model_Bean_StockMaterielFA extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	
	/** @var int  */
	protected $idFA;
        
        protected $login;
        
        /** @var int  */
	protected $idMateriel;
        
        /** @var int  */
	protected $quantite;
        
        /** @var int  */
	protected $idDemandeMateriel;
        
        protected $etat;
	
	public function getId() {
	    return (int) $this->id;
	}
	
	public function setId($id) {
	    $this->id = (int) $id;
	}
	
	public function getIdDemandeMateriel() {
	    return  $this->idDemandeMateriel;
	}
	
	public function setIdDemandeMateriel($idDemandeMateriel) {
	    $this->idDemandeMateriel =  $idDemandeMateriel;
	}
        
	public function getIdMateriel() {
	    return  $this->idMateriel;
	}
	
	public function setIdMateriel($idMateriel) {
	    $this->idMateriel =  $idMateriel;
	}
        
        public function getQuantite() {
	    return  $this->quantite;
	}
	
	public function setQuantite($quantite) {
	    $this->quantite =  $quantite;
	}
        
        
        public function getIdFA() {
	    return  $this->idFA;
	}
	
	public function setIdFA($idFA) {
	    $this->idFA =  $idFA;
	}
        
        public function getLogin() {
	    return  $this->login;
	}
	
	public function setLogin($login) {
	    $this->login =  $login;
	}
        
        public function getEtat() {
	    return  $this->etat;
	}
	
	public function setEtat($etat) {
	    $this->etat =  $etat;
	}
}