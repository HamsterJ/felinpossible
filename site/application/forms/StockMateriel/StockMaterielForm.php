<?php
/**
 * Formulaire pour la fiche de chat.
 * @author mmo
 *
 */
class FP_Form_StockMateriel_Form extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('stockMateriel');
        $this->setAttrib('class', 'formOrange');
      
        $DescriptionMateriel = new Zend_Form_Element_Text('DescriptionMateriel', array(
                                                            'label'     => 'Description',
                                                            'required'  => true,
                                                            'filters'   => array('StringTrim')));
       
        $StockEnPret = new Zend_Form_Element_Text('StockEnPret', array(
                                                                    'label'     => 'Stock Prêté',
                                                                    'required'  => true,
                                                                    'filters'   => array('StringTrim')));
        $StockEnPret->addValidator(new Zend_Validate_Regex('/[0-9]+[,\.]{0,1}[0-9]*/'));
        $StockEnPret->setValue(''); 
        
        $StockRestant = new Zend_Form_Element_Text('Stock Restant', array(
                                                                    'label'     => 'Stock Restant',
                                                                    'required'  => true,
                                                                    'filters'   => array('StringTrim')));
        $StockRestant->addValidator(new Zend_Validate_Regex('/[0-9]+[,\.]{0,1}[0-9]*/'));
        $StockRestant->setValue('');       
        
        $Unite = new Zend_Form_Element_Text('Unite', array(
                                                            'label'     => 'Unité',
                                                            'required'  => true,
                                                            'filters'   => array('StringTrim')));
        $Unite->setValue('unite'); 
        
        $Categorie = new Zend_Form_Element_Select('Categorie');
        $Categorie->setLabel('Catégorie');
        $Categorie->addMultiOptions(array_unique(FP_Model_Mapper_MapperFactory::getInstance()->StockCategorieMaterielMapper->buildArrayForForm()));
        $Categorie->setValue(array(0)); 
        
        $SuiviPrets = new Zend_Form_Element_Checkbox('SuiviPrets');
        $SuiviPrets->setLabel('Suivi des prêts');
        $SuiviPrets->setValue(1); 
        
        $id = new Zend_Form_Element_Hidden('id');
        
       
        $this->addElement($id); 
        $this->addElement($DescriptionMateriel);
        $this->addElement($StockEnPret);
        $this->addElement($StockRestant);
        $this->addElement($Unite);
        $this->addElement($Categorie);
        $this->addElement($SuiviPrets);
        
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'OK',
            'class'    => 'btn btn-primary')); 
    }
}