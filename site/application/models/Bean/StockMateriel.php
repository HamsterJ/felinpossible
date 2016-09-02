<?php
/**
 * StockMateriel model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single entry.
 *
 * @uses       FP_Model_Mapper_StockMaterielMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_StockMateriel extends FP_Model_Bean_Common
{
	/** @var int  */
	protected $id;
	/** @var string  */
	protected $DescriptionMateriel;
	/** @var int  */
	protected $StockEnPret;
	/** @var int  */
	protected $StockRestant;
	/** @var string  */
	protected $Unite;
        /** @var string  */
	protected $Categorie;
        /** @var int  */
	protected $SuiviPrets;
        
	public function setId($id)
	{
            $this->id = (int) $id;
            return $this;
	}

	public function getId() {
            return $this->id;
	}

 	public function getDescriptionMateriel() {
            return $this->DescriptionMateriel;
	}

	public function setDescriptionMateriel($DescriptionMateriel) {
            $this->DescriptionMateriel = $DescriptionMateriel;
	}
        
       	public function getStockEnPret() {
            return $this->StockEnPret;
	}       
        
	public function setStockEnPret($StockEnPret) {
            $this->StockEnPret =  $StockEnPret;
	}
	
	public function getStockRestant() {
		return $this->StockRestant;
	}       
        
	public function setStockRestant($StockRestant) {
		$this->StockRestant =  $StockRestant;
	}
	
	public function getUnite() {
		return $this->Unite;
	}       
        
	public function setCategorie($Categorie) {
		$this->Categorie =  $Categorie;
	}

	public function getCategorie() {
		return $this->Categorie;
	}       

	public function setUnite($Unite) {
		$this->Unite =  $Unite;
	} 
        
        public function getSuiviPrets() {
		return $this->SuiviPrets;
	}       

	public function setSuiviPrets($SuiviPrets) {
		$this->SuiviPrets =  $SuiviPrets;
	} 
        
}