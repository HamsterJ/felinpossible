<?php
/**
 * Formulaire pour la saisie d'un animal (chat).
 * @author Benjamin
 *
 */
class FP_Form_common_SaisirAnimalChatForm extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Ajout nom
        $this->addElement('text', 'nom', array(
            'label'      => 'Nom',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        // Ajout age
        $this->addElement('text', 'age', array(
            'required'   => true,
            'label'      => 'Age',
            'filters'    => array('StringTrim'),
        ));
        
        // Ajout vaccins
        $this->addElement('checkbox', 'vacTyphus', array(
            'label'      => 'Vaccin Typhus'
        ));
        $this->addElement('checkbox', 'vacLeucose', array(
            'label'      => 'Vaccin Leucose'
        ));
        $this->addElement('checkbox', 'vacCoryza', array(
            'label'      => 'Vaccin Coryza'
        ));
        $this->addElement('checkbox', 'vacRage', array(
            'label'      => 'Vaccin Rage'
        ));
        
        // Sterilisé
        $this->addElement('checkbox', 'sterilise', array(
            'label'      => 'Est-il stérilisé ?'
        ));
        
        // Vis maison
        $this->addElement('checkbox', 'visMaison', array(
            'label'      => 'Vis-t-il à la maion avec vous ?'
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Ajouter',
        ));
        
        $this->setAction("javascript:submit_chatFA();window.close()");
    }
 
}