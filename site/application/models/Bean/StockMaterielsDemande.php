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
class FP_Model_Bean_StockMaterielsDemande extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $materiel;
        /** @var int  */
	protected $idDemandeMateriel;
        /** @var string  */
	protected $quantite;
	
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
        
	public function getMateriel() {
	    return  $this->materiel;
	}
	
	public function setMateriel($materiel) {
	    $this->materiel =  $materiel;
	}
        
        public function getQuantite() {
	    return  $this->quantite;
	}
	
	public function setQuantite($quantite) {
	    $this->quantite =  $quantite;
	}
}