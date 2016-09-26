

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
        
        $quantite = new Zend_Form_Element_Text('quantite', array(
                                                                    'label'     => 'Quantité',
                                                                    'required'  => true,
                                                                    'filters'   => array('StringTrim')));
        //$quantite->addValidator(new Zend_Validate_Regex('/[0-9]+[,\.]{0,1}[0-9]*/')); 
        //on ne valide pas le format entré car on n'affiche pas l'unité en live donc on peut tout saisir :(
        $quantite->setValue('1');
        
        $this->addElement($idDemandeMateriel);  
        $this->addElement($materiel);
        $this->addElement($quantite);

	// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
            'class'    => 'btn btn-primary'
        ));
    }
   
}