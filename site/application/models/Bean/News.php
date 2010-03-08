<?php
/**
 * News model
 *
 * Utilizes the Data Mapper pattern to persist data. Represents a single 
 * news entry.
 * 
 * @uses       FP_Model_Mapper_NewsMapper
 * @package    FelinPossible
 * @subpackage Model
 */
class FP_Model_Bean_News extends FP_Model_Bean_Common
{
	/**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $titre;
    
    /**
     * @var string
     */
    protected $contenu;
    
    /**
     * @var int
     */
    protected $timestampCreation;
    
    /**
     * @var date
     */
    protected $dateEvenement;
    
     /**
     * Set entry id
     * 
     * @param  int $id 
     * @return FP_Model_News
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Retrieve entry id
     * 
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }
    
     /**
     * Set entry titre
     * 
     * @param  int $titre 
     * @return FP_Model_News
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * Retrieve titre
     * 
     * @return null|int
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set entry contenu
     * 
     * @param  int $contenu 
     * @return FP_Model_News
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * Retrieve contenu
     * 
     * @return null|int
     */
    public function getContenu()
    {
        return $this->contenu;
    }
    
    /**
     * Set entry timestampCreation
     * 
     * @param  int $timestampCreation
     * @return FP_Model_News
     */
    public function setTimestampCreation($timestampCreation)
    {
        $this->timestampCreation = $timestampCreation;
        return $this;
    }

    /**
     * Retrieve timestampCreation
     * 
     * @return null|int
     */
    public function getTimestampCreation()
    {
        return $this->timestampCreation;
    }
    
    /**
     * Set entry dateEvenement
     * 
     * @param  int $dateEvenement 
     * @return FP_Model_News
     */
    public function setDateEvenement($dateEvenement)
    {
        $this->dateEvenement = $dateEvenement;
        return $this;
    }

    /**
     * Retrieve dateEvenement
     * 
     * @return null|int
     */
    public function getDateEvenement()
    {
        return $this->dateEvenement;
    }
}