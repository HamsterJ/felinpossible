

<?php
/**
 * Formulaire pour la demande de matériel.
 * @author mmo
 */
class FP_Form_AjouterMateriel_Form extends FP_Form_common_Form {
   
    	public function __construct($idD = null)
	{
                                
               $this->setAttrib('idd', $idD);
                parent::__construct();
  
        }
    
    /**
     * (non-PHPdoc)
     * @see site/library/Zend/Zend_Form#init()
     */
    public function init() {

        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('AjouterMateriel');
        $this->setAttrib('class', 'formOrange');

        $materiel = new Zend_Form_Element_Select('materiel');
        $materiel->setLabel('Materiel');
        $materiel->setMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->StockMaterielMapper->buildArrayForForm());
                
        $idDemandeMateriel = new Zend_Form_Element_Hidden('idDemandeMateriel');
        $idDemandeMateriel->setValue($this->getAttrib('idd'));
        
        $this->addElement($idDemandeMateriel);  
        $this->addElement($materiel);

	// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
            'class'    => 'btn btn-primary'
        ));
    }
   
}