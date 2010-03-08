<?php
/**
 * Formulaire pour la gestion d'une FA.
 * @author Benjamin
 *
 */
class FP_Form_fa_GestionForm extends FP_Form_common_Form {
   
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
    public function init() {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setName('infosForm');
        $this->setAttrib('class', 'formOrange');

        $login = new Zend_Form_Element_Text('login', array(
                'label'      => 'Login sur le forum',
                'filters'    => array('StringTrim')
		));
		
        // Elément téléphone fixe
		$telFixe = new FP_Form_common_ElementText('telephoneFixe');
		$telFixe->setLabel('Téléphone fixe');
		$telFixe->setFilters(array('StringTrim'));
		$telFixe->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());

		// Elément téléphone portable
		$telPortable = new FP_Form_common_ElementText('telephonePortable');
		$telPortable->setLabel('Téléphone portable');
		$telPortable->setFilters(array('StringTrim'));
		$telPortable->addValidator(FP_Util_ValidatorUtil::getTelephoneValidator());
		
        $email = new Zend_Form_Element_Text('email', array(
            'label'      => 'Email',
            'required'   => true,
            'size' => 50,
            'filters'    => array('StringTrim'),
            'validators' => array('EmailAddress')
		));
		
		$listeStatut = new Zend_Form_Element_Select('statutId');
		$listeStatut->setLabel('Statut');
		$listeStatut->setRequired(true);
		$listeStatut->addMultiOptions(FP_Model_Mapper_MapperFactory::getInstance()->faStatutMapper->buildArrayForForm());

		$notes = new Zend_Form_Element_Textarea('notes');
		$notes->setLabel('Notes');
		$notes->setAttrib('cols', '60');
		$notes->setAttrib('rows', '5');
		
		$dateSoumission = new Zend_Form_Element_Text('dateSubmit');
		$dateSoumission->setLabel('Date de soumission du formulaire');
		$dateSoumission->setAttrib('dojoType', "dijit.form.DateTextBox");
		
		$dateContratFa = new Zend_Form_Element_Text('dateContratFa');
		$dateContratFa->setLabel('Date réception contrat FA');
		$dateContratFa->setAttrib('dojoType', "dijit.form.DateTextBox");
		
        $idFa = new Zend_Form_Element_Hidden('id');
        
        $this->addElement($login);
        $this->addElement($dateSoumission);
        $this->addElement($dateContratFa);
        $this->addElement($telPortable);
        $this->addElement($telFixe);
        $this->addElement($email);
		$this->addElement($listeStatut);
		$this->addElement($notes);
		$this->addElement($idFa);
                
		// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Valider',
        ));
    }
   
}