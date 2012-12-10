<?php
/**
 * Formulaire pour la saisie d'un animal (autre que chat).
 * @author Benjamin
 *
 */
class FP_Form_common_SaisirAnimalForm extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Ajout type d'animal
        $this->addElement('text', 'typeAnimal', array(
            'label'      => 'Type d\'animal',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        // Ajout race
        $this->addElement('text', 'race', array(
            'label'      => 'Race (pour les chiens)',
            'filters'    => array('StringTrim')
        ));
        
        // Ajout age
        $this->addElement('text', 'age', array(
            'required'   => true,
            'label'      => 'Age',
            'filters'    => array('StringTrim'),
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
            'class'    => 'btn btn-primary'
        ));
        
        $this->setAction("javascript:submit_animal();window.close()");
    }
 
}