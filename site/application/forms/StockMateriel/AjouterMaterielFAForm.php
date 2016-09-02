

<?php
/**
 * Formulaire pour l'ajout materiel à une FA.
 * @author mmo
 */
class FP_Form_AjouterMaterielFA_Form extends FP_Form_common_Form {
   
    	public function __construct($loginFA = null)
	{
                                
               $this->setAttrib('loginFA', $loginFA);
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
                
        $etat = new Zend_Form_Element_Select('etat');
        $etat->setLabel('État');
        $etat->addMultiOptions((FP_Util_Constantes::$ETAT_MATERIEL));

        $quantite = new Zend_Form_Element_Text('quantite', array(
                                                                    'label'     => 'Quantité',
                                                                    'required'  => true,
                                                                    'filters'   => array('StringTrim')));
        $quantite->addValidator(new Zend_Validate_Float('en_US'));
        $quantite->setValue('1'); 
        
        $loginFA = new Zend_Form_Element_Hidden('loginFA');
        $loginFA->setValue($this->getAttrib('loginFA'));
        
        $this->addElement($loginFA);  
        $this->addElement($materiel);
        $this->addElement($etat);
        $this->addElement($quantite);

	// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
            'class'    => 'btn btn-primary'
        ));
    }
   
}