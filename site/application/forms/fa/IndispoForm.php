<?php
/**
 * Formulaire pour la création d'une indisponibilité d'une FA.
 * @author Benjamin
 *
 */
class FP_Form_fa_IndispoForm extends FP_Form_common_Form {
	 
	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#init()
	 */
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setName('indispoForm');
		$this->setAttrib('class', 'formOrange');

		$dateDebut = new Zend_Form_Element_Text('dateDebut');
		$dateDebut->setLabel('Date de début');
		$dateDebut->setRequired(true);
		$dateDebut->setAttrib('dojoType', "dijit.form.DateTextBox");

		$dateFin = new Zend_Form_Element_Text('dateFin');
		$dateFin->setLabel('Date de fin');
		$dateFin->setRequired(true);
		$dateFin->setAttrib('dojoType', "dijit.form.DateTextBox");

		$comment = new Zend_Form_Element_Textarea('commentaires');
		$comment->setLabel('Commentaires');
		$comment->setAttrib('cols', '60');
		$comment->setAttrib('rows', '9');
		
		$idFa = new Zend_Form_Element_Hidden('idFa');
		$idFa->setRequired(true);
		$id = new Zend_Form_Element_Hidden('id');
		
		$this->addElement($dateDebut);
		$this->addElement($dateFin);
		$this->addElement($comment);
		$this->addElement($idFa);
		$this->addElement($id);

		// Add the submit button
		$this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Valider',
		));
	}

	/**
	 * (non-PHPdoc)
	 * @see site/library/Zend/Zend_Form#isValid($data)
	 */
	public function isValid($data) {
		$result = parent::isValid($data);
		 
		if ($result) {
			if ($data['dateDebut'] < $data['dateFin']) {
				return true;
			} else {
				$this->getElement('dateDebut')->addError('La date de début doit être strictement inférieure à la date de fin.');
				return false;
			}
		}
		return $result;
	}
}